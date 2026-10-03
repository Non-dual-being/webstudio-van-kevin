<?php

namespace App\Actions;

use App\Models\ContactSubmission;
use App\Support\ContactMailDispatcher;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\Repository;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;

class StoreContactSubmission
{
    public function __construct(private readonly ContactMailDispatcher $dispatcher) {}

    /** @param array{name: string, email: string, service?: ?string, body: string, attempt: string} $data */
    public function store(array $data, string $owner, string $ip): ContactSubmission
    {
        $fields = [
            'name' => $data['name'], 'email' => $data['email'],
            'service' => $data['service'] ?? null, 'body' => $data['body'],
        ];
        $attempt = hash('sha256', $data['attempt']);
        $session = hash('sha256', $owner);
        $payload = hash_hmac('sha256', json_encode($fields, JSON_THROW_ON_ERROR), config('app.key'));

        // A retry of an already saved attempt must also work after the rate limit is reached.
        if ($existing = ContactSubmission::query()->where('attempt_hash', $attempt)->first()) {
            return $this->sameAttempt($existing, $session, $payload);
        }

        $cache = Cache::store(config('contact.cache_store'));
        if (! $cache instanceof Repository || ! $cache->getStore() instanceof DatabaseStore) {
            throw new LogicException('Contactlimieten vereisen een gedeelde databasecache.');
        }
        $locks = $cache->getStore();
        $limiter = new RateLimiter($cache);
        $ipKey = 'contact:ip:'.hash_hmac('sha256', $ip, config('app.key'));
        $emailKey = 'contact:email:'.hash_hmac('sha256', $fields['email'], config('app.key'));

        // Shared database locks serialize quota checks across PHP workers and sessions.
        return $locks->lock($ipKey.':lock', 15)->block(5, fn () => $locks->lock($emailKey.':lock', 15)->block(5, function () use ($fields, $attempt, $session, $payload, $limiter, $ipKey, $emailKey) {
            if ($existing = ContactSubmission::query()->where('attempt_hash', $attempt)->first()) {
                return $this->sameAttempt($existing, $session, $payload);
            }

            foreach ([$ipKey => config('contact.ip_limit'), $emailKey => config('contact.email_limit')] as $key => $limit) {
                if ($limiter->tooManyAttempts($key, max(1, (int) $limit))) {
                    $minutes = max(1, (int) ceil($limiter->availableIn($key) / 60));
                    throw ValidationException::withMessages(['form' => "Er zijn al enkele berichten verstuurd. Je tekst blijft staan. Probeer over {$minutes} minuten opnieuw, of mail me rechtstreeks."]);
                }
            }
            foreach ([$ipKey, $emailKey] as $key) {
                $limiter->hit($key, max(60, (int) config('contact.limit_seconds')));
            }

            try {
                return DB::transaction(function () use ($fields, $attempt, $session, $payload) {
                    $submission = ContactSubmission::query()->create([
                        ...$fields,
                        'reference' => (string) Str::ulid(),
                        'attempt_hash' => $attempt, 'session_hash' => $session, 'payload_hash' => $payload,
                    ]);
                    foreach (['notification', 'receipt'] as $kind) {
                        $submission->deliveries()->create([
                            'kind' => $kind, 'available_at' => now(),
                            'attempt_limit' => max(1, (int) config('contact.max_attempts')),
                        ]);
                    }
                    // The durable intents survive an enqueue failure after this commit.
                    DB::afterCommit(fn () => $this->dispatcher->dispatch($submission->id));

                    return $submission;
                }, 3);
            } catch (UniqueConstraintViolationException $exception) {
                $existing = ContactSubmission::query()->where('attempt_hash', $attempt)->first();
                if (! $existing) {
                    throw $exception;
                }

                return $this->sameAttempt($existing, $session, $payload);
            }
        })
        );
    }

    private function sameAttempt(ContactSubmission $submission, string $session, string $payload): ContactSubmission
    {
        if (! hash_equals($submission->session_hash, $session) || ! hash_equals($submission->payload_hash, $payload)) {
            throw ValidationException::withMessages(['attempt' => 'Deze formulierpoging kan niet worden hergebruikt. Controleer je tekst en verstuur opnieuw met de vernieuwde formulierpoging.']);
        }

        return $submission;
    }
}
