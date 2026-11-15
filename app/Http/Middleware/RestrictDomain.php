<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass check in local environment
        if (app()->environment('local')) {
            return $next($request);
        }

        $allowedDomain = config('app.allowed_api_domain', 'api.bongonews24.com');

        if ($request->getHost() !== $allowedDomain) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized domain access.'
            ], 403);
        }

        return $next($request);
    }
}
