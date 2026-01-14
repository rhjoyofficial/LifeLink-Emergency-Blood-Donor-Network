<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorProfileExistsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->donorProfile) {
            return redirect()->route('donor.profile.edit')
                ->with('error', 'You already have a donor profile.');
        }

        return $next($request);
    }
}
