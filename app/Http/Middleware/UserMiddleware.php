<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'You do not have access.');
        }

        if (auth()->user()->email_verified_at == null) {
            auth()->logout();
            return redirect()->back()->with('error', 'Please verify your email address.');
        }

        $user = auth()->user();

        // Regular verified user
        if ($user->isUser()) {
            return $next($request);
        }

        // Admin or SuperAdmin
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'You do not have access.');
    }
}
