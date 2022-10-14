<?php

namespace App\DTO\Currency;

use App\Models\CurrencyLocation;

class CurrencyLocationDTO 
{
    public readonly string $location;
    public readonly ?string $icon;

    public function __construct(
        string $location,
        ?string $icon
    ) {
        $this->location = $location;
        $this->icon = $icon;
    }

    public static function fromCurrencyLocationModel(CurrencyLocation $location) 
    {
        return new self(
            location: $location->location,
            icon: $location->icon
        );
    }

}