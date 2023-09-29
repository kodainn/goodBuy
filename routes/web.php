<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostListController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/postlist', PostListController::class);

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::resource('/profile', ProfileController::class);

require __DIR__.'/auth.php';
