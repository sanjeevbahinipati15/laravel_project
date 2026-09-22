<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json(
            $request->user()
        );
    })->name('api.user');
    // Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');
    // Route::get('/user/edit/{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.edit');
    // Route::put('/user/update/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    // Route::delete('/user/delete/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/logout', function (Illuminate\Http\Request $request) {

        $request->user()->currentAccessToken()->delete(); // Revoke all tokens for the user

        return response()->json(['success' => 'Logged out successfully!'], 200);
    })->name('api.logout');
});
