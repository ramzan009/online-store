<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banners\CreateRequest;
use App\Models\Adverts\Category;
use App\Models\Banner\Banner;
use App\Models\Region;
use App\UseCases\Banners\BannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.banners.create.category', compact('categories'));
    }

    public function region(Category $category, Region $region)
    {
        $regions = Region::query()->where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.banners.create.region', compact('category', 'region', 'regions'));
    }

    public function banner(Category $category, Region $region = null)
    {
        $formats = Banner::formatList();

        return view('cabinet.banners.create.banner', compact('category', 'region', 'formats'));
    }

    public function store(CreateRequest $request, Category $category, Region $region = null)
    {
        try {
            $banner = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.banners.show', $banner);
    }
}
