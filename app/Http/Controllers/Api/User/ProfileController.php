<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cabinet\Profile\ProfileUpdateRequest;
use App\Models\User;
use App\UseCases\Profile\ProfileService;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show(\Request $request)
    {
        return $request->user();
    }

    public function update(ProfileUpdateRequest $request)
    {
        $this->service->edit($request->user(), $request);

        return User::query()->findOrFail($request->user()->id);
    }

}
