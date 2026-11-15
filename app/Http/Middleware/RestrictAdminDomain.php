<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bypass for local environment
        if (app()->environment('local')) {
            return $next($request);
        }

        // Get the host from APP_URL
        $appUrl = config('app.url');
        $allowedHost = parse_url($appUrl, PHP_URL_HOST);

        if ($request->getHost() !== $allowedHost) {
            abort(403, 'Unauthorized domain access.');
        }

        return $next($request);
    }
}
