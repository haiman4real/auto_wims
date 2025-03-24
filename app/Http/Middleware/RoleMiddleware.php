<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Trim spaces and ensure correct role format
        $roles = array_map('trim', $roles);

        // Log for debugging
        Log::info('Route: ' . $request->url());
        Log::info('User: ' . (Auth::check() ? Auth::user()->email : 'Guest'));
        Log::info('User Role: ' . (Auth::check() ? Auth::user()->user_role : 'None'));
        Log::info('Roles Allowed: ' . implode(', ', $roles));

        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if the authenticated user has the specified role(s)
        if (in_array(Auth::user()->user_role, $roles, true)) {
            return $next($request);
        }

        return abort(403, 'Unauthorized. You do not have permission to access this page. To request access, please contact your administrator.');
    }
}
