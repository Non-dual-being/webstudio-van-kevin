<?php
declare(strict_types=1);
require __DIR__.'/domain.php';

use Demo\{Application, Dashboard, DemoClock, FictionalCalendar, Seed};
use GeoFort\Booking\{BookingPolicy, BookingProgramConfig};
use GeoFort\Booking\Status\BookingStatusTransitionPolicy;
use GeoFort\Booking\Validation\{BookingValidationCoordinator, BookingValidationContext, BookingValidationProfile, StoredBookingValidator};
use GeoFort\Validation\{ProgramSelectionValidator, ChoiceModuleSelectionValidator};

$clock = new DemoClock('2031-10-06');
$app = new Application($clock);
$dashboard = new Dashboard($app);
$seed = $dashboard->seed();
check(count($seed) === 100 && count(array_unique(array_column(array_column($seed,'payload'),'schoolnaam'))) === 100, 'Exact 100 unieke fictieve scholen');
check($seed === $dashboard->seed(), 'Vaste seed herhaalbaar');
check(count(array_unique(array_column($seed,'status'))) === 3, 'Alle bronstatussen');
check(count(array_unique(array_column(array_column($seed,'payload'),'onderwijsSector'))) === 3, 'Alle onderwijssectoren');
check(count(array_unique(array_column(array_column($seed,'payload'),'programma'))) === 2, 'Beide programma’s');
foreach ($seed as $r) {
    $b = $dashboard->stored($r);
    check(str_ends_with($b->email, '@example.invalid'), 'Synthetisch adres');
    check((new GeoFort\Services\Booking\SchoolContact\BookingSchoolContactValidator)->validate(GeoFort\Booking\SchoolContact\BookingSchoolContactDetails::fromStoredBooking($b))['issues'] === [], 'Geldige fictieve school/contactgegevens');
    $onVisit = new DateTimeImmutable($b->visitDate, new DateTimeZone('Europe/Amsterdam'));
    (new ProgramSelectionValidator)->validate($b->program,$b->schoolSector,$onVisit);
    (new ChoiceModuleSelectionValidator)->validate($b->choiceModuleKey,$b->schoolSector,$b->program,$b->educationSelection);
    $base = (new StoredBookingValidator($app->calendar))->validateForTargetStatus($b,'Definitief',min($clock->today(),$onVisit));
    $validation = (new BookingValidationCoordinator)->validate(BookingValidationProfile::ConfirmBooking,$b,new BookingValidationContext(baseIssues:$base->issues));
    check($validation->isValid(), 'Seed voldoet aan beheerregels: '.$r['id']);
    $stats = $app->calendar->getBookingStatsForDate($b->visitDate);
    check($stats['bookedSchools'] <= 2 && $stats['bookedStudents'] <= 160, 'Seed dagcapaciteit');
    check($r['quote']['total']['amountInclVatCents'] === $app->calculator->calculate($b->schoolSector,$b->program,$b->studentCount,$b->supervisorCount,$b->foodAndDrinkSelection)->toArray()['total']['amountInclVatCents'], 'Seedprijs met broncalculator');
}
function mutate(array $r, string $action, array $values, array $context): array {
    $app = new Application(new DemoClock('2031-10-06'),$context);
    return (new Dashboard($app))->mutate(['record'=>json_encode($r),'action'=>$action,'changes'=>json_encode($values),'target'=>$values['target']??''],$context);
}
$draft = current(array_filter($seed,fn($r)=>$r['status']==='In optie' && $r['payload']['bezoekdatum']>'2031-10-06'));
$small = mutate($draft,'edit',['aantalLeerlingen'=>'1','aantalBegeleiders'=>'0'],$seed);
check($small['payload']['aantalLeerlingen']==='1' && count($small['warnings'])>0, 'Beheer vanaf één leerling, waarschuwing begeleiding');
$smallContext = array_map(fn($r)=>$r['id']===$small['id']?$small:$r,$seed);
invalid(fn()=>mutate($small,'status',['target'=>'Definitief'],$smallContext),'aantalLeerlingen','Definitief onder publiek minimum geweigerd');
invalid(fn()=>mutate($draft,'edit',['aantalLeerlingen'=>'0'],$seed),'aantalLeerlingen','Nul leerlingen geweigerd');
invalid(fn()=>mutate($draft,'edit',['aantalBegeleiders'=>'51'],$seed),'aantalBegeleiders','Begeleidermaximum');
invalid(fn()=>mutate($draft,'edit',['aantalLeerlingen'=>'2.5'],$seed),'aantalLeerlingen','Fractie geweigerd');
invalid(fn()=>mutate($draft,'edit',['aantalLeerlingen'=>'1001'],$seed),'aantalLeerlingen','Technische invoergrens');
invalid(fn()=>mutate($draft,'edit',['programma'=>'ochtend'],$seed),'demo','Beperkte demonstratierol');
invalid(fn()=>mutate($draft,'edit',['waterijsje'=>'9999'],$seed),'waterijsje','Cateringmaximum');
invalid(fn()=>mutate($draft,'edit',['email'=>'onjuist'],$seed),'contactEmail','Schoolcontactvalidatie');
invalid(fn()=>mutate($draft,'status',['target'=>'Betaald'],$seed),'status','Niet-bestaande status');
invalid(fn()=>mutate($draft,'status',['target'=>$draft['status']],$seed),'status','Identieke status');
foreach(BookingPolicy::ALLOWED_STATUSES as $from) foreach(BookingPolicy::ALLOWED_STATUSES as $to) check((new BookingStatusTransitionPolicy)->decide($from,$to)->allowed === ($from!==$to), 'Bronovergangsmatrix');
$changed=mutate($draft,'edit',['aantalLeerlingen'=>'64','aantalBegeleiders'=>'8','waterijsje'=>'4'],$seed);
check($changed['quote']['visit']['studentCount']===64 && $changed['quote']['foodAndDrink']['amountInclVatCents']>0, 'Aantallen en catering herberekenen');
$context=array_map(fn($r)=>$r['id']===$changed['id']?$changed:$r,$seed);
$confirmed=mutate($changed,'status',['target'=>'Definitief'],$context);
check($confirmed['status']==='Definitief', 'Toegestane bevestiging');
$context=array_map(fn($r)=>$r['id']===$confirmed['id']?$confirmed:$r,$context);
check((new FictionalCalendar($clock,$context))->getBookingStatsForDate($confirmed['payload']['bezoekdatum'])['bookedStudents']===64,'Definitieve capaciteit volgt records');
$released=mutate($confirmed,'status',['target'=>'Afgewezen'],$context);
$context=array_map(fn($r)=>$r['id']===$released['id']?$released:$r,$context);
check((new FictionalCalendar($clock,$context))->getBookingStatsForDate($released['payload']['bezoekdatum'])['bookedStudents']===0,'Afwijzen geeft capaciteit vrij');
$public=$dashboard->complete(fixture(),$seed);
check($public['record']['id']===101 && $public['record']['status']==='In optie','Publieke aanvraag begint in optie');
$emptyComments=mutate($public['record'],'edit',['aantalLeerlingen'=>'48','aantalBegeleiders'=>'6','opmerkingen'=>''],[...$seed,$public['record']]);
check($emptyComments['payload']['opmerkingen']==='', 'Lege opmerkingen blijven een geldige lege string in het democontract');
check(mutate($emptyComments,'status',['target'=>'Definitief'],[...$seed,$emptyComments])['status']==='Definitief','Eigen aanvraag met lege opmerkingen blijft bewerkbaar en bevestigbaar');
$context=[...$seed,$public['record']];
check((new FictionalCalendar($clock,$context))->getBookingStatsForDate('2031-10-15')['bookedStudents']===0,'Optie reserveert geen capaciteit');
$overbook=$public['record']; $overbook['payload']['bezoekdatum']=$seed[0]['payload']['bezoekdatum']; $overbook['payload']['programma']='dag';
$overbook['payload']['keuzemodule']=BookingProgramConfig::getChoiceModulesForSelection('primairOnderwijs','dag')[0]??'';
invalid(fn()=>mutate($overbook,'status',['target'=>'Definitief'],[...$seed,$overbook]),'bezoekdatum','Definitief boven dagcapaciteit');
$history=current(array_filter($seed,fn($r)=>$r['payload']['bezoekdatum']<'2031-10-06' && $r['status']!=='Definitief'));
invalid(fn()=>mutate($history,'status',['target'=>'Definitief'],$seed),'bezoekdatum','Historie niet opnieuw bevestigen');
$unchanged=$seed[0];
invalid(fn()=>mutate($seed[0],'edit',['schoolnaam'=>'Veranderd','aantalLeerlingen'=>'161','aantalBegeleiders'=>'20'],$seed),'aantalLeerlingen','Geen gedeeltelijke update bij programmafout');
check($unchanged===$seed[0],'Inputrecords blijven atomair behouden');
foreach(['2031-12-31','2040-01-01','2040-12-31'] as $today) {
    $a=new Application(new DemoClock($today)); $s=(new Dashboard($a))->seed();
    check(count($s)===100 && max(array_column(array_column($s,'payload'),'bezoekdatum'))>$today,'Datumbron verschuift over jaarwisseling');
}
$compact=array_map(fn($r)=>['id'=>$r['id'],'status'=>$r['status'],'date'=>$r['payload']['bezoekdatum'],'students'=>(int)$r['payload']['aantalLeerlingen']],$seed);
check(count(Dashboard::context(json_encode($compact)))===100,'Contextcontract');
invalid(fn()=>Dashboard::context(json_encode([...$compact,$compact[0]])),'demo','Dubbele context-ID geweigerd');
invalid(fn()=>Dashboard::context(json_encode(array_fill(0,121,$compact[0]))),'demo','Recordgrens');
invalid(fn()=>$dashboard->complete(fixture(),array_fill(0,120,$seed[0])),'demo','Maximum eigen aanvragen wordt bij afronden gehandhaafd');
echo "OK: {$count} formulier- en dashboarddomeincontroles.\n";
