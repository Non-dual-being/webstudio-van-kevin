<?php

declare(strict_types=1);

namespace Demo;

use GeoFort\Services\Booking\Availability\BookingAvailabilityService;
use GeoFort\Validation\FieldValidationException;
use GeoFort\Validation\FormRules;
use GeoFort\Validation\Validator;
use GeoFort\Validation\EducationSelectionValidator;
use GeoFort\Validation\ProgramSelectionValidator;
use GeoFort\Validation\ChoiceModuleSelectionValidator;
use GeoFort\Validation\StudentCountValidator;
use GeoFort\Validation\SupervisorCountValidator;
use GeoFort\Validation\FoodAndDrinkSelectionValidator;
use GeoFort\Validation\TermsAcceptanceValidator;

/** Geëxtraheerd uit BookingFormHandler; valideert uitsluitend in geheugen. */
final class ValidateSubmission
{
    private readonly Validator $validator;
    private readonly EducationSelectionValidator $educationSelectionValidator;
    private readonly ProgramSelectionValidator $programSelectionValidator;
    private readonly ChoiceModuleSelectionValidator $choiceModuleSelectionValidator;
    private readonly StudentCountValidator $studentCountValidator;
    private readonly SupervisorCountValidator $supervisorCountValidator;
    private readonly FoodAndDrinkSelectionValidator $foodAndDrinkSelectionValidator;
    private readonly TermsAcceptanceValidator $termsAcceptanceValidator;

    public function __construct(private readonly BookingAvailabilityService $bookingAvailabilityService)
    {
        $this->validator = new Validator;
        $this->educationSelectionValidator = new EducationSelectionValidator;
        $this->programSelectionValidator = new ProgramSelectionValidator;
        $this->choiceModuleSelectionValidator = new ChoiceModuleSelectionValidator;
        $this->studentCountValidator = new StudentCountValidator;
        $this->supervisorCountValidator = new SupervisorCountValidator;
        $this->foodAndDrinkSelectionValidator = new FoodAndDrinkSelectionValidator;
        $this->termsAcceptanceValidator = new TermsAcceptanceValidator;
    }

    public function validate(array $postData): void
    {
        $schoolnaam = $this->validator->text(
            'schoolnaam',
            $postData['schoolnaam'] ?? '',
            FormRules::RULES
        );

        $land = $this->validator->text(
            'land',
            $postData['land'] ?? '',
            FormRules::RULES
        );


        $postcode = $this->validator->postcode(
            $land,
            $postData['postcode'] ?? '',
            FormRules::RULES['postcode']
        );


        $adres = $this->validator->text(
            'adres',
            $postData['adres'] ?? '',
            FormRules::RULES
        );

        $plaats = $this->validator->text(
            'plaats',
            $postData['plaats'] ?? '',
            FormRules::RULES
        );

        $schoolTelefoonnummer = $this->validator->phone(
            'schoolTelefoonnummer',
            $land,
            $postData['schoolTelefoonnummer'] ?? '',
            FormRules::RULES['schoolTelefoonnummer']
        );

        $contactpersoonTelefoonnummer = $this->validator->phone(
            'contactpersoonTelefoonnummer',
            $land,
            $postData['contactpersoonTelefoonnummer'] ?? '',
            FormRules::RULES['contactpersoonTelefoonnummer']
        );

        $contactpersoonVoornaam = $this->validator->text(
            'contactpersoonVoornaam',
            $postData['contactpersoonVoornaam'] ?? '',
            FormRules::RULES
        );

        $contactpersoonAchternaam = $this->validator->text(
            'contactpersoonAchternaam',
            $postData['contactpersoonAchternaam'] ?? '',
            FormRules::RULES
        );

        $email = $this->validator->email(
            field: 'email',
            value: $postData['email'] ?? '',
            rules: FormRules::RULES
        );

        $visitDate = $this->validator->date(
            field: 'bezoekdatum',
            value: $postData['bezoekdatum'] ?? '',
        );

        $availableVisitDate = $this->bookingAvailabilityService->assertDateIsValid($visitDate);

        $hoeKentUGeoFort = $this->validator->discovery(
            field: 'hoeKentUGeoFort',
            value: $postData['hoeKentUGeoFort'] ?? '',
            rules: FormRules::RULES
        );

        $cjpPasGebruik = $this->validator->text(
            'cjpPasGebruik',
            $postData['cjpPasGebruik'] ?? '',
            FormRules::RULES
        );

        $cjpContactpersoonNaam = null;
        $cjpPasnummer =  null;

        if ($cjpPasGebruik === "ja") {
            $cjpContactpersoonNaam = $this->validator->text(
                'cjpContactpersoonNaam',
                $postData['cjpContactpersoonNaam'] ?? '',
                FormRules::RULES
            );

            $cjpPasnummer = $this->validator->text(
                'cjpPasnummer',
                $postData['cjpPasnummer'] ?? '',
                FormRules::RULES
            );
        } else {
            $cjpFieldsSend = (bool) (
                (isset ($postData['cjpContactpersoonNaam']))
                    ||
                (isset($postData['cjpPasnummer']))
            );

            if ($cjpFieldsSend)
                throw new FieldValidationException(
                    'cjpPasGebruik',
                    "Pasgegevens mogen alleen meegestuurd worden als u de voorbeeldpas gebruikt."
                );
        }

        $schoolSector = $this->validator->schoolSector(
            'onderwijsSector',
            $postData['onderwijsSector'] ?? '',
            FormRules::RULES
        );

        /**
         * Eerst simpele veldvalidatie:
         * - bestaat het veld?
         * - is het ochtend of dag?
         */
        $programRaw = $this->validator->text(
            'programma',
            $postData['programma'] ?? '',
            FormRules::RULES
        );

        /**
         * Daarna domeinvalidatie:
         * - mag dit programma bij de gekozen sector?
         * - mag dit programma op deze bezoekdatum?
         */
        $programma = $this->programSelectionValidator->validate(
            $programRaw,
            $schoolSector,
            $availableVisitDate,
        );

        $educationSelection = $this->educationSelectionValidator->validate(
            $postData['educationSelection'] ?? '',
            $schoolSector,
        );

        $keuzemoduleKey = $this->choiceModuleSelectionValidator->validate(
            rawValue: $postData['keuzemodule'] ?? null,
            schoolSector: $schoolSector,
            program: $programma,
            educationSelection: $educationSelection,
        );

        $aantalLeerlingen = $this->studentCountValidator->validate(
            rawValue: $postData['aantalLeerlingen'] ?? '',
            schoolSector: $schoolSector,
            program: $programma,
        );

        $aantalBegeleiders = $this->supervisorCountValidator->validate(
            rawValue: $postData['aantalBegeleiders'] ?? '',
            studentCount: $aantalLeerlingen,
        );

        $foodAndDrinkSelection = $this->foodAndDrinkSelectionValidator->validate($postData);
        $opmerkingen = $this->validator->optionalMultilineText(
            field: 'opmerkingen',
            value: $postData['opmerkingen'] ?? '',
            rules: FormRules::RULES['opmerkingen'],
        );
        $voorwaardenAkkoord = $this->termsAcceptanceValidator->validate(
            $postData['voorwaardenAkkoord'] ?? null,
        );

        $this->bookingAvailabilityService->assertCapacityAvailable(
            visitDate: $availableVisitDate,
            requestedStudents: $aantalLeerlingen,
            program: $programma,
        );

    }
}
