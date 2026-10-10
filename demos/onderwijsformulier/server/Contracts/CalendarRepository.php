<?php

declare(strict_types=1);

namespace Demo\Contracts;

interface CalendarRepository
{
    /** @return array{bookedSchools: int, bookedStudents: int} */
    public function getBookingStatsForDate(string $date): array;

    public function getBookingStatsByDateForRange(string $min, string $max): array;
}
