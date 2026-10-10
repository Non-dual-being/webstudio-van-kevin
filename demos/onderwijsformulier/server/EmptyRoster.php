<?php

declare(strict_types=1);

namespace Demo;

use Demo\Contracts\RosterRepository;

final class EmptyRoster implements RosterRepository
{
    public function findRoster(string $schoolType, string $moduleKey, string $programDuration, int $studentCount): ?array
    {
        return null;
    }
}
