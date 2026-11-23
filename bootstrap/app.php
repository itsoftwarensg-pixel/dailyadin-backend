<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\RestrictDomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirect to custom login route, if unauthenticated
        $middleware->redirectGuestsTo(fn (Request $request) => route('loginForm'));

        // Global middleware stack
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'restrict_domain' => RestrictDomain::class,
            'restrict_admin_domain' => \App\Http\Middleware\RestrictAdminDomain::class,
        ]);

        // Keep only Laravel's lightweight health check reachable during maintenance.
        // Administrative Artisan actions must never be exposed over HTTP.
        $middleware->preventRequestsDuringMaintenance(except: ['up']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Resource not found'
                ], 404);
            }
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Record not found'
                ], 404);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => config('app.debug') ? $e->getMessage() : 'An internal error occurred'
                ], 500);
            }
        });
    })->create();
