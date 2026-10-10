<?php

declare(strict_types=1);

require dirname(__DIR__).'/server/bootstrap.php';

use Demo\Application;
use Demo\DemoClock;
use GeoFort\Booking\BookingPolicy;
use GeoFort\Booking\BookingProgramConfig;
use GeoFort\Services\Booking\Pricing\BookingPriceCatalog;
use GeoFort\Validation\FieldValidationException;

if (PHP_VERSION !== '8.4.15') {
    throw new RuntimeException('PHP 8.4.15 vereist.');
}

$count = 0;
function check(bool $condition, string $label): void
{
    global $count;
    if (!$condition) {
        throw new RuntimeException($label);
    }
    $count++;
}

function invalid(callable $action, string $field, string $label): void
{
    try {
        $action();
    } catch (FieldValidationException $error) {
        check($error->getField() === $field, $label.' (veld)');
        return;
    }
    throw new RuntimeException($label.' accepteerde ongeldige invoer');
}

function selection(string $sector, string $level, array $groups): string
{
    return json_encode(['sector' => $sector, 'selectedLevels' => [$level], 'selectedGroupsByLevel' => [$level => $groups]], JSON_THROW_ON_ERROR);
}

function fixture(): array
{
    return [
        'schoolnaam' => 'Voorbeeldschool', 'land' => 'Nederland', 'adres' => 'Voorbeeldlaan 1', 'postcode' => '1234 AB', 'plaats' => 'Voorbeeldstad',
        'schoolTelefoonnummer' => '020 000 0000', 'contactpersoonTelefoonnummer' => '06 00000000', 'contactpersoonVoornaam' => 'Demo', 'contactpersoonAchternaam' => 'Bezoeker', 'email' => 'bezoeker@example.invalid',
        'bezoekdatum' => '2031-10-15', 'hoeKentUGeoFort' => '', 'cjpPasGebruik' => 'nee',
        'onderwijsSector' => 'primairOnderwijs', 'programma' => 'ochtend', 'educationSelection' => selection('primairOnderwijs', 'regulier', ['groep5']), 'keuzemodule' => '',
        'aantalLeerlingen' => '60', 'aantalBegeleiders' => '8', 'remiseBreak' => '0', 'kazerneBreak' => '0', 'fortgrachtBreak' => '0', 'waterijsje' => '0', 'glasLimonade' => '0', 'lunchChoice' => 'eigen_picknick', 'remiseLunch' => '0',
        'opmerkingen' => '', 'voorwaardenAkkoord' => '1',
    ];
}

$app = new Application(new DemoClock('2031-10-06'));
$valid = fixture();
check($app->complete($valid) === ['ok' => true, 'mode' => 'demo', 'mailDelivery' => 'simulated'], 'Eerlijke demoafronding');
check($app->complete([...$valid, 'opmerkingen' => str_repeat('é', 600)])['ok'], '600 Unicode-tekens');
invalid(fn () => $app->complete([...$valid, 'opmerkingen' => str_repeat('é', 601)]), 'opmerkingen', '601 Unicode-tekens');
invalid(fn () => $app->complete([...$valid, 'voorwaardenAkkoord' => '0']), 'voorwaardenAkkoord', 'Geen akkoord');
$noAgreement = $valid;
unset($noAgreement['voorwaardenAkkoord']);
invalid(fn () => $app->complete($noAgreement), 'voorwaardenAkkoord', 'Ontbrekend akkoord');
invalid(fn () => $app->complete([...$valid, 'bezoekdatum' => '2031-10-16']), 'programma', 'Ochtend op donderdag');
invalid(fn () => $app->complete([...$valid, 'bezoekdatum' => '2031-10-04']), 'bezoekdatum', 'Verleden');
invalid(fn () => $app->complete([...$valid, 'bezoekdatum' => '2031-10-18']), 'bezoekdatum', 'Weekend');
invalid(fn () => $app->complete([...$valid, 'bezoekdatum' => '2031-02-30']), 'bezoekdatum', 'Niet-bestaande datum');
invalid(fn () => $app->complete([...$valid, 'bezoekdatum' => '2034-01-02']), 'bezoekdatum', 'Buiten horizon');
invalid(fn () => $app->complete([...$valid, 'programma' => 'onbekend']), 'programma', 'Ongeldig programma');
invalid(fn () => $app->complete([...$valid, 'onderwijsSector' => 'voortgezetOnderbouw']), 'programma', 'Ochtend niet voor voortgezet');
invalid(fn () => $app->complete([...$valid, 'programma' => 'dag', 'onderwijsSector' => 'voortgezetOnderbouw']), 'educationSelection', 'Sectorwissel met oude groepen');
invalid(fn () => $app->complete([...$valid, 'educationSelection' => selection('primairOnderwijs', 'havo', ['havo1'])]), 'educationSelection', 'Verkeerd niveau');
invalid(fn () => $app->complete([...$valid, 'educationSelection' => selection('primairOnderwijs', 'regulier', ['groep4'])]), 'educationSelection', 'Verkeerde groep');
invalid(fn () => $app->complete([...$valid, 'educationSelection' => selection('primairOnderwijs', 'regulier', ['groep5', 'groep6', 'groep7', 'groep8'])]), 'educationSelection', 'Te veel groepen');
invalid(fn () => $app->complete([...$valid, 'keuzemodule' => 'Crisismanagement']), 'keuzemodule', 'Ochtend geen keuzemodule');
invalid(fn () => $app->complete([...$valid, 'programma' => 'dag', 'keuzemodule' => 'onbekend']), 'keuzemodule', 'Onbekende module');
$secondary = [...$valid, 'programma' => 'dag', 'onderwijsSector' => 'voortgezetOnderbouw', 'educationSelection' => selection('voortgezetOnderbouw', 'havo', ['havo1']), 'keuzemodule' => 'Minecraft-Windenergiespeurtocht'];
invalid(fn () => $app->complete($secondary), 'keuzemodule', 'Module uitgesloten voor HAVO1');
check($app->complete([...$secondary, 'educationSelection' => selection('voortgezetOnderbouw', 'havo', ['havo2'])])['ok'], 'Module toegestaan voor HAVO2');
$day = [...$valid, 'programma' => 'dag', 'keuzemodule' => 'Earth-Watch'];
foreach ([39, 81] as $students) invalid(fn () => $app->complete([...$valid, 'aantalLeerlingen' => (string) $students]), 'aantalLeerlingen', 'Ochtendgrens '.$students);
foreach ([40, 80] as $students) check($app->complete([...$valid, 'aantalLeerlingen' => (string) $students])['ok'], 'Ochtendgrens '.$students);
check($app->complete([...$day, 'aantalLeerlingen' => '160', 'aantalBegeleiders' => '10'])['ok'], 'Daggrens 160');
invalid(fn () => $app->complete([...$day, 'aantalLeerlingen' => '161', 'aantalBegeleiders' => '11']), 'aantalLeerlingen', 'Daggrens 161');
invalid(fn () => $app->complete([...$valid, 'aantalLeerlingen' => '40.5']), 'aantalLeerlingen', 'Gehele leerlingaantallen');
invalid(fn () => $app->complete([...$valid, 'aantalBegeleiders' => '3']), 'aantalBegeleiders', 'Te weinig begeleiders');
check($app->complete([...$valid, 'aantalBegeleiders' => '4'])['ok'], 'Minimale begeleiding');
check($app->complete([...$valid, 'aantalBegeleiders' => '50'])['ok'], 'Maximale begeleiding');
invalid(fn () => $app->complete([...$valid, 'aantalBegeleiders' => '51']), 'aantalBegeleiders', 'Te veel begeleiders');
check(BookingPolicy::getMinimumSupervisorCount(65) === 5 && BookingPolicy::getFreeSupervisorCount(65) === 9, 'Afronding begeleiders');
check($app->complete([...$valid, 'bezoekdatum' => '2031-10-08', 'aantalLeerlingen' => '40'])['ok'], 'Beperkte capaciteit exact');
invalid(fn () => $app->complete([...$valid, 'bezoekdatum' => '2031-10-08', 'aantalLeerlingen' => '41']), 'aantalLeerlingen', 'Beperkte capaciteit overschreden');
invalid(fn () => $app->complete([...$day, 'bezoekdatum' => '2031-10-10']), 'bezoekdatum', 'Twee scholen vol');
invalid(fn () => $app->complete([...$day, 'bezoekdatum' => '2031-10-14']), 'aantalLeerlingen', 'Leerlingcapaciteit vol');
invalid(fn () => $app->complete([...$day, 'bezoekdatum' => '2031-10-13']), 'bezoekdatum', 'Handmatig geblokkeerd');
invalid(fn () => $app->complete([...$day, 'bezoekdatum' => '2031-10-21']), 'bezoekdatum', 'Fictieve vakantie');
invalid(fn () => $app->complete([...$valid, 'remiseBreak' => '201']), 'remiseBreak', 'Te veel catering');
invalid(fn () => $app->complete([...$valid, 'lunchChoice' => 'remise_lunch', 'remiseLunch' => '49']), 'remiseLunch', 'Lunch minimum');
check($app->complete([...$valid, 'lunchChoice' => 'remise_lunch', 'remiseLunch' => '50'])['ok'], 'Lunch minimum exact');
invalid(fn () => $app->complete([...$valid, 'lunchChoice' => 'onbekend']), 'lunchChoice', 'Ongeldige lunch');
invalid(fn () => $app->complete([...$valid, 'cjpPasGebruik' => 'ja']), 'cjpContactpersoonNaam', 'Voorbeeldpas verplicht naam');
check($app->complete([...$valid, 'cjpPasGebruik' => 'ja', 'cjpContactpersoonNaam' => 'Demo Bezoeker', 'cjpPasnummer' => '12345678'])['ok'], 'Voorbeeldpas compleet');

$quote = $app->quote([...$valid, 'aantalLeerlingen' => '65', 'aantalBegeleiders' => '10', 'waterijsje' => '3']);
check($quote['total']['amountInclVatCents'] === 48195, 'Fictieve catalogus: 66 maal 725 plus 3 maal 115');
check($quote['total']['amountExclVatCents'] === 44216, 'Centafronding inclusief naar exclusief');
check($quote['total']['vatAmountCents'] === 3979, 'BTW-rest in cents');
check($quote['visit']['freeSupervisors'] === 9 && $quote['visit']['paidSupervisors'] === 1, 'Gratis en betaalde begeleiding');
$config = BookingProgramConfig::forFrontend();
check((int) round($config['prices']['bezoek']['ochtend']['basis'] * 100) === $quote['visit']['pricePerVisitorInclVatCents'], 'Eén catalogus: info en prijs');
foreach ($config['foodAndDrinkOptions'] as $options) foreach ($options as $key => $option) check((int) round($option['price'] * 100) === $app->catalogs->active()->cateringPriceCents($key), 'Cateringprijs '.$key);
check($app->catalogs->active()->amountExcludingVatCents(6) === 6 && $app->catalogs->active()->amountExcludingVatCents(7) === 6, 'Halve cent grens');
$half = new BookingPriceCatalog('rounding-test', 'EUR', 10000, BookingPriceCatalog::VAT_MEANING, BookingPriceCatalog::VAT_ROUNDING_HALF_UP_TOTAL_CENTS, BookingPriceCatalog::CALCULATION_PROFILE_PER_ITEM_V1, 8, [], [], []);
check($half->amountExcludingVatCents(1) === 1, 'Exact halve cent rondt omhoog');
$roster = $app->roster($valid);
check($roster['available'] === false && $roster['imageUrl'] === null && $roster['pdfUrl'] === null, 'Geen klantroosterbestanden');
check(count($roster['standardModules']) > 0 && $roster['groupCount'] !== null, 'Oorspronkelijke roosterregels behouden');
$dates = $app->dates();
check($dates['minDate'] === '2031-10-06' && $dates['maxDate'] === '2033-12-31', 'Geïnjecteerde horizon');
check($dates === (new Application(new DemoClock('2031-10-06')))->dates(), 'Reproduceerbare kalender');
$futureDates = (new Application(new DemoClock('2040-12-31')))->dates();
check($futureDates['details'][0]['datum'] > '2040-12-31' && $futureDates['maxDate'] === '2042-12-31', 'Kalender blijft bruikbaar na jaarwisseling');
check(!class_exists('GeoFort\\Services\\Sql\\BookingCalendarSqlService') && !class_exists('GeoFort\\Services\\Booking\\Submission\\BookingSubmissionService'), 'Opslag- en submitinfra afwezig');

echo "OK: {$count} domeincontroles; uitsluitend fictieve gegevens, geen opslag of mail.\n";
