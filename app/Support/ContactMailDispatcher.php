<?php

namespace App\Support;

use App\Jobs\SendContactMail;
use App\Models\ContactMailDelivery;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ContactMailDispatcher
{
    public function dispatch(?int $submissionId = null): int
    {
        $count = 0;
        try {
            $query = ContactMailDelivery::query()->when($submissionId !== null, fn ($query) => $query->where('contact_submission_id', $submissionId));
            $expired = now()->subSeconds((int) config('contact.claim_seconds'));
            // A worker may have died after SMTP accepted the message. Never blindly retry.
            (clone $query)->where('status', 'processing')->where('started_at', '<=', $expired)->update([
                'status' => 'uncertain', 'error_category' => 'worker_interrupted',
            ]);
            (clone $query)->where('status', 'queued')->where('queued_at', '<=', $expired)->update([
                'status' => 'pending', 'dispatch_token' => null,
            ]);

            $ids = (clone $query)->where('status', 'pending')->where('available_at', '<=', now())
                ->whereColumn('attempts', '<', 'attempt_limit')->orderBy('id')->limit(100)->pluck('id');
            foreach ($ids as $id) {
                $token = (string) Str::uuid();
                $claimed = ContactMailDelivery::query()->whereKey($id)->where('status', 'pending')
                    ->where('available_at', '<=', now())->whereColumn('attempts', '<', 'attempt_limit')->update([
                        'status' => 'queued', 'dispatch_token' => $token, 'queued_at' => now(),
                    ]);
                if (! $claimed) {
                    continue;
                }
                try {
                    Bus::dispatch((new SendContactMail($id, $token))->afterCommit());
                    $count++;
                } catch (Throwable) {
                    ContactMailDelivery::query()->whereKey($id)->where('status', 'queued')->where('dispatch_token', $token)->update([
                        'status' => 'pending', 'dispatch_token' => null, 'error_category' => 'enqueue_failed',
                    ]);
                    Log::warning('Contactmail niet ingepland.', ['delivery_id' => $id, 'category' => 'enqueue_failed']);
                }
            }
        } catch (Throwable) {
            // In particular, never turn a committed submission into a public storage error.
            Log::error('Contactmail-dispatch onderbroken.', ['submission_id' => $submissionId, 'category' => 'dispatch_store_failed']);
        }

        return $count;
    }
}
