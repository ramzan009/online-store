<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cabinet\Profile\ProfileUpdateRequest;
use App\UseCases\Profile\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $user = Auth::user();

        return view('cabinet.profile.home', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('cabinet.profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            $this->service->edit(Auth::id(), $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.profile.home');
    }
}
