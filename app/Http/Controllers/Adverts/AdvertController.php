<?php

namespace App\Http\Controllers\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Router\AdvertsPath;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\UseCases\Adverts\SearchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdvertController extends Controller
{
    private $search;

    public function __construct(SearchService $search)
    {
        $this->service = $search;
    }

    public function index(SearchRequest $request, AdvertsPath $path)
    {
        $region = $path->region;
        $category = $path->category;

        $result = $this->search->search($category, $region, $request, 20, $request->get('page', 1));

        $adverts = $result->adverts;
        $regionsCounts = $result->regionsCounts;
        $categoriesCounts = $result->categoriesCounts;

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defaultOrder()->getModels()
            : Category::wheteIsRoot()->defaultOrder()->getModels();

        $regions = array_filter($regions, function (Region $region) use ($regionsCounts) {
            return isset($regionsCounts[$region->id]) && $regionsCounts[$region->id] > 0;
        });

        $categories = array_filter($categories, function (Category $category) use ($categoriesCounts) {
            return isset($categoriesCounts[$category->id]) && $categoriesCounts[$category->id] > 0;
        });

        return view('adverts.index', compact('region', 'category', 'adverts', 'categories', 'regions', 'regionsCounts', 'categoriesCounts'));
    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(404);
        }

        $user = Auth::user();

        return view('adverts.show', compact('advert', 'user'));
    }

    public function phone(Advert $advert): array
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }
        return $advert->user->phone;
    }

}
