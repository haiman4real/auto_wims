<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the Authorization header contains a Bearer token
        $bearerToken = $request->bearerToken();
        if (!$bearerToken) {
            return response()->json([
                'message' => 'Bearer token not provided.'
            ], 401);
        }

        // Attempt to authenticate the user using the api guard
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'Token is invalid or expired.'
            ], 401);
        }

        // If using Passport, check explicitly if the token is expired.
        // This assumes that the authenticated user has a `token()` method.
        if (method_exists($user, 'token')) {
            $token = $user->token();
            if ($token && $token->expires_at && Carbon::parse($token->expires_at)->isPast()) {
                return response()->json([
                    'message' => 'Token has expired.'
                ], 401);
            }
        }

        return $next($request);
    }
}
