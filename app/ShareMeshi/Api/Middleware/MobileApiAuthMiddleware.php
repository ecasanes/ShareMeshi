<?php

namespace App\ShareMeshi\Api\Middleware;

use App\ShareMeshi\Helpers\Rest;
use App\ShareMeshi\Helpers\StatusHelper;
use App\ShareMeshi\Services\RoleService;
use App\ShareMeshi\Services\UserService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class MobileApiAuthMiddleware
{

    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $baseRole = StatusHelper::STAFF;
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Rest::validationFailed($validator);
        }

        $credentials = $request->only('email','password');

        try{

            $token = JWTAuth::attempt($credentials);

        }catch(JWTException $e){
            return Rest::failed('Could not create token!');
        }

        if(!$token){
            return Rest::invalidCredentials();
        }

        //$user = JWTAuth::toUser($token);
        $user = Auth::user();

        if(!$user){
            return Rest::invalidCredentials();
        }

        if($user->status != 'active'){
            return Rest::failed('User no longer active. Please contact the administrator');
        }

        $userId = $user->id;

        $role = $this->userService->findRoleByUserId($userId);
        $acceptedRoles = $this->roleService->getHighRankingRoles($baseRole);

        if(!in_array($role,$acceptedRoles)){
            return Rest::failed("Permission denied!");
        }

        $permissions = $this->userService->getPermissionsByUserId($userId);
        $staffPrivileges = $this->userService->getStaffPrivilegesByUserId($userId);

        $token = JWTAuth::fromUser($user, [
            'role' => $role,
            'permissions' => $permissions,
            'staff_privileges' => $staffPrivileges
        ]);

        $request->attributes->add([
            'user' => $user,
            'token' => $token
        ]);

        return $next($request);
    }
}
