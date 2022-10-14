<?php

namespace App\Repositories\CurrencyLocation;

use Illuminate\Support\Collection;
use App\Models\CurrencyLocation;

class CurrencyLocationRepository 
{
    public function insert(Collection $currencyLocationDTOs, int $currencyId) 
    {
        $currencyLocationDTOs->each(function ($item) use ($currencyId) {
            CurrencyLocation::create([
                'currency_id' => $currencyId,
                'location' => $item->location,
                'icon' => $item->icon
            ]);
        });
    }

    public function get(int $currencyLocationId) 
    {
        return CurrencyLocation::find($currencyLocationId);
    }

    public function list() 
    {
        return CurrencyLocation::all();
    }
}