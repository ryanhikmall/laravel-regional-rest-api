<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use App\Helpers\ApiFormatter;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     * Override handle untuk menangani Exception JWT
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Cek apakah ada Authorization header
        if (!$request->header('Authorization')) {
             // Jika request ke API, return JSON error
             if ($request->is('api/*')) {
                return ApiFormatter::createJson(401, 'Authorization header not provided');
             }
             // Jika bukan API, redirect (default Laravel)
             $this->unauthenticated($request, $guards);
        }

        try {
            // Coba parse token dan authenticate
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return ApiFormatter::createJson(401, 'Token has expired');
        } catch (TokenInvalidException $e) {
            return ApiFormatter::createJson(401, 'Token is invalid');
        } catch (JWTException $e) {
            return ApiFormatter::createJson(401, 'Token could not be parsed');
        } catch (\Exception $e) {
             return ApiFormatter::createJson(401, 'Unauthorized');
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}