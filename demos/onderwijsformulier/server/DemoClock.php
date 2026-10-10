<?php

declare(strict_types=1);

namespace Demo;

use DateTimeImmutable;
use DateTimeZone;
use Demo\Contracts\Clock;
use InvalidArgumentException;

final readonly class DemoClock implements Clock
{
    private DateTimeImmutable $date;

    public function __construct(?string $fixedDate = null)
    {
        $timezone = new DateTimeZone('Europe/Amsterdam');
        $date = $fixedDate === null
            ? new DateTimeImmutable('today', $timezone)
            : DateTimeImmutable::createFromFormat('!Y-m-d', $fixedDate, $timezone);

        if (!$date || ($fixedDate !== null && $date->format('Y-m-d') !== $fixedDate)) {
            throw new InvalidArgumentException('Ongeldige demo-datumbron.');
        }

        $this->date = $date;
    }

    public function today(): DateTimeImmutable
    {
        return $this->date;
    }
}
