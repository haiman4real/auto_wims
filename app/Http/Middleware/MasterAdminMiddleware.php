<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MasterAdminMiddleware
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

        // Check if the route is a public route
        // if ($request->routeIs('index', '/', 'login', 'register', 'password.request', 'password.reset')) {
        // if($request->url() === $request->route('login')){
        //     Log::info('Public route, skipping auth check.');
        //     return $next($request);
        // }

        // // Check if the user is authenticated
        // if (!Auth::check()) {
        //     Log::info('Not authenticated, redirecting to login.');
        //     Log::info($request->route('login'));
        //     return redirect()->route('login');
        // }

        // Check if the user does not have the MasterAdmin role
        if (Auth::check() && Auth::user()->user_role !== 'MasterAdmin') {
            Log::info('User does not have access to Master Admin routes.');
            return abort(403, 'You do not have access to the Master Admin routes');
        }

        return $next($request);

    }
}
