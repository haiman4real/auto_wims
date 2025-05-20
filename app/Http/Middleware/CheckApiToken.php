<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Must have a Bearer token
        if (! $request->bearerToken()) {
            abort(Response::HTTP_UNAUTHORIZED, 'Bearer token not provided.');
        }

        // Guard('api') is Passport: it will throw 401 if token is invalid or expired
        if (! Auth::guard('api')->check()) {
            abort(Response::HTTP_UNAUTHORIZED, 'Token is invalid or expired.');
        }

        return $next($request);
    }
}