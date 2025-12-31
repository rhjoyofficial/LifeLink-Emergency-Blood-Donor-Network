<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->is_verified) {
            abort(403, 'Account not verified by admin.');
        }

        return $next($request);
    }
}
