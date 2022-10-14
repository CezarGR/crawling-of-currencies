<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'location',
        'icon',
        'created_at',
        'updated_at',
    ];
}
