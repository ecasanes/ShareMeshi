<?php

namespace App\ShareMeshi\Api\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        return $next($request)
            ->header('Access-Control-Allow-Origin', env('CORS_ORIGIN', $_SERVER['HTTP_ORIGIN'])) // you can be more specific
            //->header('Access-Control-Allow-Origin', env('CORS_ORIGIN', '*'))
            ->header('Access-Control-Allow-Methods', "GET, POST, PUT, PATCH, DELETE, OPTIONS")
            ->header('Access-Control-Allow-Headers', "Content-Type, Authorization, X-XSRF-TOKEN, X-Auth-Token, X-Requested-With, Access-Control-Allow-Origin");
    }
}
