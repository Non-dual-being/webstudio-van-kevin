<?php

declare(strict_types=1);

namespace Demo\Contracts;

interface DisabledDatesRepository
{
    public function isDateDisabled(string $date): bool;

    public function getDisabledDatesDetailed(string $min, string $max): array;
}
