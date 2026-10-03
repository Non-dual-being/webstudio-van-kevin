<?php

namespace App\Mail;

use App\Data\ContactEnquiry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactEnquiryReceipt extends Mailable
{
    protected readonly string $recipient;

    protected readonly string $greeting;

    public function __construct(ContactEnquiry $enquiry)
    {
        // Retain no message, service, links or attachments from the enquiry.
        $this->recipient = $enquiry->email;
        $this->greeting = $enquiry->greeting();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('studio-mail.receipt_from_name')),
            to: [new Address($this->recipient)],
            replyTo: [new Address(config('mail.from.address'))],
            subject: 'Je bericht aan Kevin Webstudio is ontvangen',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-receipt',
            text: 'mail.text.contact-receipt',
            with: [
                'greeting' => $this->greeting,
                'contactAddress' => config('mail.from.address'),
            ],
        );
    }
}
