<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyLocationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'location' => $this->location,
            'icon' => $this->icon
        ];
    }
}
