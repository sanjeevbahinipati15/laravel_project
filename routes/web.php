<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
})->name('login.form');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->name('login');


Route::post('/register', [\App\Http\Controllers\UserController::class, 'register'])->name('register');
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');


Route::middleware('auth')->group(function () {



    Route::get('/user/edit/{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.edit');
    Route::put('/user/update/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/user/delete/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    // Route::post('/logout', function (Illuminate\Http\Request $request) {
    //     Auth::logout();
    //     $request->user()->tokens()->delete(); // Revoke all tokens for the user
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect('/')->with('success', 'Logged out successfully!');
    // })->name('logout');
});


