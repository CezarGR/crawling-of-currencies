<?php

namespace App\DTO\Crawling;

class CrawlingCurrencyDTO 
{
    public readonly string $name;
    public readonly string $number;
    public readonly string $code;
    public readonly int $decimalPlaces;
    public readonly ?string $symbol;
    public array $locations;

    public function __construct(
        $name,
        $number,
        $code,
        $decimalPlaces,
        $locations,
        $symbol
    ) {
        $this->name = $name;
        $this->number = $number;
        $this->code = $code;
        $this->decimalPlaces = in_array($decimalPlaces, ['.', '?', ' ', '']) ? 0 : (int) $decimalPlaces;
        $this->symbol = $symbol;
        $this->locations = $locations;
    }
}