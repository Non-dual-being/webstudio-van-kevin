<?php

namespace App\Console\Commands;

use App\Support\StudioMailPreview;
use Illuminate\Console\Command;
use Throwable;

class StudioMailPreviewCommand extends Command
{
    protected $signature = 'studio:mail-preview';

    protected $description = 'Maak private HTML- en tekstvoorbeelden zonder e-mail te verzenden';

    public function handle(StudioMailPreview $preview): int
    {
        try {
            $paths = $preview->write();
        } catch (Throwable) {
            $this->error('Preview maken mislukt. Controleer de templates en schrijfrechten op de private opslag.');

            return self::FAILURE;
        }

        $this->info('Lokale voorbeelden gemaakt. Niets verzonden; geen aanvraag opgeslagen.');

        foreach ($paths as $path) {
            $this->line($path);
        }

        return self::SUCCESS;
    }
}
