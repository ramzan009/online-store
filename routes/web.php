<?php

use App\Http\Controllers\Admin\Adverts\AttributeController;
use App\Http\Controllers\Admin\Adverts\CategoryController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Cabinet\Adverts\AdvertController;
use App\Http\Controllers\Cabinet\HomeCabinetController;
use App\Http\Controllers\Cabinet\PhoneController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\FiledProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');
Route::redirect('/dashboard', '/');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

});

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
//        'namespace' => 'Cabinet',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/', [HomeCabinetController::class, 'index'])->name('home');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('/', [ProfileController::class, 'index'])->name('home');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::post('/phone', [PhoneController::class, 'request']);
            Route::get('/phone', [PhoneController::class, 'form'])->name('phone');
            Route::put('/phone', [PhoneController::class, 'verify'])->name('phone.verify');

            Route::post('/phone/auth', [PhoneController::class, 'auth'])->name('phone.auth');
        });

        Route::resource('adverts', AdvertController::class)->middleware(FiledProfile::class);

    });

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::resource('users', UsersController::class);
        Route::resource('regions', RegionController::class);

        Route::group(['prefix' => 'adverts', 'as' => 'adverts.'], function () {
            Route::resource('categories', CategoryController::class);

            Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], function () {
                Route::post('/first', [CategoryController::class, 'first'])->name('first');
                Route::post('/up', [CategoryController::class, 'up'])->name('up');
                Route::post('/down', [CategoryController::class, 'down'])->name('down');
                Route::post('/last', [CategoryController::class, 'last'])->name('last');
                Route::resource('attributes', AttributeController::class)->except('index');
            });

        });
    });
