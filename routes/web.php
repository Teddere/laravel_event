<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::name('home.')->group(function (){
    Route::resource('/',\App\Http\Controllers\Front\FrontController::class)->except([
        'create','edit','show'
    ]);
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])
    ->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/',[\App\Http\Controllers\Back\EventController::class,'homePage'])->name('index');
        Route::name('event.')->group( function () {
            Route::get('/events',[\App\Http\Controllers\Back\EventController::class,'index'])->name('index');
            Route::post('/events',[\App\Http\Controllers\Back\EventController::class,'store'])->name('store');
            Route::put('/events',[\App\Http\Controllers\Back\EventController::class,'update'])->name('update');
            Route::delete('/events',[\App\Http\Controllers\Back\EventController::class,'destroy'])->name('delete');
            Route::delete('/event/hide',[\App\Http\Controllers\Back\EventController::class,'mask'])->name('hide');
            // Les événements Masqués
            Route::get('/events/mask',[\App\Http\Controllers\back\EventMaskController::class,'index'])->name('mask');
            Route::put('/events/restore',[\App\Http\Controllers\Back\EventMaskController::class,'restore'])->name('restore');
            Route::delete('/events/mask',[\App\Http\Controllers\Back\EventMaskController::class,'destroy'])->name('remove');
        });
});


