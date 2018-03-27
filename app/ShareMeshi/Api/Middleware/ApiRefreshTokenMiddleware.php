<?php

namespace App\ShareMeshi\Api\Middleware;

use App\ShareMeshi\Helpers\Rest;
use App\ShareMeshi\Services\ActivityService;
use App\ShareMeshi\Services\UserService;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiRefreshTokenMiddleware
{

    protected $userService;
    protected $activityService;

    public function __construct(UserService $roleService, ActivityService $activityService)
    {
        $this->userService = $roleService;
        $this->activityService = $activityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = null;
        $token = null;

        try {

            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::fromUser($user);

        } catch (TokenExpiredException $e) {
            // If the token is expired, then it will be refreshed and added to the headers
            try {
                $token = JWTAuth::refresh();
            } catch (JWTException $e) {
                return Rest::failed('Something went wrong while refreshing token: '. $e->getMessage());
            }

            $user = JWTAuth::setToken($token)->toUser();

        } catch (TokenInvalidException $e) {
            return Rest::failed('Token invalid!', [], [], $e->getStatusCode());
        } catch (JWTException $e) {
            return Rest::failed('Something went wrong while fetching token', [], [], $e->getStatusCode());
        }

        $request->attributes->add([
            'user' => $user,
            'token' => $token
        ]);

        return $next($request);
    }
}
