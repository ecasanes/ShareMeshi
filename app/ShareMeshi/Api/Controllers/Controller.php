<?php

namespace App\ShareMeshi\Api\Controllers;

use App\ShareMeshi\Services\RoleService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $payload;
    protected $user;
    protected $loggedInUserId;
    protected $roles;
    protected $staffId;

    public function __construct(Request $request)
    {
        $this->payload = $request;
        $this->loggedInUserId = null;
        $this->user = null;
        $this->roles = null;
        $this->staffId = null;

        $this->middleware(function ($request, $next){

            $this->user = $request->attributes->get('user');
            $this->loggedInUserId = $request->attributes->get('userId');
            $this->roles = $request->attributes->get('roles');
            $this->staffId = $request->attributes->get('staffId');

            return $next($request);
        });
    }

    public function validator($data, $rules, $messages = [])
    {
        $validator = Validator::make($data, $rules, $messages);

        return $validator;
    }
}
