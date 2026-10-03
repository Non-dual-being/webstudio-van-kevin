<?php

namespace App\Data;

use InvalidArgumentException;

readonly class ContactEnquiry
{
    public function __construct(
        public string $name,
        public string $email,
        public string $service,
        public string $body,
    ) {
        // Keep an address as an address, never as an arbitrary mail header.
        if (preg_match('/[\x00-\x20\x7f]/', $email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Een geldig e-mailadres zonder stuurtekens is vereist.');
        }
    }

    public function greeting(): string
    {
        $name = trim($this->name);

        // A submitted name must not turn the receipt into a link or spam relay.
        if (mb_strlen($name) > 80 || ! preg_match("/\A\p{L}[\p{L}\p{M} '\x{2019}-]*\z/u", $name)) {
            return 'Hallo,';
        }

        return 'Hallo '.$name.',';
    }
}
