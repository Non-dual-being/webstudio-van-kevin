<?php

namespace Tests\Feature\Mail;

use App\Data\ContactEnquiry;
use App\Mail\ContactEnquiryNotification;
use App\Mail\ContactEnquiryReceipt;
use App\Support\StudioMailExamples;
use Illuminate\Contracts\View\Factory;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\MailManager;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Tests\TestCase;

class StudioMailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'mail.default' => 'array',
            'mail.from.address' => 'info@kevinwebstudio.nl',
            'mail.from.name' => 'Kevin Webstudio',
            'studio-mail.recipient' => 'info@kevinwebstudio.nl',
            'studio-mail.receipt_from_name' => 'Kevin | Kevin Webstudio',
        ]);
    }

    public function test_notification_has_correct_headers_multipart_content_and_embedded_original_logo(): void
    {
        $email = $this->capture(new ContactEnquiryNotification(StudioMailExamples::enquiry()));

        $this->assertSame('info@kevinwebstudio.nl', $email->getFrom()[0]->getAddress());
        $this->assertSame('Kevin Webstudio', $email->getFrom()[0]->getName());
        $this->assertSame('info@kevinwebstudio.nl', $email->getTo()[0]->getAddress());
        $this->assertSame('robin.voorbeeld@example.com', $email->getReplyTo()[0]->getAddress());
        $this->assertSame('', $email->getReplyTo()[0]->getName());
        $this->assertSame('Nieuwe contactaanvraag — Kevin Webstudio', $email->getSubject());
        $this->assertStringContainsString('Robin Voorbeeld', $email->getHtmlBody());
        $this->assertStringContainsString('Website op maat', $email->getTextBody());
        $this->assertStringContainsString("bekijken.\n\nIk zoek", $email->getTextBody());
        $this->assertStringContainsString('multipart/alternative', $email->toString());
        $this->assertStringContainsString('text/plain; charset=utf-8', $email->toString());
        $this->assertStringContainsString('text/html; charset=utf-8', $email->toString());
        $this->assertCount(1, $email->getAttachments());
        $logo = $email->getAttachments()[0];
        $this->assertSame('image', $logo->getMediaType());
        $this->assertSame('png', $logo->getMediaSubtype());
        $this->assertSame('inline', $logo->getDisposition());
        $this->assertSame(file_get_contents(public_path('brand/kevin-webstudio-kds-mark.png')), $logo->getBody());
        $this->assertStringContainsString('cid:', $email->getHtmlBody());
        $this->assertStringNotContainsString('data:image', $email->getHtmlBody());
        $this->assertStringNotContainsString('cid:', $email->getTextBody());
    }

    public function test_receipt_has_correct_headers_and_readable_html_and_text(): void
    {
        $email = $this->capture(new ContactEnquiryReceipt(StudioMailExamples::enquiry()));

        $this->assertSame('info@kevinwebstudio.nl', $email->getFrom()[0]->getAddress());
        $this->assertSame('Kevin | Kevin Webstudio', $email->getFrom()[0]->getName());
        $this->assertSame('robin.voorbeeld@example.com', $email->getTo()[0]->getAddress());
        $this->assertSame('info@kevinwebstudio.nl', $email->getReplyTo()[0]->getAddress());
        $this->assertSame('Je bericht aan Kevin Webstudio is ontvangen', $email->getSubject());

        foreach ([$email->getHtmlBody(), $email->getTextBody()] as $body) {
            $this->assertStringContainsString('Hallo Robin Voorbeeld,', $body);
            $this->assertStringContainsString('Ik bekijk je vraag', $body);
            $this->assertStringContainsString('info@kevinwebstudio.nl', $body);
            $this->assertStringNotContainsString('Dit is een fictieve aanvraag', $body);
            $this->assertStringNotContainsString('Website op maat', $body);
        }

        $this->assertStringContainsString('multipart/alternative', $email->toString());
        $this->assertCount(1, $email->getAttachments());
    }

    public function test_business_sender_and_normal_notification_recipient_are_centrally_configurable(): void
    {
        config([
            'mail.from.address' => 'studio@example.com',
            'mail.from.name' => 'Voorbeeldstudio',
            'studio-mail.recipient' => 'kevin@example.com',
            'studio-mail.receipt_from_name' => 'Kevin | Voorbeeldstudio',
        ]);

        $notification = $this->capture(new ContactEnquiryNotification(StudioMailExamples::enquiry()));
        $receipt = $this->capture(new ContactEnquiryReceipt(StudioMailExamples::enquiry()));

        $this->assertSame('kevin@example.com', $notification->getTo()[0]->getAddress());
        $this->assertSame('Voorbeeldstudio', $notification->getFrom()[0]->getName());
        $this->assertSame('studio@example.com', $receipt->getFrom()[0]->getAddress());
        $this->assertSame('Kevin | Voorbeeldstudio', $receipt->getFrom()[0]->getName());
        $this->assertSame('studio@example.com', $receipt->getReplyTo()[0]->getAddress());
    }

    public function test_html_like_input_special_characters_and_long_content_stay_in_the_body(): void
    {
        $body = "<script>alert('voorbeeld')</script>\n\nCafé & ideeën: €15 < €20.\n".str_repeat('langwoord', 500)."\nEINDE";
        $enquiry = new ContactEnquiry(
            "Robin <img src=x onerror=alert(1)>\r\nBcc: injected@example.com",
            'robin+vragen@example.com',
            '<a href="https://spam.example.com">nepdienst</a>',
            $body,
        );
        $email = $this->capture(new ContactEnquiryNotification($enquiry));

        $this->assertStringNotContainsString('<script>', $email->getHtmlBody());
        $this->assertStringNotContainsString('<img src=x', $email->getHtmlBody());
        $this->assertStringNotContainsString('<a href="https://spam.example.com"', $email->getHtmlBody());
        $this->assertStringContainsString('&lt;script&gt;', $email->getHtmlBody());
        $this->assertStringContainsString('Café &amp; ideeën: €15 &lt; €20.', $email->getHtmlBody());
        $this->assertStringContainsString('<br />', $email->getHtmlBody());
        $this->assertStringContainsString('overflow-wrap: anywhere', $email->getHtmlBody());
        $this->assertStringContainsString('EINDE', $email->getHtmlBody());
        $this->assertStringContainsString($body, $email->getTextBody());
        $this->assertStringNotContainsString('injected', $email->getHeaders()->toString());
        $this->assertEmpty($email->getBcc());
        $this->assertEmpty($email->getCc());
        $this->assertSame('Nieuwe contactaanvraag — Kevin Webstudio', $email->getSubject());
    }

    #[DataProvider('invalidAddresses')]
    public function test_invalid_or_header_injecting_addresses_are_rejected(string $address): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Een geldig e-mailadres zonder stuurtekens is vereist.');

        new ContactEnquiry('Voorbeeld', $address, 'Website', 'Voorbeeld');
    }

    public static function invalidAddresses(): array
    {
        return [
            ['a@example.com'."\r\n".'Bcc: injected@example.com'],
            ['a@example.com'."\n"],
            ["a\0@example.com"],
            ['a@example.com,b@example.com'],
            ['Voorbeeld <a@example.com>'],
            ['niet-een-adres'],
        ];
    }

    public function test_mailto_encodes_percent_plus_and_query_characters_without_adding_headers(): void
    {
        $address = 'robin+%0d%0a?bcc=injected&x@example.com';
        $email = $this->capture(new ContactEnquiryNotification(new ContactEnquiry('Robin', $address, 'Website', 'Voorbeeld')));
        $html = $email->getHtmlBody();

        $this->assertStringContainsString('href="mailto:'.rawurlencode($address).'"', $html);
        $this->assertStringNotContainsString('mailto:robin+%0d%0a?', $html);
        $this->assertSame($address, $email->getReplyTo()[0]->getAddress());
        $this->assertEmpty($email->getBcc());
    }

    #[DataProvider('receiptNames')]
    public function test_receipt_uses_only_a_safe_greeting_and_never_relays_message_service_or_links(string $name, string $greeting): void
    {
        $enquiry = new ContactEnquiry($name, 'robin@example.com', 'https://service.example.com', 'SPAM-MARKER https://spam.example.com <img src="https://tracker.example.com">');
        $mail = new ContactEnquiryReceipt($enquiry);
        $email = $this->capture($mail);

        foreach ([$email->getHtmlBody(), $email->getTextBody()] as $body) {
            $this->assertStringContainsString($greeting, $body);
            $this->assertStringNotContainsString('SPAM-MARKER', $body);
            $this->assertStringNotContainsString('https://', $body);
            $this->assertStringNotContainsString('service.example.com', $body);
            $this->assertStringNotContainsString('tracker.example.com', $body);
        }

        $this->assertArrayNotHasKey('enquiry', $mail->content()->with);
        $this->assertArrayNotHasKey('message', $mail->content()->with);
        $this->assertEmpty($email->getCc());
        $this->assertEmpty($email->getBcc());
    }

    public static function receiptNames(): array
    {
        return [
            ['Zoë van Dijk', 'Hallo Zoë van Dijk,'],
            ['Renée O’Neill', 'Hallo Renée O’Neill,'],
            ['', 'Hallo,'],
            ['https://spam.example.com', 'Hallo,'],
            ['www.spam.example.com', 'Hallo,'],
            ['<b>Koop nu</b>', 'Hallo,'],
            ["Robin\r\nBcc: spam@example.com", 'Hallo,'],
            [str_repeat('Naam', 30), 'Hallo,'],
        ];
    }

    public function test_preview_generates_real_templates_with_inline_browser_logo_without_mail_or_database_queries(): void
    {
        Storage::fake('local');
        Mail::fake();
        $queries = [];
        DB::listen(function ($query) use (&$queries): void {
            $queries[] = $query->sql;
        });
        // Even unusable real SMTP configuration must not block rendering.
        config(['mail.default' => 'smtp', 'mail.mailers.smtp' => ['transport' => 'smtp', 'url' => 'malformed-secret-url']]);

        $this->artisan('studio:mail-preview')->assertSuccessful();

        $disk = Storage::disk('local');
        $this->assertCount(11, $disk->allFiles('mail-preview'));
        $html = $disk->get('mail-preview/aanvraag.html');
        $this->assertStringContainsString('data:image/png;base64,', $html);
        $this->assertStringNotContainsString('cid:', $html);
        $this->assertStringContainsString('robin.voorbeeld@example.com', $html);
        $this->assertStringContainsString('studio@example.com', $html);
        $this->assertStringNotContainsString('info@kevinwebstudio.nl', $html);
        $this->assertSame('info@kevinwebstudio.nl', config('mail.from.address'));
        $this->assertSame(
            view('mail.signature')->render(),
            $disk->get('mail-preview/handtekening.html'),
        );
        $this->assertStringNotContainsString('<img', $disk->get('mail-preview/handtekening.html'));
        $this->assertStringContainsString('Kevin de Schepper', $disk->get('mail-preview/handtekening.txt'));
        $this->assertStringContainsString('voorbeeld', $disk->get('mail-preview/aanvraag-lang.txt'));
        $this->assertSame([], $queries);
        Mail::assertNothingOutgoing();
    }

    public function test_default_test_command_is_render_only_even_with_complete_smtp_settings(): void
    {
        Storage::fake('local');
        $this->configureSmtp();
        Mail::fake();

        $manager = Mockery::mock(MailManager::class);
        $manager->shouldNotReceive('build');
        $this->app->instance(MailManager::class, $manager);

        $this->artisan('studio:mail-test', ['--to' => 'kevin.webappbeheer@outlook.com'])
            ->expectsOutputToContain('SMTP niet benaderd')
            ->assertSuccessful();

        Mail::assertNothingOutgoing();
        Storage::disk('local')->assertExists('mail-preview/index.html');
    }

    #[DataProvider('disallowedRecipients')]
    public function test_test_command_refuses_unapproved_recipients_or_missing_explicit_recipient(?string $recipient): void
    {
        Mail::fake();
        $options = ['--send' => true];

        if ($recipient !== null) {
            $options['--to'] = $recipient;
        }

        $this->artisan('studio:mail-test', $options)
            ->expectsOutputToContain('Kies --to=')
            ->assertFailed();

        Mail::assertNothingOutgoing();
    }

    public static function disallowedRecipients(): array
    {
        return [[null], ['outsider@example.com'], ['info@kevinwebstudio.nl,outsider@example.com'], ["info@kevinwebstudio.nl\r\nBcc: outsider@example.com"]];
    }

    #[DataProvider('badSmtpSettings')]
    public function test_explicit_send_refuses_missing_or_ambiguous_configuration_without_showing_secrets(string $key, mixed $value): void
    {
        $this->configureSmtp();
        config([$key => $value]);
        $manager = Mockery::mock(MailManager::class);
        $manager->shouldNotReceive('build');
        $this->app->instance(MailManager::class, $manager);

        $this->artisan('studio:mail-test', ['--to' => 'info@kevinwebstudio.nl', '--send' => true])
            ->doesntExpectOutputToContain('DO-NOT-PRINT')
            ->assertFailed();
    }

    public static function badSmtpSettings(): array
    {
        return [
            ['mail.default', 'log'],
            ['mail.default', 'failover'],
            ['mail.mailers.smtp.transport', 'log'],
            ['mail.mailers.smtp.host', null],
            ['mail.mailers.smtp.port', null],
            ['mail.mailers.smtp.scheme', null],
            ['mail.mailers.smtp.scheme', 'smtp'],
            ['mail.mailers.smtp.username', null],
            ['mail.mailers.smtp.password', null],
            ['mail.mailers.smtp.password', ''],
            ['mail.mailers.smtp.url', 'smtps://user:DO-NOT-PRINT@smtp.transip.email:465?verify_peer=0'],
            ['mail.from.address', 'visitor@example.com'],
        ];
    }

    public function test_explicit_send_refuses_a_non_smtp_transport(): void
    {
        $this->configureSmtp();
        $manager = Mockery::mock(MailManager::class);
        $manager->shouldReceive('build')->once()->andReturn(new Mailer('array', app(Factory::class), new ArrayTransport));
        $this->app->instance(MailManager::class, $manager);

        $this->artisan('studio:mail-test', ['--to' => 'info@kevinwebstudio.nl', '--send' => true])
            ->expectsOutputToContain('Geen echt SMTP-transport')
            ->assertFailed();
    }

    public function test_transport_failure_returns_failure_without_printing_or_logging_the_exception(): void
    {
        $this->configureSmtp();
        $transport = Mockery::mock(EsmtpTransport::class);
        $transport->shouldReceive('send')->once()->andThrow(new TransportException('DO-NOT-PRINT smtp://user:DO-NOT-PRINT@example.com'));
        $this->useTestTransport($transport);

        $this->artisan('studio:mail-test', ['--to' => 'info@kevinwebstudio.nl', '--send' => true])
            ->expectsOutputToContain('SMTP-test mislukt')
            ->doesntExpectOutputToContain('DO-NOT-PRINT')
            ->assertFailed();
    }

    #[DataProvider('ownRecipients')]
    public function test_explicit_send_uses_exactly_one_chosen_recipient_and_test_subject_with_a_fake_smtp_transport(string $recipient): void
    {
        $this->configureSmtp();
        // A configured normal recipient/global redirect must not widen a CLI test.
        config(['studio-mail.recipient' => 'normal@example.com', 'mail.to.address' => 'redirect@example.com']);
        $captured = [];
        $transport = Mockery::mock(EsmtpTransport::class);
        $transport->shouldReceive('send')->once()->andReturnUsing(function (RawMessage $email, ?Envelope $envelope = null) use (&$captured): SentMessage {
            $captured[] = $email;

            return new SentMessage($email, $envelope ?? Envelope::create($email));
        });
        $this->useTestTransport($transport);

        $this->artisan('studio:mail-test', ['--to' => $recipient, '--send' => true])
            ->expectsOutputToContain('Eén testbericht door SMTP geaccepteerd')
            ->expectsOutputToContain('Daadwerkelijke ontvangst')
            ->assertSuccessful();

        $this->assertCount(1, $captured);
        $email = $captured[0];
        $this->assertInstanceOf(Email::class, $email);
        $this->assertCount(1, $email->getTo());
        $this->assertSame($recipient, $email->getTo()[0]->getAddress());
        $this->assertSame('[TEST] Nieuwe contactaanvraag — Kevin Webstudio', $email->getSubject());
        $this->assertSame('robin.voorbeeld@example.com', $email->getReplyTo()[0]->getAddress());
        $this->assertSame('info@kevinwebstudio.nl', $email->getFrom()[0]->getAddress());
        $this->assertEmpty($email->getCc());
        $this->assertEmpty($email->getBcc());
    }

    public static function ownRecipients(): array
    {
        return [['info@kevinwebstudio.nl'], ['kevin.webappbeheer@outlook.com']];
    }

    private function capture(ContactEnquiryNotification|ContactEnquiryReceipt $mail): Email
    {
        $sent = Mail::mailer('array')->send($mail);
        $this->assertNotNull($sent);
        $email = $sent->getOriginalMessage();
        $this->assertInstanceOf(Email::class, $email);

        return $email;
    }

    private function configureSmtp(): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp' => [
                'transport' => 'smtp',
                'url' => null,
                'scheme' => 'smtps',
                'host' => 'smtp.transip.email',
                'port' => 465,
                'username' => 'info@kevinwebstudio.nl',
                'password' => 'DO-NOT-PRINT',
            ],
        ]);
    }

    private function useTestTransport(EsmtpTransport $transport): void
    {
        $manager = Mockery::mock(MailManager::class);
        $manager->shouldReceive('build')->once()->withArgs(function (array $config): bool {
            return $config['transport'] === 'smtp'
                && $config['verify_peer'] === true
                && $config['require_tls'] === true;
        })->andReturn(new Mailer('smtp-test', app(Factory::class), $transport, app('events')));
        $this->app->instance(MailManager::class, $manager);
    }
}
