<?php

namespace App\Support;

use App\Mail\ContactEnquiryNotification;
use App\Mail\ContactEnquiryReceipt;
use App\Models\ContactMailDelivery;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;
use Throwable;

class ContactMailSender
{
    /** @return array{status: string, category: ?string, retry: bool} */
    public function send(ContactMailDelivery $delivery): array
    {
        try {
            $mailer = Mail::mailer();
            $transport = $mailer->getSymfonyTransport();
            $testing = $transport instanceof ArrayTransport && app()->environment(['local', 'testing']) && config('contact.allow_test_transport');
            if (! $transport instanceof SmtpTransport && ! $testing) {
                return ['status' => 'failed', 'category' => 'transport_not_allowed', 'retry' => false];
            }
            if ($transport instanceof SmtpTransport) {
                $stream = $transport->getStream();
                if ($stream instanceof SocketStream) {
                    $stream->setTimeout(10);
                }
            }
            $enquiry = $delivery->submission()->firstOrFail()->enquiry();
            $mail = match ($delivery->kind) {
                'notification' => new ContactEnquiryNotification($enquiry),
                'receipt' => new ContactEnquiryReceipt($enquiry),
            };
        } catch (Throwable) {
            return ['status' => 'failed', 'category' => 'preparation_failed', 'retry' => false];
        }

        try {
            $sent = $mailer->send($mail);
            if ($sent === null) {
                return ['status' => 'failed', 'category' => 'send_cancelled', 'retry' => false];
            }

            return ['status' => $testing ? 'intercepted' : 'accepted', 'category' => null, 'retry' => false];
        } catch (TransportExceptionInterface $exception) {
            $code = $exception->getCode();
            if ($code >= 400 && $code < 600) {
                return ['status' => 'failed', 'category' => $code < 500 ? 'smtp_temporary_rejection' : 'smtp_rejected', 'retry' => $code < 500];
            }

            return ['status' => 'uncertain', 'category' => 'smtp_unknown_outcome', 'retry' => false];
        } catch (Throwable) {
            return ['status' => 'uncertain', 'category' => 'send_unknown_outcome', 'retry' => false];
        }
    }
}
