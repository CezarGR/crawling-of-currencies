<?php

namespace App\DTO\Currency;

use App\Models\Currency;
use Illuminate\Support\Collection;

class CurrencyDTO 
{
    public readonly string $name;
    public readonly string $number;
    public readonly string $code;
    public readonly int $decimalPlaces;
    public readonly ?string $symbol;
    public readonly array|Collection $locations;

    public function __construct(
        $name,
        $number,
        $code,
        $decimalPlaces,
        $symbol,
        $locations
    ) {
        $this->name = $name;
        $this->number = $number;
        $this->code = $code;
        $this->decimalPlaces = $decimalPlaces;
        $this->symbol = $symbol;
        $this->locations = $locations;
    }

    public static function fromCurrencyModel(Currency $currency) 
    {
        return new self(
            name: $currency->name,
            number: $currency->number,
            code: $currency->code,
            decimalPlaces: $currency->decimal_places,
            symbol:  $currency->symbol,
            locations: $currency->locations->map(fn ($location) => CurrencyLocationDTO::fromCurrencyLocationModel($location))
        );
    }

}