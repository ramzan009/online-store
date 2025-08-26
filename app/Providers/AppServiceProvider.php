<?php

namespace App\Providers;

use App\Http\Router\AdvertsPath;
use App\Models\Adverts\Advert\Advert;
use App\Models\Region;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::model('adverts_path', AdvertsPath::class);
//        Route::model('page_path', AdvertsPath::class);


    }
}
