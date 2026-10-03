<?php

namespace Tests\Feature\Contact;

use Symfony\Component\Process\Process;
use Tests\TestCase;

class ContactConcurrencyTest extends TestCase
{
    public function test_independent_php_processes_cannot_duplicate_submission_dispatch_or_delivery(): void
    {
        $directory = sys_get_temp_dir().'/kevin-contact-concurrency-'.bin2hex(random_bytes(8));
        mkdir($directory);
        $script = $directory.'/worker.php';
        $database = $directory.'/isolated.sqlite';
        $gate = $directory.'/gate';
        $trace = $directory.'/sends';
        touch($database);
        file_put_contents($script, <<<'PHP'
<?php
require $argv[1].'/vendor/autoload.php';
$app = require $argv[1].'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>$argv[2],
    'database.connections.sqlite.url'=>null, 'database.connections.sqlite.busy_timeout'=>5000,
    'cache.default'=>'database', 'contact.cache_store'=>'database', 'mail.default'=>'array',
    'contact.allow_test_transport'=>true, 'queue.default'=>'database']);
Illuminate\Support\Facades\DB::purge('sqlite');
if ($argv[3] === 'setup') {
    Illuminate\Support\Facades\Artisan::call('migrate', ['--force'=>true]);
    echo 'ready';
    exit;
}
if ($argv[3] === 'inspect') {
    echo json_encode([
        'submissions'=>App\Models\ContactSubmission::count(),
        'deliveries'=>App\Models\ContactMailDelivery::count(),
        'jobs'=>Illuminate\Support\Facades\DB::table('jobs')->count(),
        'accepted'=>App\Models\ContactMailDelivery::where('status','accepted')->count(),
    ]);
    exit;
}
$deadline = microtime(true) + 10;
while (!is_file($argv[4])) {
    if (microtime(true) > $deadline) throw new RuntimeException('Barrier timed out');
    usleep(10000);
}
if ($argv[3] === 'submit') {
    $submission = app(App\Actions\StoreContactSubmission::class)->store([
        'name'=>'Robin Voorbeeld', 'email'=>'parallel@example.com', 'service'=>'website',
        'body'=>'Een fictieve gelijktijdige aanvraag voor een website.', 'attempt'=>'same-unpredictable-test-key',
    ], str_repeat('a',64), '198.51.100.42');
    echo $submission->reference;
} elseif ($argv[3] === 'dispatch') {
    echo app(App\Support\ContactMailDispatcher::class)->dispatch();
} elseif ($argv[3] === 'send') {
    $delivery = App\Models\ContactMailDelivery::firstOrFail();
    $sender = new class($argv[5]) extends App\Support\ContactMailSender {
        public function __construct(private string $trace) {}
        public function send(App\Models\ContactMailDelivery $delivery): array {
            file_put_contents($this->trace, "accepted\n", FILE_APPEND | LOCK_EX);
            usleep(200000);
            return ['status'=>'accepted','category'=>null,'retry'=>false];
        }
    };
    (new App\Jobs\SendContactMail($delivery->id,$delivery->dispatch_token))->handle($sender);
}
PHP);
        $processes = [];
        $process = function (string $action) use ($script, $database, $gate, $trace, $directory): Process {
            return new Process([PHP_BINARY, '-d', 'xdebug.mode=off', $script, base_path(), $database, $action, $gate, $trace], base_path(), [
                'APP_ENV' => 'testing', 'APP_CONFIG_CACHE' => $directory.'/absent-config.php',
                'DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => $database, 'DB_URL' => '',
                'MAIL_MAILER' => 'array', 'SESSION_DRIVER' => 'array',
            ], timeout: 40);
        };
        try {
            $process('setup')->mustRun();
            foreach (['submit', 'dispatch', 'send'] as $action) {
                if (is_file($gate)) {
                    unlink($gate);
                }
                $pair = [$process($action), $process($action)];
                foreach ($pair as $worker) {
                    $processes[] = $worker;
                    $worker->start();
                }
                touch($gate);
                foreach ($pair as $worker) {
                    $worker->wait();
                    $this->assertTrue($worker->isSuccessful(), $worker->getErrorOutput().$worker->getOutput());
                }
                if ($action === 'submit') {
                    $this->assertSame($pair[0]->getOutput(), $pair[1]->getOutput());
                }
            }
            $state = json_decode($process('inspect')->mustRun()->getOutput(), true, flags: JSON_THROW_ON_ERROR);
            $this->assertSame(['submissions' => 1, 'deliveries' => 2, 'jobs' => 2, 'accepted' => 1], $state);
            $this->assertSame("accepted\n", file_get_contents($trace));
        } finally {
            foreach ($processes as $worker) {
                if ($worker->isRunning()) {
                    $worker->stop();
                }
            }
            foreach ([$script, $database, $database.'-wal', $database.'-shm', $gate, $trace] as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($directory);
        }
    }
}
