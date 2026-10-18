<?php

declare(strict_types=1);

namespace Demo;

use GeoFort\Booking\BookingPolicy as Policy;
use GeoFort\Booking\BookingProgramConfig as Config;
use GeoFort\Booking\Capacity\{BookingCapacityValidator, DayCapacityTotals, EffectiveDayCapacity};
use GeoFort\Booking\Status\BookingStatusTransitionPolicy;
use GeoFort\Booking\Stored\{StoredBooking, BookingSourceMetadata};
use GeoFort\Booking\Validation\{BookingValidationCoordinator, BookingValidationContext, BookingValidationProfile, StoredBookingValidator};
use GeoFort\Booking\SchoolContact\BookingSchoolContactDetails;
use GeoFort\Services\Booking\SchoolContact\BookingSchoolContactValidator;
use GeoFort\Validation\{EducationSelectionValidator, FoodAndDrinkSelectionValidator, FieldValidationException, Validator, FormRules};

/** Stateless demo-adapter. Geen auth-, SQL-, mail-, export- of plannerverbinding. */
final readonly class Dashboard
{
    public const MAX_RECORDS = 120;
    public const MAX_STUDENTS = 1000; // Technische demogrens, geen programmaregel.
    public const EDIT_FIELDS = ['schoolnaam','land','adres','postcode','plaats','schoolTelefoonnummer','contactpersoonTelefoonnummer','contactpersoonVoornaam','contactpersoonAchternaam','email','aantalLeerlingen','aantalBegeleiders','remiseBreak','kazerneBreak','fortgrachtBreak','waterijsje','glasLimonade','lunchChoice','remiseLunch','opmerkingen'];

    public function __construct(private Application $app) {}

    public static function fail(string $field, string $message): never
    {
        throw new FieldValidationException($field, $message);
    }

    /** Alleen capaciteitssamenvattingen gaan bij formulierrequests mee. */
    public static function context(mixed $json): ?array
    {
        if ($json === null) return null;
        if (!is_string($json)) self::fail('demo', 'Ongeldige demonstratieomgeving.');
        $rows = json_decode($json, true, 12, JSON_THROW_ON_ERROR);
        if (!is_array($rows) || !array_is_list($rows) || count($rows) > self::MAX_RECORDS) self::fail('demo', 'Er passen maximaal 120 aanvragen in de demo. Start opnieuw.');
        $records = []; $ids = [];
        foreach ($rows as $row) {
            if (!is_array($row) || !is_int($row['id'] ?? null) || $row['id'] < 1 || $row['id'] > self::MAX_RECORDS || isset($ids[$row['id']]) || !is_string($row['status'] ?? null) || !Policy::isAllowedStatus($row['status']) || !is_int($row['students'] ?? null) || $row['students'] < 1 || $row['students'] > self::MAX_STUDENTS || !is_string($row['date'] ?? null)) self::fail('demo', 'Ongeldige demonstratiegegevens.');
            (new Validator)->date('bezoekdatum', $row['date']);
            $ids[$row['id']] = true;
            $records[] = ['id'=>$row['id'], 'status'=>$row['status'], 'payload'=>['bezoekdatum'=>$row['date'], 'aantalLeerlingen'=>(string) $row['students']]];
        }
        return $records;
    }

    public function seed(): array
    {
        return array_map($this->decorate(...), Seed::records($this->app->clock));
    }

    public function decorate(array $record, array $warnings = []): array
    {
        $b = $this->stored($record);
        foreach (['aantalLeerlingen','aantalBegeleiders','remiseBreak','kazerneBreak','fortgrachtBreak','waterijsje','glasLimonade','remiseLunch'] as $field) {
            $record['payload'][$field] = (string) (int) $record['payload'][$field];
        }
        if ($b->foodAndDrinkSelection->eigenPicknick) $record['payload']['remiseLunch'] = '0';
        $quote = $this->app->calculator->calculate($b->schoolSector, $b->program, $b->studentCount, $b->supervisorCount, $b->foodAndDrinkSelection)->toArray();
        $education = [];
        $selection = json_decode($record['payload']['educationSelection'], true, 12, JSON_THROW_ON_ERROR);
        foreach ($selection['selectedLevels'] as $level) {
            $config = Config::SCHOOL_LEVELS[$b->schoolSector][$level];
            $education[] = $config['label'].': '.implode(', ', array_map(static fn (string $key): string => $config['groups'][$key], $selection['selectedGroupsByLevel'][$level]));
        }
        return ['id'=>$record['id'], 'status'=>$record['status'], 'payload'=>$record['payload'], 'quote'=>$quote,
            'sectorLabel'=>Config::getSchoolSectorLabel($b->schoolSector), 'programLabel'=>Policy::getProgramLabel($b->program),
            'moduleLabel'=>$b->choiceModuleKey ? Config::getModuleLabel($b->choiceModuleKey) : 'Vaste modules', 'educationLabels'=>$education,
            'warnings'=>array_map(static fn ($issue): string => $issue->title, $warnings)];
    }

    public function stored(array $record): StoredBooking
    {
        $p = $record['payload'];
        return new StoredBooking(
            $record['id'], $record['status'], $p['bezoekdatum'], $p['schoolnaam'], $p['land'], $p['adres'], $p['postcode'], $p['plaats'], $p['schoolTelefoonnummer'], $p['contactpersoonTelefoonnummer'],
            $p['contactpersoonVoornaam'], $p['contactpersoonAchternaam'], $p['email'], $p['hoeKentUGeoFort'], $p['cjpPasGebruik'], $p['cjpContactpersoonNaam'] ?? null, $p['cjpPasnummer'] ?? null,
            $p['onderwijsSector'], $p['programma'], $p['keuzemodule'] ?: null, (int) $p['aantalLeerlingen'], (int) $p['aantalBegeleiders'],
            (new EducationSelectionValidator)->validate($p['educationSelection'], $p['onderwijsSector']), (new FoodAndDrinkSelectionValidator)->validate($p),
            $p['opmerkingen'], $p['voorwaardenAkkoord'] === '1', null, new BookingSourceMetadata(null, null, null, null, true),
        );
    }

    public function complete(array $payload, array $context): array
    {
        if (count($context) >= self::MAX_RECORDS) self::fail('demo', 'Deze demo bevat al 120 aanvragen. Start opnieuw om verder te oefenen.');
        $this->app->complete($payload);
        // Behoud alleen de begrensde publieke invoervelden, geen requestmetadata.
        $keys = array_keys(Seed::records($this->app->clock)[0]['payload']);
        $keys = [...$keys, 'cjpContactpersoonNaam', 'cjpPasnummer'];
        $payload = array_intersect_key($payload, array_flip($keys));
        foreach ($payload as $key => $value) {
            if (!is_string($value)) self::fail($key, 'Ongeldige veldinhoud.');
            $payload[$key] = trim($value);
        }
        foreach ($keys as $key) if (!isset($payload[$key]) && !str_starts_with($key, 'cjp')) $payload[$key] = '';
        return ['ok'=>true, 'mode'=>'demo', 'mailDelivery'=>'simulated', 'record'=>$this->decorate(['id'=>max([100, ...array_column($context, 'id')]) + 1, 'status'=>Policy::STATUS_OPTION, 'payload'=>$payload])];
    }

    public function mutate(array $request, array $context): array
    {
        $record = json_decode($request['record'] ?? '', true, 16, JSON_THROW_ON_ERROR);
        if (!is_array($record) || !is_int($record['id'] ?? null) || !is_string($record['status'] ?? null) || !Policy::isAllowedStatus($record['status']) || !is_array($record['payload'] ?? null)) self::fail('demo', 'Ongeldige aanvraag.');
        $match = array_values(array_filter($context, static fn (array $r): bool => $r['id'] === $record['id']));
        if (count($match) !== 1 || $match[0]['status'] !== $record['status'] || $match[0]['payload']['bezoekdatum'] !== ($record['payload']['bezoekdatum'] ?? null) || $match[0]['payload']['aantalLeerlingen'] !== ($record['payload']['aantalLeerlingen'] ?? null)) self::fail('demo', 'De aanvraag is gewijzigd. Open deze opnieuw.');
        foreach ($record['payload'] as $value) if (!is_string($value) || mb_strlen($value) > 2000) self::fail('demo', 'Ongeldige veldinhoud.');
        $action = $request['action'] ?? '';
        if ($action === 'edit') {
            $patch = json_decode($request['changes'] ?? '', true, 12, JSON_THROW_ON_ERROR);
            if (!is_array($patch) || array_diff(array_keys($patch), self::EDIT_FIELDS) !== []) self::fail('demo', 'Dit veld is niet bewerkbaar in de demo.');
            foreach ($patch as $value) if (!is_string($value) || mb_strlen($value) > 600) self::fail('demo', 'Gebruik maximaal 600 tekens per veld.');
            $record['payload'] = [...$record['payload'], ...$patch];
        } elseif ($action === 'status') {
            $decision = (new BookingStatusTransitionPolicy)->decide($record['status'], $request['target'] ?? '');
            if (!$decision->allowed) self::fail('status', 'Kies een andere, ondersteunde status.');
        } else self::fail('demo', 'Deze actie hoort niet bij de demonstratierol.');
        $p = &$record['payload'];
        foreach (['aantalLeerlingen'=>[1,self::MAX_STUDENTS], 'aantalBegeleiders'=>[0,Policy::MAX_SUPERVISORS_PER_BOOKING]] as $field=>[$min,$max]) {
            $label = $field === 'aantalLeerlingen' ? 'Leerlingen' : 'Begeleiders';
            if (!preg_match('/^\d+$/D', $p[$field]) || (int) $p[$field] < $min || (int) $p[$field] > $max) self::fail($field, "{$label}: vul een heel aantal in van {$min} tot {$max}.");
        }
        $p['opmerkingen'] = (new Validator)->optionalMultilineText('opmerkingen', $p['opmerkingen'], FormRules::RULES['opmerkingen']) ?? '';
        $b = $this->stored($record);
        $contact = (new BookingSchoolContactValidator)->validate(BookingSchoolContactDetails::fromStoredBooking($b));
        if ($contact['issues'] !== []) self::fail($contact['issues'][0]->field, $contact['issues'][0]->description);
        $contactKeys = ['schoolName'=>'schoolnaam','country'=>'land','address'=>'adres','postalCode'=>'postcode','city'=>'plaats','schoolPhone'=>'schoolTelefoonnummer','contactFirstName'=>'contactpersoonVoornaam','contactLastName'=>'contactpersoonAchternaam','contactEmail'=>'email','contactPhone'=>'contactpersoonTelefoonnummer'];
        foreach ($contact['details']->toArray() as $key => $value) $p[$contactKeys[$key]] = $value;
        $confirming = $action === 'status' && $request['target'] === Policy::STATUS_CONFIRMED;
        $confirmed = $record['status'] === Policy::STATUS_CONFIRMED;
        $base = $confirming ? (new StoredBookingValidator($this->app->calendar))->validateForTargetStatus($b, Policy::STATUS_CONFIRMED, $this->app->clock->today())->issues : [];
        $profile = $confirming ? BookingValidationProfile::ConfirmBooking : ($confirmed ? BookingValidationProfile::ChangeAttendanceConfirmed : BookingValidationProfile::ChangeAttendanceDraft);
        $issues = $action === 'status' && !$confirming ? [] : (new BookingValidationCoordinator)->validate($profile, $b, new BookingValidationContext(baseIssues: $base))->issues;
        foreach ($issues as $issue) {
            if ($confirming || $confirmed || !$issue->overridable) self::fail($issue->field, $issue->code === 'CURRENT_CONFIGURATION_MISMATCH' ? 'Controleer de programma-, onderwijs- en aantallenregels voordat u definitief maakt.' : ($issue->code === 'HISTORICAL_VISIT_DATE' ? 'Een verstreken bezoekdatum kan niet definitief worden gemaakt.' : $issue->title));
        }
        if ($confirming || ($action === 'edit' && $confirmed)) {
            $others = array_filter($context, static fn (array $r): bool => $r['id'] !== $record['id']);
            $stats = (new FictionalCalendar($this->app->clock, $others))->getBookingStatsForDate($b->visitDate);
            $capacity = (new BookingCapacityValidator)->validate(new DayCapacityTotals($stats['bookedSchools'], $stats['bookedStudents']), $b->studentCount, new EffectiveDayCapacity(Policy::MAX_SCHOOLS_PER_DAY, Policy::MAX_STUDENTS_TOTAL_PER_DAY, null, null, Policy::MAX_SCHOOLS_PER_DAY, Policy::MAX_STUDENTS_TOTAL_PER_DAY));
            if (!$capacity->allowed) self::fail('bezoekdatum', 'Deze wijziging overschrijdt de beschikbare school- of leerlingcapaciteit.');
        }
        if ($action === 'status') $record['status'] = $request['target'];
        return $this->decorate($record, $issues);
    }
}
