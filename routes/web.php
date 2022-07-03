<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth']], function(){
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('/tag', TagController::class);
    Route::resource('/cast', CastController::class);
    Route::resource('/genre', GenreController::class);
    Route::resource('/movie', MovieController::class);
});
