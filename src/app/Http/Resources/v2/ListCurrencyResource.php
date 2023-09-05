<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Resources\Json\JsonResource;

class ListCurrencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "name" => $this->name,  
            "code" => $this->code,
            "symbol" => $this->symbol,
            "flagUrl" => $this->locations?->first()?->icon ? 'https:' . $this->locations?->first()?->icon : null 
        ];
    }
}
