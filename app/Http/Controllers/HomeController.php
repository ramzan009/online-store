<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    /**
     * Страница главная
     *
     * @return Factory|View|Application|object
     */
    public function index()
    {
        return view('home');
    }
}
