<?php

declare(strict_types=1);

namespace GeoFort\Services\Booking\Pricing;

use GeoFort\Services\Booking\Data\FoodAndDrinkSelectionData;
use InvalidArgumentException;

final readonly class BookingPricingInput
{
    public function __construct(
        public string $schoolSector,
        public string $program,
        public int $studentCount,
        public int $supervisorCount,
        public FoodAndDrinkSelectionData $foodAndDrink,
    ) {
        if ($studentCount <= 0 || $supervisorCount < 0) throw new InvalidArgumentException('PRICE_CALCULATION_INVALID');
    }

}
