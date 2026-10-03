<?php

namespace App\Jobs;

use App\Models\ContactMailDelivery;
use App\Support\ContactMailSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendContactMail implements ShouldQueue
{
    use Queueable;

    // Retries belong to the durable delivery, not to an opaque queue payload.
    public int $tries = 1;

    public int $timeout = 60;

    public bool $failOnTimeout = true;

    public function __construct(public readonly int $deliveryId, public readonly string $dispatchToken)
    {
        $this->onConnection('database')->onQueue(config('contact.queue'));
    }

    public function handle(ContactMailSender $sender): void
    {
        try {
            $claimed = ContactMailDelivery::query()->whereKey($this->deliveryId)
                ->where('status', 'queued')->where('dispatch_token', $this->dispatchToken)
                ->whereColumn('attempts', '<', 'attempt_limit')->update([
                    'status' => 'processing', 'started_at' => now(), 'attempts' => DB::raw('attempts + 1'),
                ]);
            if (! $claimed) {
                return;
            }
            $delivery = ContactMailDelivery::query()->findOrFail($this->deliveryId);
            // No database transaction or cache lock is held across SMTP.
            $outcome = $sender->send($delivery);
            $retry = $outcome['retry'] && $delivery->attempts < $delivery->attempt_limit;
            $backoff = config('contact.backoff');
            $delay = $backoff[min($delivery->attempts - 1, count($backoff) - 1)];
            ContactMailDelivery::query()->whereKey($this->deliveryId)
                ->where('status', 'processing')->where('dispatch_token', $this->dispatchToken)->update([
                    'status' => $retry ? 'pending' : $outcome['status'],
                    'error_category' => $outcome['category'],
                    'available_at' => $retry ? now()->addSeconds($delay) : $delivery->available_at,
                    'finished_at' => $retry ? null : now(),
                ]);
            if ($outcome['category']) {
                Log::warning('Contactmail vraagt opvolging.', [
                    'delivery_id' => $delivery->id, 'category' => $outcome['category'],
                ]);
            }
        } catch (Throwable) {
            // A failed final write must not cause a resend after successful SMTP acceptance.
            // A surviving processing claim will be classified as uncertain by the dispatcher.
            Log::error('Contactmail-verwerking onderbroken.', ['delivery_id' => $this->deliveryId, 'category' => 'delivery_store_failed']);
        }
    }

    public function failed(?Throwable $exception): void
    {
        ContactMailDelivery::query()->whereKey($this->deliveryId)->where('status', 'processing')
            ->where('dispatch_token', $this->dispatchToken)->update([
                'status' => 'uncertain', 'error_category' => 'worker_interrupted',
            ]);
    }
}
