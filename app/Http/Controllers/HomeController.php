<?php

namespace App\Http\Controllers;

use App\Models\Adverts\Category;
use App\Models\Region;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    /**
     * Главная страница
     *
     * @return Factory|View|Application|object
     */
    public function index(Category $category)
    {
        $regions = Region::roots()->orderBy('name')->getModels();

        $categories = Category::whereIsRoot()->defaultOrder()->getModels();

        return view('home', compact('regions', 'categories', 'category'));
    }
}
