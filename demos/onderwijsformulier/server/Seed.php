<?php

declare(strict_types=1);

namespace Demo;

use Demo\Contracts\Clock;
use GeoFort\Booking\BookingPolicy;
use GeoFort\Booking\BookingProgramConfig as Config;
use GeoFort\Validation\EducationSelectionValidator;

/** Reproduceerbare, uitsluitend synthetische aanvragen. Geen globale randomstate. */
final class Seed
{
    public const SEED = 120100;

    public static function weekdays(Clock $clock): array
    {
        $days = [];
        $day = $clock->today();
        while (count($days) < 20) {
            $day = $day->modify('+1 day');
            if ((int) $day->format('N') <= 5) $days[] = $day->format('Y-m-d');
        }
        return $days;
    }

    public static function records(Clock $clock): array
    {
        $state = self::SEED;
        $random = static function (int $max) use (&$state): int {
            $state = ($state * 48271) % 2147483647;
            return $state % $max;
        };
        $days = self::weekdays($clock);
        $records = [];
        $cursor = $clock->today()->modify('+35 days');
        for ($i = 1; $i <= 100; $i++) {
            $sector = array_keys(Config::SCHOOL_TYPES_BY_KEY)[($i - 1) % 3];
            $program = $sector === 'primairOnderwijs' && $i % 2 === 0 ? 'ochtend' : 'dag';
            do { $cursor = $cursor->modify('+1 day'); }
            while (!Config::isProgramAllowedForSelection($program, $sector, (int) $cursor->format('N')));
            $date = $cursor->format('Y-m-d');
            $status = BookingPolicy::ALLOWED_STATUSES[($i - 1) % 3];
            $students = 40 + $random($program === 'ochtend' ? 41 : 81);
            if ($i <= 4) {
                $date = $days[[1, 3, 3, 5][$i - 1]];
                $program = 'dag';
                $students = [120, 40, 40, 160][$i - 1];
                $status = BookingPolicy::STATUS_CONFIRMED;
            } elseif ($i <= 14) {
                // Recente historie: geldig op de toenmalige bezoekdatum.
                $historical = $clock->today()->modify('-'.($i * 3).' days');
                while (!Config::isProgramAllowedForSelection($program, $sector, (int) $historical->format('N'))) $historical = $historical->modify('-1 day');
                $date = $historical->format('Y-m-d');
            }
            $levels = array_keys(Config::SCHOOL_LEVELS[$sector]);
            $selectedLevels = array_slice($levels, 0, $sector === 'primairOnderwijs' ? 1 : 1 + $random(min(3, count($levels))));
            $groups = [];
            foreach ($selectedLevels as $level) {
                $available = array_keys(Config::SCHOOL_LEVELS[$sector][$level]['groups']);
                $groups[$level] = array_slice($available, 0, 1 + $random(min(3, count($available))));
            }
            $selection = json_encode(['sector'=>$sector, 'selectedLevels'=>$selectedLevels, 'selectedGroupsByLevel'=>$groups], JSON_THROW_ON_ERROR);
            $education = (new EducationSelectionValidator)->validate($selection, $sector);
            $modules = array_values(array_filter(Config::getChoiceModulesForSelection($sector, $program), static fn (string $module): bool => Config::isChoiceModuleAllowedForEducationSelection($module, $sector, $education)));
            $number = str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $payload = [
                'schoolnaam'=>'Demoschool '.$number, 'land'=>'Nederland', 'adres'=>'Voorbeeldlaan '.$i, 'postcode'=>'1234 AB', 'plaats'=>'Demostad',
                'schoolTelefoonnummer'=>'020 000 0000', 'contactpersoonTelefoonnummer'=>'06 00000000', 'contactpersoonVoornaam'=>'Demo', 'contactpersoonAchternaam'=>'Voorbeeld', 'email'=>'school'.$number.'@example.invalid',
                'bezoekdatum'=>$date, 'hoeKentUGeoFort'=>'', 'cjpPasGebruik'=>'nee', 'onderwijsSector'=>$sector, 'programma'=>$program,
                'educationSelection'=>$selection, 'keuzemodule'=>$modules === [] ? '' : $modules[$random(count($modules))],
                'aantalLeerlingen'=>(string) $students, 'aantalBegeleiders'=>(string) BookingPolicy::getMinimumSupervisorCount($students),
                'remiseBreak'=>(string) ($i % 4 === 0 ? 10 : 0), 'kazerneBreak'=>(string) ($i % 4 === 1 ? 10 : 0), 'fortgrachtBreak'=>(string) ($i % 4 === 2 ? 10 : 0),
                'waterijsje'=>(string) ($i % 5 === 0 ? $students : 0), 'glasLimonade'=>(string) ($i % 5 === 1 ? $students : 0),
                'lunchChoice'=>$i % 3 === 0 ? 'remise_lunch' : 'eigen_picknick', 'remiseLunch'=>(string) ($i % 3 === 0 ? max($students, Config::FOOD_AND_DRINK_OPTIONS['lunch']['remise_lunch']['min']) : 0),
                'opmerkingen'=>'Fictieve aanvraag om vrij mee te oefenen.', 'voorwaardenAkkoord'=>'1',
            ];
            $records[] = ['id'=>$i, 'status'=>$status, 'payload'=>$payload];
        }
        return $records;
    }
}
