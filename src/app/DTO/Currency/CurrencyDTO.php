<?php

namespace App\DTO\Currency;

use App\DTO\Crawling\CrawlingCurrencyDTO;
use Illuminate\Support\Collection;
use App\Models\Currency;

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

    public static function fromCrawlingCurrencyDTO(CrawlingCurrencyDTO $crawlingCurrencyDTO) 
    {
        return new self(
            name: $crawlingCurrencyDTO->name,
            number: $crawlingCurrencyDTO->number,
            code: $crawlingCurrencyDTO->code,
            decimalPlaces: in_array($crawlingCurrencyDTO->decimalPlaces, ['.', '?', ' ', '']) ? 0 : (int) $crawlingCurrencyDTO->decimalPlaces,
            symbol:  $crawlingCurrencyDTO->symbol,
            locations: collect($crawlingCurrencyDTO->locations)
                ->map(function ($item) {
                    return new CurrencyLocationDTO(data_get($item, 'location'), data_get($item, 'icon'));
                })
        );
    }

}