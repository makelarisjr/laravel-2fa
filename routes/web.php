<?php

use Illuminate\Support\Facades\Route;
use MakelarisJR\Laravel2FA\Http\Controllers\Verify2FAController;

Route::view('/verify', 'laravel2fa::verify')
    ->name('verify2fa');
Route::post('/verify', Verify2FAController::class)
    ->name('verify2fa.post');
