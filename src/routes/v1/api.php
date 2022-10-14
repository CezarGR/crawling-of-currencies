<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::prefix('currencies')->as('currencies.')->group(function () {
    Route::post('search', [CurrencyController::class, 'search'])
        ->name('search');
});