<?php

namespace Tests\Feature\Contact;

use App\Actions\StoreContactSubmission;
use App\Jobs\SendContactMail;
use App\Models\ContactMailDelivery;
use App\Models\ContactSubmission;
use App\Support\ContactMailDispatcher;
use App\Support\ContactMailSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Mime\RawMessage;
use Tests\TestCase;

class ContactDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array', 'contact.allow_test_transport' => true, 'contact.cache_store' => 'database',
            'mail.from.address' => 'info@kevinwebstudio.nl', 'studio-mail.recipient' => 'info@kevinwebstudio.nl']);
        Bus::fake();
    }

    private function submission(): ContactSubmission
    {
        return app(StoreContactSubmission::class)->store([
            'name' => 'Robin Voorbeeld', 'email' => 'robin@example.com', 'service' => 'application',
            'body' => 'Fictieve inhoud die niet in de ontvangstbevestiging mag verschijnen.',
            'attempt' => bin2hex(random_bytes(32)),
        ], str_repeat('a', 64), '198.51.100.10');
    }

    private function process(ContactMailDelivery $delivery, ?ContactMailSender $sender = null): void
    {
        (new SendContactMail($delivery->id, $delivery->dispatch_token))->handle($sender ?? app(ContactMailSender::class));
    }

    public function test_both_existing_mailables_use_saved_data_and_duplicates_do_not_send_again(): void
    {
        $submission = $this->submission();
        foreach ($submission->deliveries as $delivery) {
            $this->process($delivery);
            $this->process($delivery);
        }
        $transport = Mail::mailer()->getSymfonyTransport();
        $this->assertInstanceOf(ArrayTransport::class, $transport);
        $messages = $transport->messages();
        $this->assertCount(2, $messages);
        $notification = $messages[0]->getOriginalMessage();
        $receipt = $messages[1]->getOriginalMessage();
        $this->assertSame('info@kevinwebstudio.nl', $notification->getTo()[0]->getAddress());
        $this->assertSame('robin@example.com', $notification->getReplyTo()[0]->getAddress());
        $this->assertStringContainsString('Webapplicaties op maat', $notification->getTextBody());
        $this->assertStringContainsString($submission->body, $notification->getTextBody());
        $this->assertSame('robin@example.com', $receipt->getTo()[0]->getAddress());
        $this->assertSame('info@kevinwebstudio.nl', $receipt->getReplyTo()[0]->getAddress());
        $this->assertStringNotContainsString($submission->body, $receipt->getTextBody());
        $this->assertSame(['intercepted', 'intercepted'], $submission->deliveries()->pluck('status')->all());
        $this->assertSame([1, 1], $submission->deliveries()->pluck('attempts')->all());
    }

    #[DataProvider('disallowedTransports')]
    public function test_log_fallback_and_non_explicit_test_transports_are_not_success(string $mailer, bool $allow, string $environment): void
    {
        config(['mail.default' => $mailer, 'contact.allow_test_transport' => $allow]);
        $this->app['env'] = $environment;
        $delivery = $this->submission()->deliveries()->firstOrFail();
        $this->process($delivery);
        $this->assertSame('failed', $delivery->fresh()->status);
        $this->assertSame('transport_not_allowed', $delivery->fresh()->error_category);
    }

    public static function disallowedTransports(): array
    {
        return [['log', true, 'testing'], ['failover', true, 'testing'], ['array', false, 'testing'], ['array', true, 'production']];
    }

    public function test_smtp_acceptance_is_recorded_separately_from_test_interception(): void
    {
        Mail::mailer()->setSymfonyTransport(new class extends SmtpTransport
        {
            public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
            {
                return new SentMessage($message, $envelope ?? Envelope::create($message));
            }
        });
        $delivery = $this->submission()->deliveries()->firstOrFail();
        $this->process($delivery);
        $this->assertSame('accepted', $delivery->fresh()->status);
    }

    public function test_one_mail_can_retry_without_resending_the_other_and_retries_are_bounded(): void
    {
        $submission = $this->submission();
        $notification = $submission->deliveries()->where('kind', 'notification')->firstOrFail();
        $receipt = $submission->deliveries()->where('kind', 'receipt')->firstOrFail();
        $this->process($notification);
        Mail::mailer()->setSymfonyTransport(new class extends SmtpTransport
        {
            public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
            {
                throw new TransportException('Sensitive provider output must stay private', 451);
            }
        });
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this->process($receipt->fresh());
            $this->assertSame($attempt, $receipt->fresh()->attempts);
            $this->assertSame('intercepted', $notification->fresh()->status);
            $this->assertSame(0, app(ContactMailDispatcher::class)->dispatch());
            $this->travel(6)->minutes();
            $this->assertSame($attempt < 3 ? 1 : 0, app(ContactMailDispatcher::class)->dispatch());
        }
        $this->assertSame('failed', $receipt->fresh()->status);
        $this->artisan('contact:mail', ['reference' => $submission->reference])->assertSuccessful();
        $this->assertSame('failed', $receipt->fresh()->status);
        $this->artisan('contact:mail', ['reference' => $submission->reference, '--retry' => 'receipt'])->assertSuccessful();
        $this->assertSame('queued', $receipt->fresh()->status);
        $this->assertSame(6, $receipt->fresh()->attempt_limit);
        $this->assertSame(0, app(ContactMailDispatcher::class)->dispatch());
        Mail::mailer()->setSymfonyTransport(new ArrayTransport);
        $this->process($receipt->fresh());
        $this->assertSame('intercepted', $receipt->fresh()->status);
        $this->assertSame(1, $notification->fresh()->attempts);
    }

    public function test_unknown_smtp_outcome_requires_explicit_review_and_is_never_automatically_retried(): void
    {
        Mail::mailer()->setSymfonyTransport(new class extends SmtpTransport
        {
            public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
            {
                throw new TransportException('Timeout after DATA, address and provider detail must not be logged');
            }
        });
        $submission = $this->submission();
        $delivery = $submission->deliveries()->firstOrFail();
        $this->process($delivery);
        $this->assertSame('uncertain', $delivery->fresh()->status);
        $this->artisan('contact:mail', ['reference' => $submission->reference, '--retry' => 'notification'])->assertFailed();
        $this->artisan('contact:mail', ['reference' => $submission->reference, '--retry' => 'notification', '--confirm-uncertain' => true])->assertSuccessful();
        $this->assertSame('queued', $delivery->fresh()->status);
    }

    public function test_stale_processing_is_uncertain_and_old_queue_generations_cannot_claim_new_work(): void
    {
        $submission = $this->submission();
        $notification = $submission->deliveries()->where('kind', 'notification')->firstOrFail();
        $receipt = $submission->deliveries()->where('kind', 'receipt')->firstOrFail();
        $notification->update(['status' => 'processing', 'started_at' => now()->subMinutes(6)]);
        $receipt->update(['queued_at' => now()->subMinutes(6)]);
        $this->assertSame(1, app(ContactMailDispatcher::class)->dispatch());
        $this->assertSame('uncertain', $notification->fresh()->status);
        $this->process($receipt); // Old job, before the queued lease was replaced.
        $this->assertSame(0, $receipt->fresh()->attempts);
        $this->process($receipt->fresh());
        $this->assertSame('intercepted', $receipt->fresh()->status);
        $this->artisan('contact:mail', ['reference' => $submission->reference, '--retry' => 'receipt'])->assertFailed();
    }
}
