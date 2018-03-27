<?php

namespace App\ShareMeshi\Api\Controllers;

use App\ShareMeshi\Helpers\Rest;
use App\ShareMeshi\Helpers\StatusHelper;
use App\ShareMeshi\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        Request $request,
        UserService $userService
    )
    {
        parent::__construct($request);
        $this->userService = $userService;
    }

    public function getAll()
    {
        $payload = $this->payload;
        $data = $payload->all();

        $usersMeta = $this->userService->getFilterMeta($data);
        $users = $this->userService->filter($data);

        return Rest::success($users, $usersMeta);
    }

    public function get($id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            return Rest::notFound("User not found");
        }

        return Rest::success($user);
    }

    public function getCurrentUser()
    {
        $user = $this->user;

        return Rest::success($user);
    }

    public function getCountByFilter()
    {
        $payload = $this->payload;
        $data = $payload->all();

        $total = $this->userService->getCountByFilter($data);

        return Rest::success($total);

    }

    public function create()
    {
        $payload = $this->payload;
        $data = $payload->all();
        $branchId = null;
        $roleCode = null;
        $canVoid = 0;

        $permissions = [];
        $additionalValidations = [];

        if (isset($data['role'])) {
            $roleCode = $data['role'];
        }

        if (isset($data['can_void'])) {
            $canVoid = $data['can_void'];
        }

        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        if (isset($data['branch_id'])) {
            $branchId = $data['branch_id'];
        }

        # default password
        $password = "testing";

        if ($roleCode == StatusHelper::STAFF && $canVoid == 1) {

            $password = $payload->password;

            $additionalValidations = [
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ];
        }

        if ($roleCode == StatusHelper::MEMBER) {
            $additionalValidations = [
                'customer_id' => 'required|unique:customer_users|digits:16|numeric',
                'phone' => 'required_without:email|nullable|unique:users|digits:11|numeric',
                'email' => 'required_without:phone|email|nullable|unique:users'
            ];
        }

        $validator = $this->validator($data, [
                // 'email' => 'required|email|unique:users',
                'firstname' => 'required',
                'lastname' => 'required',
                'role' => 'required',
                'branch_id' => 'required'
            ] + $additionalValidations,
            [
                'customer_id.required' => 'The customer\'s ID is required',
                'customer_id.unique' => 'The customer\'s ID is already used',
                'customer_id.digits' => 'The customer\'s ID should only be 16 digits',
                'firstname.required' => 'The customer\'s firstname is required',
                'lastname.required' => 'The customer\'s lastname is requied',
                'branch_id.required' => 'Please select a branch',
                'phone.required_without' => 'The customer\'s phone is required if there is no email',
                'email.required_without' => 'The customer\'s email is required if there is no phone',
                'phone.unique' => 'The phone is already used',
                'email_unique' => 'The email is already used'
            ]);

        if ($validator->fails()) {
            return Rest::validationFailed($validator);
        }

        $roleCode = $data['role'];
        $role = $this->roleService->findByCode($roleCode);

        if (!$role) {
            return Rest::failed("Invalid role!");
        }

        if (!is_array($permissions)) {
            return Rest::failed("Please input correct permission field format");
        }

        if ($permissions == "") {
            return Rest::failed("Please input correct permission field format");
        }

        if ($roleCode == StatusHelper::COMPANY_STAFF && empty($permissions)) {
            return Rest::failed("Please select at least one permission for this user type");
        }

        $password = Hash::make($password);
        $data['password'] = $password;

        $user = $this->userService->create($data);

        if (!$user) {
            return Rest::failed("Something went wrong while creating new user");
        }

        $userId = $user->id;
        $this->userService->createUserPermissionsByCode($userId, $permissions);

        if ($branchId && ($roleCode == StatusHelper::STAFF || $roleCode == StatusHelper::BRANCH)) {

            $staffId = $this->userService->generateStaffId($userId, $branchId);

            $this->companyService->updateStaff($staffId, [
                'can_void' => $canVoid,
                'has_multiple_access' => $data['has_multiple_access']
            ]);

            $this->userService->update($userId, [
                'branch_id_registered' => $branchId
            ]);
        }

        if ($roleCode == StatusHelper::MEMBER || $roleCode == StatusHelper::GUEST) {
            $customerId = $payload->customer_id;
            $this->userService->updateCustomerId($userId, $customerId);
        }

        $user = $this->userService->find($userId);

        return Rest::success($user);

    }

    public function update($id)
    {
        $payload = $this->payload;
        $data = $payload->all();
        $permissions = [];
        $additionalValidations = [];
        $roleCode = null;
        $canVoid = null;

        $password = "";

        $user = $this->userService->find($id);

        // if the user account is not found
        if (!$user) {
            return Rest::notFound("User not found.");
        }

        if (isset($data['role'])) {

            $roleCode = $data['role'];

            if ($roleCode == StatusHelper::STAFF && isset($data['can_void']) && $data['can_void'] == 1) {
                $canVoid = true;
            } 
        }

        if (isset($data['password'])) {
            $password = $data['password'];
        }
        
        if ($password == "" || empty($password)) {
            unset($data['password']);
        }

        $existingUserRole = $user->role;

        if ($existingUserRole == StatusHelper::MEMBER) {

            $userId = $user->id;
            $customerUserId = $user->customer_user_id;

            $additionalValidations = [
                'customer_id' => 'required|digits:16|numeric|unique:customer_users,customer_id,'.$customerUserId,
                'phone' => 'required_without:email|nullable|digits:11|numeric|unique:users,phone,'.$userId,
                'email' => 'required_without:phone|email|nullable|unique:users,email,'.$userId,
                'branch_id' => 'required'
            ];
        }

        if ($existingUserRole == StatusHelper::STAFF && $canVoid) {

            $email = $user->email;

            $additionalValidations = [
                'email' => 'required|email|unique:users,email' . $email,
                'password' => 'required',
                'branch_id' => 'required'
            ];
        }

        $validator = $this->validator($data, [
            'firstname' => 'required',
            'lastname' => 'required'
        ] + $additionalValidations,
        [
            'customer_id.required' => 'The customer\'s ID is required',
            'customer_id.unique' => 'The customer\'s ID is already used',
            'customer_id.digits' => 'The customer\'s ID should only be 16 digits',
            'firstname.required' => 'The customer\'s firstname is required',
            'lastname.required' => 'The customer\'s lastname is requied',
            'branch_id.required' => 'Please select a branch',
            'phone.required_without' => 'The customer\'s phone is required if there is no email',
            'email.required_without' => 'The customer\'s email is required if there is no phone',
            'phone.unique' => 'The phone is already used',
            'email_unique' => 'The email is already used'
        ]);

        if ($validator->fails()) {
            return Rest::validationFailed($validator);
        }

        $isDeleted = $this->userService->isDeleted($id);

        if ($isDeleted) {
            return Rest::notFound("User is not active. Please contact the administrator.");
        }

        if (isset($data['role'])) {
            $roleCode = $data['role'];
            $role = $this->roleService->findByCode($roleCode);

            if (!$role) {
                return Rest::failed("Invalid role!");
            }
        }

        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        if (!is_array($permissions)) {
            return Rest::failed("Please input correct permission field format");
        }

        if ($permissions == "") {
            return Rest::failed("Please input correct permission field format");
        }

        $updated = $this->userService->update($id, $data);

        $this->userService->updateUserPermissionsByCode($id, $permissions);

        if ($existingUserRole == StatusHelper::MEMBER || $existingUserRole == StatusHelper::GUEST) {
            $customerId = $payload->customer_id;
            $this->userService->updateCustomerId($id, $customerId);
        }


        $user = $this->userService->find($id);

        return Rest::updateSuccess($updated, $user);
    }

    public function delete($id)
    {
        $deleted = $this->userService->delete($id);

        return Rest::deleteSuccess($deleted);
    }

    public function login()
    {
        $payload = $this->payload;

        $token = $payload->attributes->get('token');

        return Rest::successToken($token);
    }

    public function refreshToken()
    {
        $payload = $this->payload;

        $token = $payload->attributes->get('token');

        return Rest::successToken($token);
    }

    public function getUsersByIdRange()
    {

        $payload = $this->payload->all();

        $id = $payload['from'];

        $users = $this->userService->getUsersByIdRange($id);

        return Rest::success($users);
    }

}
