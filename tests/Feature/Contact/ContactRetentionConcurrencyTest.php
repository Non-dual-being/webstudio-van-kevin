<?php

namespace Tests\Feature\Contact;

use Symfony\Component\Process\Process;
use Tests\TestCase;

class ContactRetentionConcurrencyTest extends TestCase
{
    public function test_two_pruners_and_an_active_mail_worker_preserve_the_active_request(): void
    {
        $directory = sys_get_temp_dir().'/kevin-retention-concurrency-'.bin2hex(random_bytes(8));
        mkdir($directory);
        $script = $directory.'/worker.php';
        $database = $directory.'/isolated.sqlite';
        touch($database);
        file_put_contents($script, <<<'PHP'
<?php
require $argv[1].'/vendor/autoload.php';
$app = require $argv[1].'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
config(['database.default'=>'sqlite', 'database.connections.sqlite.database'=>$argv[2],
    'database.connections.sqlite.url'=>null, 'database.connections.sqlite.busy_timeout'=>5000,
    'queue.connections.database.connection'=>'sqlite', 'queue.failed.database'=>'sqlite',
    'queue.failed.driver'=>'database-uuids', 'mail.default'=>'array', 'contact.retention_days'=>90]);
Illuminate\Support\Facades\DB::purge('sqlite');
function barrier(string $file): void {
    $deadline = microtime(true) + 20;
    while (!is_file($file)) {
        if (microtime(true) > $deadline) throw new RuntimeException('Barrier timed out');
        usleep(10000);
    }
}
if ($argv[3] === 'setup') {
    Illuminate\Support\Facades\Artisan::call('migrate', ['--force'=>true]);
    foreach (['complete', 'active'] as $state) {
        $submission = App\Models\ContactSubmission::create([
            'reference'=>(string) Illuminate\Support\Str::ulid(), 'attempt_hash'=>hash('sha256',$state),
            'session_hash'=>str_repeat('b',64), 'payload_hash'=>str_repeat('c',64),
            'name'=>'Fictieve Test', 'email'=>'fiction@example.com', 'body'=>'Uitsluitend fictieve testinhoud.',
            'created_at'=>now()->subDays(150), 'updated_at'=>now()->subDays(150),
        ]);
        foreach (['notification','receipt'] as $kind) {
            $active = $state === 'active' && $kind === 'notification';
            $submission->deliveries()->create([
                'kind'=>$kind, 'status'=>$active ? 'queued' : 'intercepted',
                'dispatch_token'=>(string) Illuminate\Support\Str::uuid(),
                'available_at'=>now()->subDays(150), 'finished_at'=>$active ? null : now()->subDays(100),
            ]);
        }
    }
} elseif ($argv[3] === 'send') {
    $delivery = App\Models\ContactMailDelivery::where('status','queued')->sole();
    $sender = new class($argv[4]) extends App\Support\ContactMailSender {
        public function __construct(private string $directory) {}
        public function send(App\Models\ContactMailDelivery $delivery): array {
            touch($this->directory.'/sending');
            barrier($this->directory.'/release');
            return ['status'=>'intercepted','category'=>null,'retry'=>false];
        }
    };
    (new App\Jobs\SendContactMail($delivery->id,$delivery->dispatch_token))->handle($sender);
} elseif ($argv[3] === 'prune') {
    touch($argv[4].'/ready-'.$argv[5]);
    barrier($argv[4].'/prune-gate');
    $exit = Illuminate\Support\Facades\Artisan::call('contact:prune', ['--execute'=>true]);
    echo Illuminate\Support\Facades\Artisan::output();
    exit($exit);
} elseif ($argv[3] === 'inspect') {
    echo json_encode([
        'submissions'=>App\Models\ContactSubmission::count(),
        'deliveries'=>App\Models\ContactMailDelivery::count(),
        'processing'=>App\Models\ContactMailDelivery::where('status','processing')->count(),
        'eligible'=>app(App\Support\ContactRetention::class)->inspect(now()->toImmutable()->subDays(90))['eligible'],
    ]);
}
PHP);
        $processes = [];
        $process = fn (string $action, string $name = 'final') => new Process(
            [PHP_BINARY, '-d', 'xdebug.mode=off', $script, base_path(), $database, $action, $directory, $name],
            base_path(), [
                'APP_ENV' => 'testing', 'APP_CONFIG_CACHE' => $directory.'/absent-config.php',
                'DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => $database, 'DB_URL' => '',
                'DB_QUEUE_CONNECTION' => 'sqlite', 'MAIL_MAILER' => 'array', 'SESSION_DRIVER' => 'array',
            ], timeout: 40,
        );
        $waitFor = function (string $file): void {
            $deadline = microtime(true) + 20;
            while (! is_file($file)) {
                $this->assertLessThan($deadline, microtime(true), 'Worker barrier timed out');
                usleep(10000);
            }
        };
        try {
            $process('setup')->mustRun();
            $sender = $process('send');
            $processes[] = $sender;
            $sender->start();
            $waitFor($directory.'/sending');
            $pair = [$process('prune', 'one'), $process('prune', 'two')];
            foreach ($pair as $worker) {
                $processes[] = $worker;
                $worker->start();
            }
            $waitFor($directory.'/ready-one');
            $waitFor($directory.'/ready-two');
            touch($directory.'/prune-gate');
            $deleted = 0;
            foreach ($pair as $worker) {
                $worker->wait();
                $this->assertTrue($worker->isSuccessful(), $worker->getErrorOutput().$worker->getOutput());
                $this->assertSame(1, preg_match('/Verwijderde aanvragen: (\d+)/', $worker->getOutput(), $matches));
                $deleted += (int) $matches[1];
            }
            $this->assertSame(1, $deleted);
            $state = json_decode($process('inspect')->mustRun()->getOutput(), true, flags: JSON_THROW_ON_ERROR);
            $this->assertSame(['submissions' => 1, 'deliveries' => 2, 'processing' => 1, 'eligible' => 0], $state);
            touch($directory.'/release');
            $sender->wait();
            $this->assertTrue($sender->isSuccessful(), $sender->getErrorOutput());
            $process('prune')->mustRun();
            $state = json_decode($process('inspect')->mustRun()->getOutput(), true, flags: JSON_THROW_ON_ERROR);
            $this->assertSame(['submissions' => 1, 'deliveries' => 2, 'processing' => 0, 'eligible' => 0], $state);
        } finally {
            foreach ($processes as $worker) {
                if ($worker->isRunning()) {
                    $worker->stop();
                }
            }
            foreach (['worker.php', 'isolated.sqlite', 'isolated.sqlite-wal', 'isolated.sqlite-shm', 'sending', 'release', 'prune-gate', 'ready-one', 'ready-two', 'ready-final'] as $file) {
                if (is_file($directory.'/'.$file)) {
                    unlink($directory.'/'.$file);
                }
            }
            rmdir($directory);
        }
    }
}
