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

class JWTAuthMiddleware
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

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return Rest::failed('Token has expired', [], ['code'=>'expired'], $e->getStatusCode());
            /*// If the token is expired, then it will be refreshed and added to the headers
            try {
                $refreshed = JWTAuth::refresh();
                //$response->header('Authorization', 'Bearer ' . $refreshed);
            } catch (JWTException $e) {
                return Rest::failed('Something went wrong while refreshing token');
            }

            $user = JWTAuth::setToken($refreshed)->toUser();*/

        } catch (TokenInvalidException $e) {
            return Rest::failed('Token invalid!', [], [], $e->getStatusCode());
        } catch (JWTException $e) {
            return Rest::failed('Something went wrong while fetching token', [], [], $e->getStatusCode());
        }

        if (!$user) {
            return Rest::notFound('User not found');
        }

        $userId = $user->id;
        $this->activityService->logUserLogin($userId);

        $role = $this->userService->findRoleByUserId($userId);
        $roles = $this->userService->getRolesByUserId($userId);
        $permissions = $this->userService->getPermissionsByUserId($userId);
        $staffPrivileges = $this->userService->getStaffPrivilegesByUserId($userId);

        $request->attributes->add([
            'user' => $user,
            'userId' => $user->id,
            'role' => $role,
            'roles' => $roles,
            'permissions' => $permissions,
            'staff_privileges' => $staffPrivileges
        ]);

        return $next($request);
    }
}
