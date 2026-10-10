<?php

declare(strict_types=1);

namespace Demo\Contracts;

interface RosterRepository
{
    public function findRoster(string $schoolType, string $moduleKey, string $programDuration, int $studentCount): ?array;
}
