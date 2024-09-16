<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
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
        if (!Auth::check()) {
            // Redirect to login page if not authenticated
            return redirect()->route('login'); // You can modify 'login' with the actual route name of your login page
        }

        // Check if the logged-in user's role is not MasterAdmin
        if (Auth::user()->user_role !== 'MasterAdmin') {
            return abort(403, 'You do not have access to the Master Admin routes');
        }

        return $next($request);
        
    }
}
