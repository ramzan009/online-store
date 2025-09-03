<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home()
    {
        return [
            'name' => 'Board API'
        ];
    }
}
