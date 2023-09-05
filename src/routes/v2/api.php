<?php

use App\Http\Controllers\v2\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::prefix('currencies')->as('currencies.')->group(function () {
    Route::post('search', [CurrencyController::class, 'search'])
        ->name('v2.search');
    Route::get('/', [CurrencyController::class, 'list'])
    ->name('v2.list');
});