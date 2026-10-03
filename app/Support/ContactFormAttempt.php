<?php

namespace App\Support;

use Illuminate\Contracts\Session\Session;

class ContactFormAttempt
{
    public function issue(Session $session): string
    {
        if (! $session->has('contact.owner')) {
            $session->put('contact.owner', bin2hex(random_bytes(32)));
        }

        $nonce = bin2hex(random_bytes(24));

        return $nonce.'.'.hash_hmac('sha256', $nonce, $session->get('contact.owner'));
    }

    public function valid(Session $session, string $token): bool
    {
        $owner = $session->get('contact.owner');
        if (! is_string($owner) || ! preg_match('/\A[a-f0-9]{48}\.[a-f0-9]{64}\z/', $token)) {
            return false;
        }

        [$nonce, $signature] = explode('.', $token);

        return hash_equals(hash_hmac('sha256', $nonce, $owner), $signature);
    }
}
