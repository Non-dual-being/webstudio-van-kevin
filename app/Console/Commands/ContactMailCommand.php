<?php

namespace App\Console\Commands;

use App\Models\ContactMailDelivery;
use App\Models\ContactSubmission;
use App\Support\ContactMailDispatcher;
use Illuminate\Console\Command;

class ContactMailCommand extends Command
{
    protected $signature = 'contact:mail {reference? : Interne aanvraagreferentie}
        {--show : Toon de aanvraaginhoud uitsluitend bij een expliciete referentie}
        {--dispatch : Plan wachtende/vergeten verzendintenties in}
        {--retry= : Herstel alleen notification of receipt bij deze referentie}
        {--confirm-uncertain : Bevestig dat een onzekere verzending buiten de applicatie is gecontroleerd}';

    protected $description = 'Bekijk contactaanvragen en mailstatussen; wijzigingen vereisen een expliciete optie';

    public function handle(ContactMailDispatcher $dispatcher): int
    {
        $reference = $this->argument('reference');
        $submission = is_string($reference) ? ContactSubmission::query()->where('reference', $reference)->first() : null;
        if ($reference && ! $submission) {
            $this->error('Referentie niet gevonden.');

            return self::FAILURE;
        }
        if (($this->option('show') || $this->option('retry')) && ! $submission) {
            $this->error('Geef hiervoor één aanvraagreferentie op.');

            return self::FAILURE;
        }

        if ($kind = $this->option('retry')) {
            if (! in_array($kind, ['notification', 'receipt'], true)) {
                $this->error('Kies --retry=notification of --retry=receipt.');

                return self::FAILURE;
            }
            $delivery = $submission?->deliveries()->where('kind', $kind)->first();
            if (! $delivery || ! in_array($delivery->status, ['failed', 'uncertain'], true)) {
                $this->error('Alleen failed of uncertain kan gericht worden hersteld. Geaccepteerde/onderschepte mail wordt nooit opnieuw ingepland.');

                return self::FAILURE;
            }
            if ($delivery->status === 'uncertain' && ! $this->option('confirm-uncertain')) {
                $this->error('Controleer eerst mailbox en provider: de mail kan al geaccepteerd zijn. Gebruik pas daarna --confirm-uncertain.');

                return self::FAILURE;
            }
            $updated = ContactMailDelivery::query()->whereKey($delivery->id)->where('status', $delivery->status)
                ->where('attempts', $delivery->attempts)->update([
                    'status' => 'pending', 'available_at' => now(), 'finished_at' => null,
                    'dispatch_token' => null, 'error_category' => null,
                    'attempt_limit' => $delivery->attempts + max(1, (int) config('contact.max_attempts')),
                ]);
            if (! $updated) {
                $this->error('De status is ondertussen gewijzigd. Bekijk de aanvraag opnieuw.');

                return self::FAILURE;
            }
            $this->info('Herstel vastgelegd; '.$dispatcher->dispatch($submission->id).' taak/taken ingepland.');
        } elseif ($this->option('dispatch')) {
            $this->info($dispatcher->dispatch($submission?->id).' taak/taken ingepland.');
        }

        $submissions = ContactSubmission::query()->when($submission, fn ($query) => $query->whereKey($submission->id))
            ->with('deliveries')->latest('id')->limit(20)->get();
        $rows = [];
        foreach ($submissions as $item) {
            foreach ($item->deliveries as $delivery) {
                $rows[] = [$item->reference, $item->created_at?->format('Y-m-d H:i'), $delivery->kind,
                    $delivery->status, $delivery->attempts.'/'.$delivery->attempt_limit,
                    $delivery->error_category ?? '—'];
            }
        }
        $this->table(['Referentie', 'Ontvangen', 'Mail', 'Status', 'Pogingen', 'Categorie'], $rows);
        if ($submission && $this->option('show')) {
            $enquiry = $submission->enquiry();
            // Treat CLI content as literal data, including terminal control characters.
            $this->line(json_encode([
                'reference' => $submission->reference, 'name' => $enquiry->name,
                'email' => $enquiry->email, 'service' => $enquiry->service, 'body' => $enquiry->body,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        }
        $this->line('accepted = SMTP-acceptatie, geen bewijs van bezorging; intercepted = uitsluitend testtransport.');

        return self::SUCCESS;
    }
}
