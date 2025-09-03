<?php

use App\Http\Controllers\Admin\Adverts\AttributeController;
use App\Http\Controllers\Admin\Adverts\CategoryController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Adverts\AdvertController;
use App\Http\Controllers\Adverts\FavoriteController;
use App\Http\Controllers\Auth\NetworkController;
use App\Http\Controllers\Cabinet\Adverts\AdvertController as CabinetAdvertController;
use App\Http\Controllers\Cabinet\Adverts\CreateController;
use App\Http\Controllers\Cabinet\Adverts\ManageController;
use App\Http\Controllers\Cabinet\Banners\BannerController;
use App\Http\Controllers\Cabinet\HomeCabinetController;
use App\Http\Controllers\Cabinet\PhoneController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\FilledProfile;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

});

Route::get('/', [HomeController::class, 'index'])->name('dashboard');
Route::redirect('/dashboard', '/');

Route::get('/login/{network}', [NetworkController::class, 'redirect'])->name('login.network');
Route::get('/login/{network}/callback', [NetworkController::class, 'callback']);

Route::get('/banner/get', [\App\Http\Controllers\BannerController::class, 'get'])->name('banner.get');
Route::get('/banner/{banner}/click', [\App\Http\Controllers\BannerController::class, 'click'])->name('banner.click');

Route::group(
    [
        'prefix' => 'adverts',
        'as' => 'adverts.',
    ],
    function () {
        Route::get('/show/{advert}', [AdvertController::class, 'show'])->name('show');
        Route::post('/show{advert}/phone', [AdvertController::class, 'phone'])->name('phone');
        Route::post('/show/{advert}/favorites', [FavoriteController::class, 'add'])->name('favorites');
        Route::delete('/show/{advert}/favorites', [FavoriteController::class, 'remove']);

        Route::get('/{adverts_path?}', [AdvertController::class, 'index'])->name('index')->where('adverts_path', '.+');
    });

Route::group(
    [
        'prefix' => 'cabinet',
        'as' => 'cabinet.',
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

        Route::get('/favorites', [\App\Http\Controllers\Cabinet\FavoriteController::class, 'index'])->name('favorites.index');
        Route::delete('/favorites/{advert}', [\App\Http\Controllers\Cabinet\FavoriteController::class, 'remove'])->name('favorites.remove');

        Route::group(
            [
                'prefix' => 'adverts',
                'as' => 'adverts.',
                'middleware' => [FilledProfile::class],
            ], function () {

            Route::get('/', [CabinetAdvertController::class, 'index'])->name('index');
            Route::get('/create', [CreateController::class, 'category'])->name('create');
            Route::get('/create/region/{category}/{region?}', [CreateController::class, 'region'])->name('create.region');
            Route::get('/create/advert/{category}/{region?}', [CreateController::class, 'advert'])->name('create.advert');
            Route::post('/create/advert/{category}/{region?}', [CreateController::class, 'store'])->name('create.advert.store');

            Route::get('/{advert}/edit', [ManageController::class, 'edit'])->name('edit');
            Route::put('/{advert}/edit', [ManageController::class, 'editForm']);
            Route::get('/{advert}/photos', [ManageController::class, 'photosForm'])->name('photos');
            Route::post('/{advert}/photos', [ManageController::class, 'photos']);
            Route::get('{advert}/attributes', [ManageController::class, 'attributesForm'])->name('attributes');
            Route::post('{advert}/attributes', [ManageController::class, 'attributes']);
            Route::post('/{advert}/close', [ManageController::class, 'close'])->name('close');
            Route::post('/{advert}/send', [ManageController::class, 'send'])->name('send');
            Route::delete('/{advert}/destroy', [ManageController::class, 'destroy'])->name('destroy');

        });

        Route::group([
            'prefix' => 'banners',
            'as' => 'banners.',
            'middleware' => [FilledProfile::class],
        ], function () {
            Route::get('/', [BannerController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Cabinet\Banners\CreateController::class, 'category'])->name('create');
            Route::get('/create/region/{category}/{region?}', [\App\Http\Controllers\Cabinet\Banners\CreateController::class, 'region'])->name('create.region');
            Route::get('/create/banner/{category}/{region?}', [\App\Http\Controllers\Cabinet\Banners\CreateController::class, 'banner'])->name('create.banner');
            Route::post('/create/banner/{category}/{region?}', [\App\Http\Controllers\Cabinet\Banners\CreateController::class, 'store'])->name('create.banner.store');

            Route::get('/show/{banner}', [BannerController::class, 'show'])->name('show');
            Route::get('/{banner}/edit', [BannerController::class, 'editForm'])->name('edit');
            Route::put('/{banner}/edit', [BannerController::class, 'edit']);
            Route::get('/{banner}/file', [BannerController::class, 'fileForm'])->name('file');
            Route::put('/{banner}/file', [BannerController::class, 'file']);
            Route::post('/{banner}/send', [BannerController::class, 'send'])->name('send');
            Route::post('/{banner}/cancel', [BannerController::class, 'cancel'])->name('cancel');
            Route::post('/{banner}/order', [BannerController::class, 'order'])->name('order');
            Route::delete('/{banner}/destroy', [BannerController::class, 'destroy'])->name('destroy');
        });

    });

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['auth', 'can:admin-panel'],
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

            Route::group(['prefix' => 'adverts', 'as' => 'adverts.'], function () {
                Route::get('/', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'index'])->name('index');
                Route::get('/{advert}/edit', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'editForm'])->name('edit');
                Route::put('/{advert}/edit', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'edit']);
                Route::get('/{advert}/photos}', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'photosForm'])->name('photos');
                Route::post('/{advert}/photos', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'photos']);
                Route::get('/{advert}/attributes}', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'attributesForm'])->name('attributes');
                Route::post('/{advert}/attributes', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'attributes']);
                Route::post('/{advert}/moderate', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'moderate'])->name('moderate');
                Route::get('/{advert}/reject', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'rejectForm'])->name('reject');
                Route::post('/{advert}/reject', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'reject']);
                Route::delete('{advert}/destroy', [\App\Http\Controllers\Admin\Adverts\AdvertController::class, 'destroy'])->name('destroy');
            });

        });

        Route::group(['prefix' => 'banners', 'as' => 'banners.'], function () {
            Route::get('/', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('index');
            Route::get('/{banner}/show', [\App\Http\Controllers\Admin\BannerController::class, 'show'])->name('show');
            Route::get('/{banner}/edit', [\App\Http\Controllers\Admin\BannerController::class, 'editForm'])->name('edit');
            Route::put('/{banner}/edit', [\App\Http\Controllers\Admin\BannerController::class, 'edit']);
            Route::post('/{banner}/moderate', [\App\Http\Controllers\Admin\BannerController::class, 'moderate'])->name('moderate');
            Route::get('/{banner}/reject', [\App\Http\Controllers\Admin\BannerController::class, 'rejectForm'])->name('reject');
            Route::post('/{banner}/reject', [\App\Http\Controllers\Admin\BannerController::class, 'reject']);
            Route::post('/{banner}/pay', [\App\Http\Controllers\Admin\BannerController::class, 'pay'])->name('pay');
            Route::delete('/{banner}/destroy', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('destroy');
        });

    });
