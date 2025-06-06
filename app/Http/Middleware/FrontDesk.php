<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FrontDesk
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
       if (Auth::check() && !in_array(strtolower(trim(Auth::user()->user_role)), ['frontdesk', 'superadmin', 'masteradmin'])) {
           Log::info('User does not have access to Master Admin routes.');
           return abort(403, 'You do not have access to the Master Admin routes');
       }

       return $next($request);
    }
}
