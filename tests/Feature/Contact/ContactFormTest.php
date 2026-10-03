<?php

namespace Tests\Feature\Contact;

use App\Jobs\SendContactMail;
use App\Models\ContactMailDelivery;
use App\Models\ContactSubmission;
use App\Support\ContactMailDispatcher;
use Illuminate\Bus\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['contact.cache_store' => 'database', 'mail.default' => 'array']);
        Bus::fake();
        Mail::fake();
    }

    /** @return array<string, string> */
    private function fields(): array
    {
        $response = $this->get(route('contact.create'));
        $response->assertOk();

        return ['name' => 'Robin Voorbeeld', 'email' => 'robin@example.com', 'service' => 'website',
            'body' => 'Een fictieve aanvraag voor een heldere website voor onze vereniging.',
            'website' => '', 'attempt' => $response->viewData('page')['props']['attempt']];
    }

    public function test_form_exposes_only_options_and_a_session_bound_attempt(): void
    {
        $this->get('/contact')->assertInertia(fn (Assert $page) => $page->component('Contact')
            ->has('services', 4)->where('services.0.label', 'Websites')->where('received', false)
            ->where('attempt', fn ($attempt) => strlen($attempt) === 113)->missing('submissions'));
    }

    public function test_valid_attempt_is_saved_once_with_two_jobs_after_commit_and_no_http_mail(): void
    {
        $data = $this->fields();
        $this->post('/contact', [...$data, 'from' => 'injection@example.com', 'recipient' => 'injection@example.com'])
            ->assertRedirect('/contact')->assertSessionHas('contact.received', true);
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertDatabaseCount('contact_mail_deliveries', 2);
        $submission = ContactSubmission::query()->sole();
        $this->assertSame('Websites', $submission->enquiry()->service);
        $this->assertSame(['notification', 'receipt'], $submission->deliveries()->orderBy('id')->pluck('kind')->all());
        Bus::assertDispatchedTimes(SendContactMail::class, 2);
        Bus::assertDispatched(SendContactMail::class, function (SendContactMail $job) use ($data) {
            $this->assertTrue($job->afterCommit);
            $this->assertSame('database', $job->connection);
            $this->assertSame('contact-mail', $job->queue);
            $this->assertStringNotContainsString($data['body'], serialize($job));
            $this->assertStringNotContainsString($data['email'], serialize($job));

            return true;
        });
        $this->post('/contact', $data)->assertSessionHas('contact.received', true);
        $this->assertDatabaseCount('contact_submissions', 1);
        Bus::assertDispatchedTimes(SendContactMail::class, 2);
        Mail::assertNothingSent();
    }

    public function test_conflicting_payload_and_another_session_cannot_reuse_a_saved_attempt(): void
    {
        $data = $this->fields();
        $this->post('/contact', $data)->assertSessionHasNoErrors();
        $this->post('/contact', [...$data, 'body' => 'Een ander bericht met dezelfde ongeldige formulierpoging.'])
            ->assertSessionHasErrors('attempt');
        $this->withSession(['contact.owner' => str_repeat('a', 64)])->post('/contact', $data)
            ->assertSessionHasErrors('attempt');
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertSame($data['body'], ContactSubmission::query()->sole()->body);
        Bus::assertDispatchedTimes(SendContactMail::class, 2);
    }

    #[DataProvider('invalidFields')]
    public function test_invalid_input_or_honeypot_stores_and_sends_nothing(string $field, mixed $value): void
    {
        $data = $this->fields();
        $this->post('/contact', [...$data, $field => $value])->assertSessionHasErrors($field);
        $this->assertDatabaseCount('contact_submissions', 0);
        $this->assertDatabaseCount('contact_mail_deliveries', 0);
        Bus::assertNothingDispatched();
        Mail::assertNothingSent();
    }

    /** @return array<string, array{string, mixed}> */
    public static function invalidFields(): array
    {
        return [
            'name required' => ['name', ''], 'name array' => ['name', ['x']],
            'header in email' => ['email', "robin@example.com\r\nBcc: other@example.com"],
            'email too long' => ['email', str_repeat('a', 250).'@example.com'],
            'unknown service' => ['service', 'anything'], 'body too short' => ['body', 'Kort'],
            'body too long' => ['body', str_repeat('x', 5001)],
            'control characters' => ['body', "Een bericht met ongeldige \x00 tekens erin."],
            'honeypot' => ['website', 'https://spam.example.com'], 'invalid attempt' => ['attempt', str_repeat('a', 113)],
        ];
    }

    public function test_normalized_email_limit_is_shared_across_ips_and_does_not_block_safe_retries(): void
    {
        config(['contact.email_limit' => 1]);
        $first = $this->fields();
        $this->post('/contact', [...$first, 'email' => ' ROBIN@EXAMPLE.COM '])->assertSessionHasNoErrors();
        $second = $this->fields();
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.2'])->post('/contact', $second)->assertSessionHasErrors('form');
        $this->post('/contact', $first)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('contact_submissions', 1);
        foreach (DB::table('cache')->pluck('key') as $key) {
            $this->assertStringNotContainsString('robin', $key);
            $this->assertStringNotContainsString('example.com', $key);
        }
    }

    public function test_ip_limit_applies_across_email_addresses_and_expires(): void
    {
        config(['contact.ip_limit' => 1]);
        $this->post('/contact', $this->fields())->assertSessionHasNoErrors();
        $second = [...$this->fields(), 'email' => 'second@example.com'];
        $this->post('/contact', $second)->assertSessionHasErrors('form');
        $this->travel(61)->minutes();
        $this->post('/contact', $second)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('contact_submissions', 2);
    }

    public function test_storage_failure_rolls_back_both_tables_and_never_queues_or_logs_content(): void
    {
        Log::spy();
        ContactMailDelivery::creating(fn () => throw new RuntimeException('Sensitive storage diagnostic: robin@example.com'));
        $this->post('/contact', $this->fields())->assertSessionHasErrors('form')->assertSessionMissing('contact.received');
        $this->assertDatabaseCount('contact_submissions', 0);
        $this->assertDatabaseCount('contact_mail_deliveries', 0);
        Bus::assertNothingDispatched();
        Log::shouldHaveReceived('error')->once()->with('Contactaanvraag niet opgeslagen.', ['category' => 'submission_store_failed']);
    }

    public function test_enqueue_failure_preserves_intents_and_can_be_dispatched_once_later(): void
    {
        Log::spy();
        Bus::swap($this->app->make(Dispatcher::class));
        Bus::shouldReceive('dispatch')->twice()->andThrow(new RuntimeException('Sensitive transport diagnostic'));
        $this->post('/contact', $this->fields())->assertSessionHas('contact.received', true);
        $this->assertDatabaseCount('contact_submissions', 1);
        $this->assertSame(['pending'], ContactMailDelivery::query()->pluck('status')->unique()->values()->all());
        Bus::fake();
        $this->assertSame(2, app(ContactMailDispatcher::class)->dispatch());
        $this->assertSame(0, app(ContactMailDispatcher::class)->dispatch());
        Bus::assertDispatchedTimes(SendContactMail::class, 2);
    }

    public function test_jobs_are_not_available_before_outer_commit_or_after_rollback(): void
    {
        $data = $this->fields();
        DB::beginTransaction();
        $this->post('/contact', $data)->assertSessionHas('contact.received', true);
        Bus::assertNothingDispatched();
        DB::rollBack();
        $this->assertDatabaseCount('contact_submissions', 0);
        Bus::assertNothingDispatched();
    }

    public function test_expired_csrf_redirects_to_a_retry_without_accepting_the_request(): void
    {
        $data = $this->fields();
        $this->app['env'] = 'local';
        $this->post('/contact', $data)->assertStatus(303)->assertRedirect('/contact')->assertSessionHasErrors('form');
        $this->assertDatabaseCount('contact_submissions', 0);
        Bus::assertNothingDispatched();
    }

    public function test_no_public_read_route_or_success_payload_exposes_saved_data(): void
    {
        $data = $this->fields();
        $this->post('/contact', $data);
        $reference = ContactSubmission::query()->sole()->reference;
        $this->get('/contact/'.$reference)->assertNotFound();
        $this->get('/contact')->assertInertia(fn (Assert $page) => $page->where('received', true)->missing('submission')->missing('reference')->missing('email')->missing('body'));
    }
}
