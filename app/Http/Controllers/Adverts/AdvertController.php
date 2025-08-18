<?php

namespace App\Http\Controllers\Adverts;

use App\Http\Controllers\Controller;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::active()->with(['category', 'region'])->orderByDesc('published_at');

        if ($category) {
            $query->forCatagory($category);
        }
        if ($region) {
            $query->forRegion($region);
        }

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defaultOrder()->getModels()
            : Category::wheteIsRoot()->defaultOrder()->getModels();

        $adverts = $query->paginate(20);

        return view('adverts.index', compact('region', 'category', 'adverts', 'categories', 'regions'));
    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(404);
        }

        return view('adverts.show', compact('advert'));
    }

    public function phone(Advert $advert): array
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }
        return $advert->user->phone;
    }

}
