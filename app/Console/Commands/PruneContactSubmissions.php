<?php

namespace App\Console\Commands;

use App\Support\ContactRetention;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Throwable;

class PruneContactSubmissions extends Command
{
    protected $signature = 'contact:prune
        {--days= : Alleen simuleren met een andere termijn in dagen}
        {--execute : Verwijder maximaal 500 aanvragen volgens de vastgestelde configuratie}';

    protected $description = 'Rapporteer oude contactopslag zonder persoonsgegevens; standaard uitsluitend dry-run';

    public function handle(ContactRetention $retention): int
    {
        $execute = (bool) $this->option('execute');
        $configured = config('contact.retention_days');
        $override = $this->option('days');
        if ($execute && ($configured === null || $configured === '' || $override !== null)) {
            $this->error('Uitvoeren vereist een vastgestelde CONTACT_RETENTION_DAYS, zonder --days. Niets verwijderd.');

            return self::FAILURE;
        }

        // 90 days is a proposal for inspection only, never an enabled policy.
        $days = $override ?? $configured ?? 90;
        if ((! is_string($days) && ! is_int($days))
            || ! preg_match('/^[1-9][0-9]{0,4}$/D', (string) $days) || (int) $days > 36500) {
            $this->error('De termijn moet een geheel aantal dagen tussen 1 en 36500 zijn. Niets verwijderd.');

            return self::FAILURE;
        }

        $cutoff = CarbonImmutable::now()->subDays((int) $days);
        $this->info($execute ? 'UITVOERING: één transactie, maximaal 500 aanvragen.' : 'DRY-RUN: er wordt niets verwijderd.');
        if ($configured === null || $configured === '') {
            $this->line('Bewaarbeleid niet ingesteld; de getoonde termijn is uitsluitend een voorstel.');
        }
        $this->line('Termijn: '.$days.' dagen vanaf de laatste afgeronde mailtaak.');

        try {
            $report = $retention->inspect($cutoff);
            $this->table(['Categorie', 'Aantal'], [
                ['Aanvragen ouder dan de termijn', $report['old']],
                ['Technisch geschikt voor opruimen', $report['eligible']],
                ['Onopgelost: afzonderlijk beoordelen', $report['unresolved']],
                ['Afgerond, maar afronding nog te recent', $report['retained']],
            ]);
            if ($report['queue_blocked']) {
                $this->warn('Contactqueue of foutwachtrij bevat taken. Uitvoeren geblokkeerd; taken blijven behouden.');

                return $execute ? self::FAILURE : self::SUCCESS;
            }
            if ($execute) {
                $this->info('Verwijderde aanvragen: '.$retention->execute($cutoff));
            }
        } catch (Throwable) {
            // Never echo exception text, SQL bindings, references or request content.
            // The bounded execution transaction rolls back on failure.
            $this->error('Controle of uitvoering afgebroken. Controleer database- en queueconfiguratie en voer opnieuw een dry-run uit. De uitvoeringstransactie is niet vastgelegd.');

            return self::FAILURE;
        }

        $this->line('Deze functie ruimt uitsluitend websiteaanvragen en bijbehorende mailstatussen op; geen mailboxen of back-ups.');

        return self::SUCCESS;
    }
}
