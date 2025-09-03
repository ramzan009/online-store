<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\UseCases\Auth\NetworksService;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class NetworkController extends Controller
{
    private $service;

    public function __construct(NetworksService $service)
    {
        $this->service = $service;
    }

    public function redirect(string $network)
    {
        return Socialite::driver($network)->redirect();
    }

    public function callback(string $network)
    {
        $data = Socialite::driver($network)->user();

        try {
            $user = $this->service->auth('twitter', $data);
            Auth::login($user);
            return redirect()->intended();
        } catch (DomainException $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
