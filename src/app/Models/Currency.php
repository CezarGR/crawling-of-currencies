<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory, CacheTrait;

    protected $fillable = [
        'code',
        'number',
        'name',
        'symbol',
        'decimal_places',
        'created_at',
        'updated_at',
    ];

    public $updated_at = false;

    protected $with = [
        'locations'
    ];

    public function locations()
    {
        return $this->hasMany(CurrencyLocation::class);
    }
}
