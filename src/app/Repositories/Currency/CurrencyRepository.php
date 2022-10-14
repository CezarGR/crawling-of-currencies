<?php

namespace App\Repositories\Currency;

use App\Repositories\CurrencyLocation\CurrencyLocationRepository;
use Illuminate\Support\Collection;
use App\Models\Currency;

class CurrencyRepository 
{
    public function insert(Collection $currencyDTOs) 
    {
        $currencyDTOs->each(function ($item) {
            $currency = Currency::create([
                'code' => $item->code,
                'number' => $item->number,
                'name' => $item->name,
                'symbol' => $item->symbol,
                'decimal_places' => $item->decimalPlaces
            ]);

            (new CurrencyLocationRepository)->insert($item->locations, $currency->id);
        });
    }

    public function get(int $currencyId) 
    {
        return Currency::find($currencyId);
    }

    public function list() 
    {
        return Currency::all();
    }
}