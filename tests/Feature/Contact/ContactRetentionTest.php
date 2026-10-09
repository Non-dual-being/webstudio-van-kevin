<?php

namespace Tests\Feature\Contact;

use App\Jobs\SendContactMail;
use App\Models\ContactSubmission;
use App\Support\ContactMailSender;
use App\Support\ContactRetention;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactRetentionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->freezeTime();
        config([
            'contact.retention_days' => null,
            'queue.connections.database.connection' => 'sqlite',
            'queue.failed.database' => 'sqlite',
            'queue.failed.driver' => 'database-uuids',
        ]);
        Mail::fake();
    }

    private function submission(string $status = 'accepted', int $age = 120, int $finishedAge = 100): ContactSubmission
    {
        $submission = ContactSubmission::create([
            'reference' => (string) Str::ulid(), 'attempt_hash' => hash('sha256', Str::random()),
            'session_hash' => str_repeat('b', 64), 'payload_hash' => str_repeat('c', 64),
            'name' => 'Fictieve Privacyproef', 'email' => 'privacy-fixture@example.com',
            'body' => 'Dit is uitsluitend verzonnen inhoud voor de geïsoleerde test.',
            'service' => 'website', 'created_at' => now()->subDays($age), 'updated_at' => now()->subDays($age),
        ]);
        foreach (['notification', 'receipt'] as $kind) {
            $submission->deliveries()->create([
                'kind' => $kind, 'status' => $status, 'attempts' => 1, 'attempt_limit' => 3,
                'dispatch_token' => (string) Str::uuid(), 'available_at' => now()->subDays($age),
                'finished_at' => now()->subDays($finishedAge),
            ]);
        }

        return $submission;
    }

    public function test_default_is_read_only_and_output_contains_only_aggregate_information(): void
    {
        $submission = $this->submission();
        $queries = [];
        DB::listen(function ($event) use (&$queries) {
            $queries[] = $event->sql;
        });
        Log::spy();

        $this->assertSame(0, Artisan::call('contact:prune'));
        $output = Artisan::output();
        $this->assertStringContainsString('DRY-RUN', $output);
        $this->assertStringContainsString('Bewaarbeleid niet ingesteld', $output);
        foreach ([$submission->name, $submission->email, $submission->body, $submission->reference, $submission->session_hash] as $private) {
            $this->assertStringNotContainsString($private, $output);
        }
        foreach ($queries as $sql) {
            $this->assertDoesNotMatchRegularExpression('/\b(insert|update|delete)\b/i', $sql);
            $this->assertDoesNotMatchRegularExpression('/\b(name|email|body|reference|session_hash|payload_hash|attempt_hash|payload|exception)\b/i', $sql);
        }
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseCount('contact_mail_deliveries', 2);
        Log::shouldNotHaveReceived('info');
        Log::shouldNotHaveReceived('warning');
        Log::shouldNotHaveReceived('error');
        Mail::assertNothingSent();
    }

    public function test_execution_requires_an_approved_configuration_and_disallows_ad_hoc_overrides(): void
    {
        $this->submission();
        $this->artisan('contact:prune', ['--execute' => true])->assertFailed();
        config(['contact.retention_days' => 90]);
        $this->artisan('contact:prune', ['--execute' => true, '--days' => 1])->assertFailed();
        $this->artisan('contact:prune', ['--days' => 1])->assertSuccessful();
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    #[DataProvider('invalidTerms')]
    public function test_invalid_retention_configuration_never_deletes(mixed $days): void
    {
        $this->submission();
        config(['contact.retention_days' => $days]);
        $this->artisan('contact:prune', ['--execute' => true])->assertFailed();
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public static function invalidTerms(): array
    {
        return [[0], [-1], ['1.5'], ['90 days'], [true], [36501], ['']];
    }

    public function test_only_old_complete_pairs_are_deleted_with_their_restricted_foreign_keys(): void
    {
        config(['contact.retention_days' => 90]);
        $eligible = $this->submission();
        $this->submission('intercepted');
        $this->submission(age: 20, finishedAge: 10);
        $this->submission(finishedAge: 89);
        $this->submission(finishedAge: 90);
        $missing = $this->submission();
        $missing->deliveries()->where('kind', 'receipt')->delete();
        $unfinished = $this->submission();
        $unfinished->deliveries()->where('kind', 'receipt')->update(['finished_at' => null]);
        $extra = $this->submission();
        $extra->deliveries()->create(['kind' => 'unknown', 'status' => 'accepted', 'available_at' => now(), 'finished_at' => now()->subDays(100)]);
        $this->assertSame(1, (int) DB::scalar('PRAGMA foreign_keys'));

        $report = app(ContactRetention::class)->inspect(now()->toImmutable()->subDays(90));
        $this->assertSame(['old' => 7, 'eligible' => 2, 'unresolved' => 3, 'retained' => 2, 'queue_blocked' => false], $report);
        $staleDelivery = $eligible->deliveries()->first();
        $this->artisan('contact:prune', ['--execute' => true])->assertSuccessful();
        $this->assertDatabaseMissing('contact_submissions', ['id' => $eligible->id]);
        $this->assertDatabaseCount('contact_submissions', 6);
        $this->assertDatabaseCount('contact_mail_deliveries', 12);

        $sender = $this->mock(ContactMailSender::class);
        $sender->shouldNotReceive('send');
        (new SendContactMail($staleDelivery->id, $staleDelivery->dispatch_token))->handle($sender);
        Mail::assertNothingSent();
    }

    #[DataProvider('unresolvedStates')]
    public function test_old_unresolved_deliveries_are_reported_and_never_pruned(string $status): void
    {
        config(['contact.retention_days' => 90]);
        $submission = $this->submission();
        $submission->deliveries()->where('kind', 'receipt')->update(['status' => $status]);
        $report = app(ContactRetention::class)->inspect(now()->toImmutable()->subDays(90));
        $this->assertSame(1, $report['unresolved']);
        $this->assertSame(0, $report['eligible']);
        $this->artisan('contact:prune', ['--execute' => true])->assertSuccessful();
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseCount('contact_mail_deliveries', 2);
    }

    public static function unresolvedStates(): array
    {
        return array_map(fn ($status) => [$status], ['pending', 'queued', 'processing', 'failed', 'uncertain', 'unknown']);
    }

    #[DataProvider('queueStates')]
    public function test_waiting_reserved_and_failed_jobs_block_deletion_without_being_removed(string $table, bool $reserved): void
    {
        config(['contact.retention_days' => 90]);
        $this->submission();
        DB::table($table)->insert($table === 'jobs' ? [
            'queue' => 'contact-mail', 'payload' => '{}', 'attempts' => 0,
            'reserved_at' => $reserved ? now()->timestamp : null,
            'available_at' => now()->timestamp, 'created_at' => now()->timestamp,
        ] : [
            'uuid' => (string) Str::uuid(), 'connection' => 'database', 'queue' => 'contact-mail',
            'payload' => '{}', 'exception' => 'Fictieve fout zonder persoonsgegevens', 'failed_at' => now(),
        ]);
        $this->artisan('contact:prune')->assertSuccessful();
        $this->artisan('contact:prune', ['--execute' => true])->assertFailed();
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseCount($table, 1);
    }

    public static function queueStates(): array
    {
        return [['jobs', false], ['jobs', true], ['failed_jobs', false]];
    }

    public function test_a_database_failure_rolls_back_both_intents_and_parent_without_leaking_the_exception(): void
    {
        config(['contact.retention_days' => 90]);
        $this->submission();
        DB::unprepared("CREATE TRIGGER prevent_test_delete BEFORE DELETE ON contact_submissions BEGIN SELECT RAISE(ABORT, 'private-fixture@example.com'); END;");
        $this->assertSame(1, Artisan::call('contact:prune', ['--execute' => true]));
        $this->assertStringNotContainsString('private-fixture@example.com', Artisan::output());
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseCount('contact_mail_deliveries', 2);
    }

    public function test_unknown_or_separate_queue_storage_fails_closed(): void
    {
        config(['contact.retention_days' => 90]);
        $this->submission();
        foreach ([['queue.connections.database.connection' => 'mysql'], ['queue.failed.driver' => 'null']] as $settings) {
            config($settings);
            $this->artisan('contact:prune', ['--execute' => true])->assertFailed();
            config(['queue.connections.database.connection' => 'sqlite', 'queue.failed.driver' => 'database-uuids']);
        }
        $this->assertDatabaseCount('contact_submissions', 1);
    }
}
