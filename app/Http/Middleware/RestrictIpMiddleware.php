<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\AllowedIp;

class RestrictIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the current environment
        $currentEnvironment = app()->environment();
        Log::info('env - ' . $currentEnvironment);

        // Fetch allowed IPs for the current environment
        $allowedIps = AllowedIp::where('environment', $currentEnvironment)
            ->where('status', 'allowed')
            ->pluck('ip_address')
            ->toArray();

        // Log the allowed IPs for debugging
        Log::info('Allowed IPs:', $allowedIps);

        // Check if the request IP is in the allowed list
        if (!in_array($request->ip(), $allowedIps)) {
            Log::warning('Access denied for IP: ' . $request->ip());
            abort(403, 'Access denied. Please contact Admin for IP whitelisting');
        }


        return $next($request);
    }
}
