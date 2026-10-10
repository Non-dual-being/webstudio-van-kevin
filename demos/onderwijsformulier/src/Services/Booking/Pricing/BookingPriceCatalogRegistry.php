<?php

declare(strict_types=1);

namespace GeoFort\Services\Booking\Pricing;

use InvalidArgumentException;

final class BookingPriceCatalogRegistry
{
    /** @var array<string, BookingPriceCatalog> */
    private array $catalogs;

    /** @param list<BookingPriceCatalog>|null $catalogs */
    public function __construct(array $catalogs, private readonly string $activeVersion)
    {
        $this->catalogs = [];
        foreach ($catalogs as $catalog) {
            if (isset($this->catalogs[$catalog->version])) throw new InvalidArgumentException('Prijsversies zijn immutable en uniek.');
            $this->catalogs[$catalog->version] = $catalog;
        }
        if (!isset($this->catalogs[$activeVersion])) throw new InvalidArgumentException('PRICE_VERSION_UNKNOWN');
    }

    public function active(): BookingPriceCatalog { return $this->catalogs[$this->activeVersion]; }
    public function get(string $version): BookingPriceCatalog
    {
        return $this->catalogs[$version] ?? throw new InvalidArgumentException('PRICE_VERSION_UNKNOWN');
    }
}
