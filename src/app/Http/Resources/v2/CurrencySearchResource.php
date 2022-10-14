<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencySearchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "name" => $this->name,  
            "code" => $this->code,  
            "number" => $this->number,
            "symbol" => $this->symbol,
            "decimal_places" => $this->decimalPlaces,  
            "locations" => CurrencyLocationResource::collection($this->locations)
        ];
    }
}
