<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoporateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log the current route and user
        Log::info('Route: ' . $request->url());
        Log::info('User: ' . (Auth::check() ? Auth::user()->email : 'Guest'));

        // Check if the user does not have the MasterAdmin role
        if (Auth::check() && !in_array(strtolower(trim(Auth::user()->user_role)), ['coporateuser', 'superadmin', 'masteradmin'])) {
            Log::info('User does not have access to Coporate User routes.');
            return abort(403, 'You do not have access to the Coporate User routes');
        }

        return $next($request);
    }
}
