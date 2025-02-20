<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class JobController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if(Auth::user()->role !== "JobController"){
		// 	return abort('403', 'You do not have access to the Event Admin routes');
		// }
        // return $next($request);
        // Log the current route and user
        Log::info('Route: ' . $request->url());
        Log::info('User: ' . (Auth::check() ? Auth::user()->email : 'Guest'));

        // return $next($request);
        // Check if the user does not have the Technician role
        if (Auth::check() && !in_array(strtolower(trim(Auth::user()->user_role)), ['jobcontroller'])) {
            Log::info('User does not have access to Job Controller routes.');
            return abort(403, 'You do not have access to the Job Controller routes');
        }

        return $next($request);
    }
}
