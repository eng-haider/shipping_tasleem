<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class DriverAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = JWTAuth::parseToken();
            $token->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return Helper::responseError('Token is Invalid', [],401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return Helper::responseError('Token is Expired', [],401);
            }else{
                return Helper::responseError('Authorization Token not found', [],401);
            }
        }
        if(!auth('driver')->user()){
            return  Helper::responseError('Route list not Found', [],403);
        }
        // if(!auth('driver')->user()->is_active){
        //     return  Helper::responseError('Your account is not active', [],401);
        // }
        return $next($request);
    }
}
