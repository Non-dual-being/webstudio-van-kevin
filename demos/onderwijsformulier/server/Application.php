<?php

declare(strict_types=1);

namespace Demo;

use Demo\Contracts\Clock;
use GeoFort\Booking\BookingPolicy;
use GeoFort\Booking\BookingProgramConfig;
use GeoFort\Services\Booking\Availability\BookingAvailabilityService;
use GeoFort\Services\Booking\Pricing\BookingPriceCalculator;
use GeoFort\Services\Booking\Pricing\BookingPriceCatalog;
use GeoFort\Services\Booking\Pricing\BookingPriceCatalogRegistry;
use GeoFort\Services\Booking\Roster\BookingRosterResolver;
use GeoFort\Services\Booking\Roster\RosterGroupCountResolver;
use GeoFort\Validation\ChoiceModuleSelectionValidator;
use GeoFort\Validation\EducationSelectionValidator;
use GeoFort\Validation\FoodAndDrinkSelectionValidator;
use GeoFort\Validation\FormRules;
use GeoFort\Validation\ProgramSelectionValidator;
use GeoFort\Validation\StudentCountValidator;
use GeoFort\Validation\SupervisorCountValidator;
use GeoFort\Validation\Validator;

final class Application
{
    public readonly FictionalCalendar $calendar;
    public readonly BookingAvailabilityService $availability;
    public readonly BookingPriceCatalogRegistry $catalogs;
    public readonly BookingPriceCalculator $calculator;

    public function __construct(public readonly Clock $clock, ?array $records = null)
    {
        $this->calendar = new FictionalCalendar($clock, $records);
        $this->availability = new BookingAvailabilityService($this->calendar, $this->calendar, $clock);
        $data = json_decode(file_get_contents(__DIR__.'/catalog.json'), true, 32, JSON_THROW_ON_ERROR);
        $priceTypes = array_map(static fn (array $sector): string => $sector['priceType'], BookingProgramConfig::SCHOOL_TYPES_BY_KEY);
        $catalog = new BookingPriceCatalog($data['version'], $data['currency'], $data['vatBasisPoints'], BookingPriceCatalog::VAT_MEANING, BookingPriceCatalog::VAT_ROUNDING_HALF_UP_TOTAL_CENTS, BookingPriceCatalog::CALCULATION_PROFILE_PER_ITEM_V1, BookingPolicy::SUPERVISOR_STUDENTS_PER_FREE_SUPERVISOR, $priceTypes, $data['visitPricesCents'], $data['cateringPricesCents']);
        $this->catalogs = new BookingPriceCatalogRegistry([$catalog], $catalog->version);
        BookingProgramConfig::useCatalogs($this->catalogs);
        $this->calculator = new BookingPriceCalculator($this->catalogs);
    }

    /** Contract van DisabledDatesAction, met geïnjecteerde klok en fictieve repositories. */
    public function dates(): array
    {
        $today = $this->clock->today();
        $future = $today->modify('+'.BookingPolicy::BOOKABLE_YEARS_AHEAD.' years');
        $max = $future->setDate((int) $future->format('Y'), 12, 31);
        $details = $this->calendar->getDisabledDatesDetailed($today->format('Y-m-d'), $max->format('Y-m-d'));

        return [
            'minDate' => $today->format('Y-m-d'),
            'maxDate' => $max->format('Y-m-d'),
            'disabledDates' => array_column($details, 'datum'),
            'details' => $details,
            'capacity' => $this->availability->getCapacityForFrontend(),
            'availabilityDetails' => $this->availability->getAvailabilityDetailsForRange($today, $max),
        ];
    }

    /** Coördinatie van de hergebruikte validators, geen tweede set bedrijfsregels. */
    private function visit(array $payload): array
    {
        $validator = new Validator;
        $sector = $validator->schoolSector('onderwijsSector', $payload['onderwijsSector'] ?? '', FormRules::RULES);
        $date = $this->availability->assertDateIsValid($validator->date('bezoekdatum', $payload['bezoekdatum'] ?? ''));
        $program = (new ProgramSelectionValidator)->validate($payload['programma'] ?? '', $sector, $date);
        $students = (new StudentCountValidator)->validate($payload['aantalLeerlingen'] ?? '', $sector, $program);
        $supervisors = (new SupervisorCountValidator)->validate($payload['aantalBegeleiders'] ?? '', $students);
        $this->availability->assertCapacityAvailable($date, $students, $program);

        return [$sector, $program, $students, $supervisors];
    }

    public function quote(array $payload): array
    {
        [$sector, $program, $students, $supervisors] = $this->visit($payload);
        $food = (new FoodAndDrinkSelectionValidator)->validate($payload);

        return $this->calculator->calculate($sector, $program, $students, $supervisors, $food)->toArray();
    }

    public function roster(array $payload): array
    {
        [$sector, $program, $students, $supervisors] = $this->visit($payload);
        $education = (new EducationSelectionValidator)->validate($payload['educationSelection'] ?? '', $sector);
        $module = (new ChoiceModuleSelectionValidator)->validate($payload['keuzemodule'] ?? null, $sector, $program, $education);

        return (new BookingRosterResolver(new EmptyRoster, new RosterGroupCountResolver))
            ->resolve($sector, $program, $education, $module, $students, $supervisors)->toArray();
    }

    public function complete(array $payload): array
    {
        (new ValidateSubmission($this->availability))->validate($payload);

        return ['ok' => true, 'mode' => 'demo', 'mailDelivery' => 'simulated'];
    }
}
