<?php

namespace App\Http\Middleware;

use App\Service\CheckUserAuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthUserMicroservice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // check current mode of application 
            if (!env('APP_MODE')) {
                return response()->json([
                    'message' => 'App mode not set'
                ], 500);
            }
            if (env('APP_MODE') === 'testing') {
                $request['user_id'] = 1;
                return $next($request);
            }

            // try to get jwt token from request
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
            $check = CheckUserAuthService::checkUserAuth($token);
            if (!$check) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
            $request['user_id'] = $check;

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }
    }
}
