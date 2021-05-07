<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthentication extends BaseMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $message = null;

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $message = 'token_expired';
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $message = 'token_invalid';
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            $message = 'token_absent';
        } catch (Exception $e) {
            $message = 'token_not_found';
        }

        if ($message) {
            return response([
                'success' => false,
                'code' => 401,
                'contents' => [
                    'message' => $message,
                ],
            ], 401);
        }

        return $next($request);
    }
}
