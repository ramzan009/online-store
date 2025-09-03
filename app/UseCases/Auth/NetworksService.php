<?php

namespace App\UseCases\Auth;

use App\Models\User;
use DomainException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as NetworkUser;

class NetworksService
{

    public function auth(string $network, NetworkUser $data): User
    {
        if ($user = User::byNetwork($network, $data->getId())->first()) {
            return $user;
        }

        if ($data->getEmail() && $user = User::where('email', $data->getEmail())->exists()) {
            throw new DomainException('Email address already exists.');
        }

        $user = DB::transaction(function () use ($network, $data) {
            return User::registerByNetwork($network, $data->getId());
        });

        event(new Registered($user));

        return $user;
    }

}
