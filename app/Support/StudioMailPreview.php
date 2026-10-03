<?php

namespace App\Support;

use App\Mail\ContactEnquiryNotification;
use App\Mail\ContactEnquiryReceipt;
use Illuminate\Contracts\View\Factory;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Storage;

class StudioMailPreview
{
    /** @return array{html: string, text: string} */
    public function render(ContactEnquiryNotification|ContactEnquiryReceipt $mail): array
    {
        $content = $mail->content();

        if ($content->view === null || $content->text === null) {
            throw new \LogicException('Beide mailviews zijn vereist voor een preview.');
        }

        $views = app(Factory::class);

        // Render only. Never resolve the configured transport or call send().
        // Laravel converts CID images to data URLs for this local HTML rendering.
        $renderer = new Mailer('studio-preview', $views, new ArrayTransport);

        return [
            'html' => $renderer->render($content->view, $content->with),
            'text' => $views->make($content->text, $content->with)->render(),
        ];
    }

    /** @return list<string> Absolute paths to generated, private files. */
    public function write(): array
    {
        $original = [
            'mail.from.address' => config('mail.from.address'),
            'mail.from.name' => config('mail.from.name'),
            'studio-mail.recipient' => config('studio-mail.recipient'),
        ];
        $files = [];

        try {
            config([
                'mail.from.address' => 'studio@example.com',
                'mail.from.name' => 'Kevin Webstudio',
                'studio-mail.recipient' => 'kevin@example.com',
            ]);

            foreach ([false, true] as $long) {
                $enquiry = StudioMailExamples::enquiry($long);
                $suffix = $long ? '-lang' : '';

                foreach ([
                    'aanvraag' => new ContactEnquiryNotification($enquiry),
                    'ontvangst' => new ContactEnquiryReceipt($enquiry),
                ] as $name => $mail) {
                    foreach ($this->render($mail) as $type => $contents) {
                        $extension = $type === 'text' ? 'txt' : 'html';
                        $files[$name.$suffix.'.'.$extension] = $contents;
                    }
                }
            }
        } finally {
            config($original);
        }

        $files['handtekening.html'] = view('mail.signature')->render();
        $files['handtekening.txt'] = view('mail.text.signature')->render();
        $files['index.html'] = view('mail.preview-index')->render();

        $disk = Storage::disk('local');
        $paths = [];

        foreach ($files as $name => $contents) {
            $path = 'mail-preview/'.$name;

            if (! $disk->put($path, $contents)) {
                throw new \RuntimeException('De private mailvoorbeelden konden niet worden geschreven.');
            }

            $paths[] = $disk->path($path);
        }

        return $paths;
    }
}
