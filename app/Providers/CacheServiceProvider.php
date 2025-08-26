<?php

namespace App\Providers;

use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    private $classes = [
        Region::class,
        Category::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->classes as $class) {
            $this->registerFlusher($class);
        }
    }

    public function registerFlusher($class): void
    {
        $flush = function () use ($class) {
            Cache::tags($class)->flush();
        };

        $class::created($flush);
        $class::updated($flush);
        $class::saved($flush);
        $class::deleted($flush);
    }
}
