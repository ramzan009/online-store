<?php

use App\Http\Router\AdvertsPath;
use App\Models\Region;

if (!function_exists('adverts_path')) {
    function adverts_path(?Region $region, ?\App\Models\Adverts\Category $category) //может быть объектом класса Region, либо null.
    {
        return app()->make(AdvertsPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}
