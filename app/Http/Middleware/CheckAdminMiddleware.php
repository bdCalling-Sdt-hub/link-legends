<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware
{

//    public function handle(Request $request, Closure $next): Response
//    {
//        try {
//            $user = auth()->userOrFail();
//            if ($user->userType == 'ADMIN') {
//                return $next($request);
//            }
//            return response()->json([
//                'message' => 'Unauthorized user'
//            ], 401);
//        } catch (AuthenticationException $exception) {
//            return response()->json([
//                'message' => 'Unauthorized: ' . $exception->getMessage()
//            ], 401);
//        }
//    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = auth()->user();
            if ($user && $user->userType == 'ADMIN') {
                return $next($request);
            }
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        } catch (AuthenticationException $exception) {
            return response()->json([
                'message' => 'Unauthorized: ' . $exception->getMessage()
            ], 401);
        }
    }


//    public function handle(Request $request, Closure $next): Response
//    {
//        // Check if the Authorization header with Bearer token is present
//        if (!$request->bearerToken()) {
//            return response()->json([
//                'message' => 'Unauthorized. Bearer token is missing.'
//            ], 401);
//        }
//
//        try {
//            $user = auth()->userOrFail();
//            if ($user->userType == 'ADMIN') {
//                return $next($request);
//            }
//            return response()->json([
//                'message' => 'Unauthorized user'
//            ], 401);
//        } catch (AuthenticationException $exception) {
//            return response()->json([
//                'message' => 'Unauthorized: ' . $exception->getMessage()
//            ], 401);
//        }
//    }

}
