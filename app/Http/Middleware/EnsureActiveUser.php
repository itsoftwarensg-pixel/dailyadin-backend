<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class EnsureActiveUser { public function handle(Request $request, Closure $next): Response { $user = $request->user(); if (!$user || (int) $user->status !== 1) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('loginForm')->with('error', 'Your account is inactive.'); } return $next($request); } }
