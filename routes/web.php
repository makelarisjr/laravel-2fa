<?php

use Illuminate\Support\Facades\Route;
use MakelarisJR\Laravel2FA\Http\Controllers\Verify2FAController;

Route::group(['middleware' => 'throttle:100'], function() {
    Route::view('/verify', 'laravel2fa::verify')
        ->middleware(\MakelarisJR\Laravel2FA\Http\Middleware\ShouldVerify::class)
        ->name('verify2fa');
    Route::post('/verify', Verify2FAController::class)
        ->name('verify2fa.post')->middleware('throttle:10');
});
