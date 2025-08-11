<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeCabinetController extends Controller
{
    public function index()
    {
        return view('cabinet.home');
    }
}
