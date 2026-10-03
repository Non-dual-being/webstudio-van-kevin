<?php

namespace App\Mail;

use App\Data\ContactEnquiry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactEnquiryNotification extends Mailable
{
    public function __construct(protected readonly ContactEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: [new Address(config('studio-mail.recipient'))],
            replyTo: [new Address($this->enquiry->email)],
            subject: 'Nieuwe contactaanvraag — Kevin Webstudio',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-enquiry',
            text: 'mail.text.contact-enquiry',
            with: [
                'enquiry' => $this->enquiry,
                'contactAddress' => config('mail.from.address'),
            ],
        );
    }
}
