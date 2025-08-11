<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cabinet\Profile\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
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
        $data = $request->validated();
        $user = Auth::user();

        $oldPhone = $user->phone;
        $user->update([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
        ]);
        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }

        return redirect()->route('cabinet.profile.home');
    }
}
