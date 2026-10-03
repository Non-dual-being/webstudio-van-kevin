<?php

namespace App\Console\Commands;

use App\Mail\ContactEnquiryNotification;
use App\Support\StudioMailExamples;
use Illuminate\Console\Command;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Throwable;

class StudioMailTestCommand extends Command
{
    protected $signature = 'studio:mail-test
        {--to= : info@kevinwebstudio.nl of kevin.webappbeheer@outlook.com}
        {--send : Verzend bewust precies één SMTP-testmail}';

    protected $description = 'Controleer de mailbasis; verzendt uitsluitend met --send en een eigen testontvanger';

    private const RECIPIENTS = ['info@kevinwebstudio.nl', 'kevin.webappbeheer@outlook.com'];

    public function handle(): int
    {
        $recipient = $this->option('to');

        if (($recipient !== null && ! in_array($recipient, self::RECIPIENTS, true))
            || ($this->option('send') && $recipient === null)) {
            $this->error('Kies --to=info@kevinwebstudio.nl of --to=kevin.webappbeheer@outlook.com. Niets verzonden.');

            return self::FAILURE;
        }

        $errors = $this->configurationErrors();

        if (! $this->option('send')) {
            if ($this->call('studio:mail-preview') !== self::SUCCESS) {
                return self::FAILURE;
            }

            foreach ($errors as $error) {
                $this->warn($error);
            }

            $this->info('Alleen controle. SMTP niet benaderd. Verzenden vereist --to=<eigen adres> --send.');

            return self::SUCCESS;
        }

        if ($errors !== []) {
            foreach ($errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        try {
            // Build only the checked SMTP transport, without default/failover or global recipients.
            $smtp = config('mail.mailers.smtp');
            $mailer = app(MailManager::class)->build([
                ...$smtp,
                'timeout' => 15,
                'verify_peer' => true,
                'require_tls' => true,
            ]);

            if (! $mailer->getSymfonyTransport() instanceof EsmtpTransport) {
                $this->error('Geen echt SMTP-transport beschikbaar. Niets verzonden.');

                return self::FAILURE;
            }

            $mail = new ContactEnquiryNotification(StudioMailExamples::enquiry());
            $mail->withSymfonyMessage(function (Email $email) use ($recipient): void {
                $email->to($recipient);
                $email->cc();
                $email->bcc();
                $email->subject('[TEST] Nieuwe contactaanvraag — Kevin Webstudio');
            });

            $sent = $mailer->send($mail);

            if ($sent === null) {
                $this->error('Het testbericht is niet door SMTP geaccepteerd.');

                return self::FAILURE;
            }
        } catch (Throwable) {
            // Provider responses can contain credentials. Do not print or log the exception.
            $this->error('SMTP-test mislukt. Controleer verbinding, TLS, mailboxgegevens en afzender in .env. Er is geen bevestigde SMTP-acceptatie; controleer de mailbox voordat je opnieuw probeert.');

            return self::FAILURE;
        }

        $this->info('Eén testbericht door SMTP geaccepteerd.');
        $this->line('Daadwerkelijke ontvangst en SPF/DKIM/DMARC zijn hiermee nog niet aangetoond.');

        return self::SUCCESS;
    }

    /** @return list<string> Fixed diagnostics only; never include configuration values. */
    private function configurationErrors(): array
    {
        $errors = [];

        if (config('mail.default') !== 'smtp' || config('mail.mailers.smtp.transport') !== 'smtp') {
            $errors[] = 'Stel MAIL_MAILER=smtp in. Log, array en failover gelden niet als een SMTP-test.';
        }

        if (filled(config('mail.mailers.smtp.url'))) {
            $errors[] = 'Maak MAIL_URL leeg voor deze beperkte test en gebruik de losse MAIL_* instellingen uit het runbook.';
        }

        if (config('mail.mailers.smtp.host') !== 'smtp.transip.email'
            || (int) config('mail.mailers.smtp.port') !== 465
            || config('mail.mailers.smtp.scheme') !== 'smtps') {
            $errors[] = 'Gebruik MAIL_HOST=smtp.transip.email, MAIL_PORT=465 en MAIL_SCHEME=smtps volgens het runbook.';
        }

        if (config('mail.mailers.smtp.username') !== 'info@kevinwebstudio.nl'
            || ! is_string(config('mail.mailers.smtp.password'))
            || blank(config('mail.mailers.smtp.password'))) {
            $errors[] = 'Vul MAIL_USERNAME en MAIL_PASSWORD van de zakelijke mailbox zelf in .env in.';
        }

        if (config('mail.from.address') !== 'info@kevinwebstudio.nl'
            || config('mail.from.name') !== 'Kevin Webstudio') {
            $errors[] = 'Gebruik MAIL_FROM_ADDRESS=info@kevinwebstudio.nl en MAIL_FROM_NAME="Kevin Webstudio".';
        }

        return $errors;
    }
}
