<?php

namespace App\Enums;

enum ContactService: string
{
    case Website = 'website';
    case Application = 'application';
    case Shop = 'shop';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Websites',
            self::Application => 'Webapplicaties op maat',
            self::Shop => 'Webshops en productcatalogi',
            self::Maintenance => 'Onderhoud en doorontwikkeling',
        };
    }

    /** @return list<array{value: string, label: string}> */
    public static function options(): array
    {
        return array_map(fn (self $service) => ['value' => $service->value, 'label' => $service->label()], self::cases());
    }
}
