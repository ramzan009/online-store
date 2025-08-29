<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilledProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!empty($user->hasFilledProfile())) {
            return  redirect()
                ->route('cabinet.profile.home')
                ->with('error', 'Please fill your profile and verify your phone');
        }

        return $next($request);
    }
}
