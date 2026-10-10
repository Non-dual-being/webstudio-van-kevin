<?php

declare(strict_types=1);

namespace Demo;

use Demo\Contracts\CalendarRepository;
use Demo\Contracts\Clock;
use Demo\Contracts\DisabledDatesRepository;
use GeoFort\Booking\BookingPolicy;

/** Alleen verzonnen aantallen en redenen in geheugen; geen aanvragen. */
final class FictionalCalendar implements CalendarRepository, DisabledDatesRepository
{
    private array $stats = [];
    private array $disabled = [];

    public function __construct(Clock $clock)
    {
        $day = $clock->today();
        $weekdays = [];
        while (count($weekdays) < 20) {
            $day = $day->modify('+1 day');
            if ((int) $day->format('N') <= 5) {
                $weekdays[] = $day->format('Y-m-d');
            }
        }

        $this->stats[$weekdays[1]] = ['bookedSchools' => 1, 'bookedStudents' => BookingPolicy::MAX_STUDENTS_TOTAL_PER_DAY - 40];
        $this->stats[$weekdays[3]] = ['bookedSchools' => BookingPolicy::MAX_SCHOOLS_PER_DAY, 'bookedStudents' => 80];
        $this->stats[$weekdays[5]] = ['bookedSchools' => 1, 'bookedStudents' => BookingPolicy::MAX_STUDENTS_TOTAL_PER_DAY];
        $this->disabled[$weekdays[4]] = ['datum' => $weekdays[4], 'type' => 'manual', 'reden' => 'Voorbeelddag: gesloten voor onderwijsbezoeken.'];
        foreach (array_slice($weekdays, 10, 3) as $date) {
            $this->disabled[$date] = ['datum' => $date, 'type' => 'school_vacation', 'reden' => 'Fictieve vakantieperiode; geen echte schoolvakantiekalender.'];
        }
    }

    public function getBookingStatsForDate(string $date): array
    {
        return $this->stats[$date] ?? ['bookedSchools' => 0, 'bookedStudents' => 0];
    }

    public function getBookingStatsByDateForRange(string $min, string $max): array
    {
        return array_filter($this->stats, static fn (string $date): bool => $date >= $min && $date <= $max, ARRAY_FILTER_USE_KEY);
    }

    public function isDateDisabled(string $date): bool
    {
        return isset($this->disabled[$date]);
    }

    public function getDisabledDatesDetailed(string $min, string $max): array
    {
        return array_values(array_filter($this->disabled, static fn (array $row): bool => $row['datum'] >= $min && $row['datum'] <= $max));
    }
}
