<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::post('/submit', [\App\Http\Controllers\VerificationController::class, 'submit'])->name('submit');
