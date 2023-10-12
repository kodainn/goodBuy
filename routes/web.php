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

//投稿
Route::prefix('postlist')->name('postlist.')->controller(PostListController::class)->group(function() {

    Route::middleware('auth')->group(function() {
        Route::get('/getpost', 'getPost');
        Route::post('/', 'store')->name('store');
        Route::post('/good/{post_uuid}', 'goodStore');
        Route::delete('/good/{post_uuid}', 'goodDelete');
    });

    Route::get('/search/{genre}', 'searchGenre');
    Route::get('/', 'index')->name('index');
    Route::get('/{post_uuid}', 'show')->name('show');
});


//お問い合わせ
Route::prefix('contact')->name('contact.')->controller(ContactController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/conf', 'conf')->name('conf');
    Route::post('/comp', 'comp')->name('comp');
});


//プロフィール
Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->middleware('auth')->group(function() {
    Route::post('/follow/{user_uuid}', 'followStore');
    Route::delete('/follow/{user_uuid}', 'followDelete');
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/{user_uuid}', 'show')->name('show');
});


require __DIR__.'/auth.php';
