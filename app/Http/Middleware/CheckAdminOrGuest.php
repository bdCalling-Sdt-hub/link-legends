<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrGuest
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if ($request->user() && $request->user()->userType == 'ADMIN') {
            return $next($request);
        }

        // Allow access to public routes for unauthenticated users
        if (! $request->user()) {
            return $next($request);
        }

        // For other routes, return Unauthorized
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
