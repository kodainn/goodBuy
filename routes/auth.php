<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::prefix('password_reset')->name('password_reset.')->controller(PasswordResetController::class)->group(function() {
    Route::get('/index', 'index')->name('index');
    Route::post('/send', 'send')->name('send');
    Route::get('/sendComp', 'sendComp')->name('sendComp');
    Route::get('/edit', 'edit')->name('edit');
    Route::post('/update', 'update')->name('update');
    Route::get('/passwordComp', 'passwordComp')->name('passwordComp');
});
