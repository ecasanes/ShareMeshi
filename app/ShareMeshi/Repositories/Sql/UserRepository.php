<?php namespace App\ShareMeshi\Repositories;

use App\CustomerUser;
use App\ShareMeshi\Helpers\SqlHelper;
use App\ShareMeshi\Helpers\StatusHelper;
use App\Permission;
use App\User;
use App\UserPermission;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserRepository implements UserInterface
{
    protected $result = [
        'customer_id' => '',
        'firstname' => '',
        'lastname' => '',
        'points' => '',
        'updated_at' => ''
    ];

    public function create(array $data)
    {
        try {
            $user = User::create($data);
        } catch (Exception $e) {
            return false;
        }

        return $user;
    }

    public function find($id)
    {
        $user = User::where('users.id', $id)
            ->leftJoin('branch_staffs', 'branch_staffs.user_id', '=', 'users.id')
            ->leftJoin('customer_users', 'customer_users.user_id', '=', 'users.id')
            ->leftJoin('branches', 'branches.id', '=', 'branch_staffs.branch_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            ->select(
                'users.*',
                'branch_staffs.staff_id',
                'customer_users.id as customer_user_id',
                'customer_users.customer_id',
                'customer_users.is_new_member',
                'customer_users.reward_points',
                'branches.name as branch_name',
                'branches.id as branch_id',
                'roles.name as role_name'
            )
            ->first();

        return $user;
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email='{$email}'";

        $user = DB::select($query);

        return $user;
    }

    public function getCompanyManagerPermissionByUserId($id)
    {
        $allPermissions = Permission::where('code','!=','user')->get();
        $userPermissions = UserPermission::where('user_id', $id)->get();

        $numOfPermissions = count($userPermissions);

        if ($numOfPermissions > 1) {
            foreach ($allPermissions as $key => $permission) {
                $permissions[$key]['code'] = $permission->code;
                $permissions[$key]['label'] = $permission->name;
                $permissions[$key]['isChecked'] = true;
            }
        }

        if ($numOfPermissions == 1) {
            foreach ($allPermissions as $key => $permission) {
                $permissions[$key]['code'] = $permission->code;
                $permissions[$key]['label'] = $permission->name;
                if ($userPermissions[0]->permission_id == $permission->id) {
                    $permissions[$key]['isChecked'] = true;
                } else {
                    $permissions[$key]['isChecked'] = false;
                }
            }
        }

        return $permissions;
    }

    public function getAll()
    {
        $users = User::whereIn('status', [StatusHelper::ACTIVE])->get();

        return $users;
    }

    public function getCountAll()
    {
        $count = User::whereIn('status', [StatusHelper::ACTIVE])->count();

        return $count;
    }

    public function getCountByFilter(array $filter = [])
    {
        $activeFlag = StatusHelper::ACTIVE;
        $additionalSqlFilters = $this->getAdditionalSqlFilters($filter);

        $sql = "SELECT 
                    count(*) as users_count
                  FROM users 
                    INNER JOIN user_roles ON user_roles.`user_id` = users.`id`
                    INNER JOIN roles ON roles.`id` = user_roles.`role_id`
                    LEFT JOIN branch_staffs ON branch_staffs.`user_id` = users.`id`
                    LEFT JOIN customer_users ON customer_users.`user_id` = users.`id`
                  WHERE users.status = '{$activeFlag}'
                  {$additionalSqlFilters}
               ";

        $users = DB::select($sql);

        return $users[0]->users_count;
    }

    public function filter(array $filter)
    {
        $activeFlag = StatusHelper::ACTIVE;
        $saleFlag = StatusHelper::SALE;

        $additionalSqlFilters = $this->getAdditionalSqlFilters($filter);
        $paginationSql = SqlHelper::getPaginationByFilter($filter);

        $soldItemsDateRange = $this->getSoldItemsDateRange($filter);

        $sql = "SELECT 
                    users.id,
                    users.firstname,
                    users.lastname,
                    users.created_at,
                    customer_users.customer_id,
                    customer_users.reward_points,
                    customer_users.is_new_member,
                    branch_staffs.staff_id,
                    (SELECT branches.name 
                        FROM branches
                        WHERE branches.`id` = users.`branch_id_registered`
                        LIMIT 1
                    ) as branch_registered,
                    branches.name as branch_name,
                    branches.id as branch_id,
                    roles.name as role_name,
                    roles.code as role_code,
                    users.email,
                    users.phone,
                    users.updated_at,
                    CASE WHEN branch_staffs.staff_id IS NOT NULL AND branch_staffs.staff_id != '' 
                    THEN (SELECT 
                          SUM(
                            transaction_items.quantity
                          ) 
                        FROM
                          transaction_items 
                          INNER JOIN transactions 
                            ON transactions.id = transaction_items.`transaction_id` 
                          INNER JOIN transaction_types 
                            ON transaction_types.id = transactions.`transaction_type_id` 
                        WHERE transactions.staff_id = branch_staffs.staff_id
                          AND transaction_items.product_name NOT IN ('membership card', 'paper bag')
                          AND transactions.status = '{$activeFlag}' 
                          AND transaction_types.code = '{$saleFlag}' 
                          {$soldItemsDateRange}
                        GROUP BY transactions.staff_id
                    )
                    ELSE 0 END as 'sold_items',
                    users.city,
                    users.province,
                    users.zip,
                    branch_staffs.can_void,
                    branch_staffs.has_multiple_access,
                    users.address,
                    users.status,
                    users.branch_id_registered,
                    (SELECT TRIM(GROUP_CONCAT(' ',permissions.name)) 
                        FROM user_permissions 
                        INNER JOIN permissions ON permissions.id = user_permissions.permission_id 
                        WHERE user_permissions.user_id = users.id
                    ) as permission_names
                  FROM users 
                    INNER JOIN user_roles ON user_roles.`user_id` = users.`id`
                    INNER JOIN roles ON roles.`id` = user_roles.`role_id`
                    LEFT JOIN branch_staffs ON branch_staffs.`user_id` = users.`id`
                    LEFT JOIN customer_users ON customer_users.`user_id` = users.`id`
                    LEFT JOIN branches ON branches.`id` = branch_staffs.`branch_id`
                  WHERE users.status = '{$activeFlag}'
                  {$additionalSqlFilters}
                  {$paginationSql}
               ";
        $users = DB::select($sql);

        return $users;
    }

    public function update($id, $data)
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $updated = $user->update($data);

        return $updated;
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        if ($user->status == StatusHelper::DELETED) {
            return true;
        }

        $deleted = $user->update([
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'status' => StatusHelper::DELETED,
            'email' => $user->email . StatusHelper::flagDelete($user->firstname . '_' . $user->lastname),
        ]);

        $this->deleteUserRoleByUserId($id);

        $this->deleteCustomerRecordByUserId($id);

        return $deleted;
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $destroyed = $user->delete();

        return $destroyed;
    }

    public function isDeleted($id)
    {
        $user = User::find($id);

        if (!$user) {
            return true;
        }

        if ($user->status != StatusHelper::DELETED) {
            return false;
        }

        return true;
    }

    public function getFilterMeta($data)
    {
        $length = $this->getCountByFilter($data);

        return [
                'length' => $length,
            ] + $data;
    }

    public function findByCustomerId($customerId)
    {
        $customerUser = CustomerUser::where('customer_users.customer_id', $customerId)->first();

        if (!$customerUser) {
            return null;
        }

        $userId = $customerUser->user_id;

        $user = $this->find($userId);

        return $user;
    }

    public function findByFirstnameAndLastname($firstname, $lastname)
    {
        $user = User::where(DB::raw("LOWER(firstname)"), strtolower($firstname))->where(DB::raw("LOWER(firstname)"), strtolower($lastname))->first();

        return $user;
    }

    private function getAdditionalSqlFilters($filter)
    {
        $searchKeySql = "";
        $branchSql = "";
        $roleSql = "";
        $staffSql = "";
        $rolesSql = "";

        $fromSql = "";
        $toSql = "";
        $orderSql = "";
        $sortSql = " ORDER BY users.`firstname` ASC";
        $privilegeSql = "";

        if (isset($filter['role'])) {
            $role = $filter['role'];
            $roleSql = " AND roles.code = '{$role}' ";
        }

        if (isset($filter['q'])) {

            $searchKey = $filter['q'];

            $searchKeySql = " AND CONCAT(
                    users.firstname,' ',
                    users.lastname,' ',
                    branch_staffs.staff_id,' '
                ) LIKE '%{$searchKey}%' ";

            if ($filter['role'] == 'company_staff') {
                $searchKeySql = " AND CONCAT(
                        users.firstname,' ',
                        users.lastname,' ',
                        users.email,' '
                    ) LIKE '%{$searchKey}%' ";
            }

            if ($filter['role'] == 'member') {
                $searchKeySql = " AND CONCAT(
                    users.firstname,' ',
                    users.lastname,' ',
                    ISNULL(users.email),' ',
                    customer_users.customer_id,' '
                ) LIKE '%{$searchKey}%' ";
            }
        }


        if(isset($filter['branch_id'])) {
            $branchId = $filter['branch_id'];
            if($filter['branch_id'] != 0) {
                $branchSql = " AND branch_staffs.branch_id = '{$branchId}' ";
            }
            if(isset($role) && $role == 'member') {
                $branchSql = " AND users.branch_id_registered = '{$branchId}'";
            }
        }

        if(isset($filter['void_privilege'])) {
            $privilege = $filter['void_privilege'];
            $privilegeSql = " AND branch_staffs.can_void = {$privilege}";
        }


        if(isset($filter['order'])) {

            $order = strtoupper($filter['order']);

            if($order == 'ASC') {
                $orderSql = 'ASC';
            }

            if($order == 'DESC' || empty($order)) {
                $orderSql = 'DESC';
            }

        }

        if(!isset($filter['order'])) {
            $orderSql = 'DESC';
        }

        if(isset($filter['sort'])) {

            $sort = $filter['sort'];

            switch ($sort) {
                case 'customer_id':
                    $sortSql = " ORDER BY customer_users.`customer_id` {$orderSql} ";
                    break;

                case 'staff_id':
                    $sortSql = " ORDER BY branch_staffs.`staff_id` {$orderSql} ";
                    break;

                case 'branch_name':
                    $sortSql = " ORDER BY branch_registered {$orderSql} ";
                    break;

                case 'sold_items':
                    $sortSql = " ORDER BY sold_items {$orderSql} ";
                    break;

                case 'lastname':
                    $sortSql = " ORDER BY users.`lastname` {$orderSql} ";
                    break;

                case 'firstname':
                    $sortSql = " ORDER BY users.`firstname` {$orderSql} ";
                    break;

                default:
                    $sortSql = " ORDER BY sold_items DESC";
            }
        }

        if (isset($filter['staff_id'])) {
            $staffId = $filter['staff_id'];
            $staffSql = " AND branch_staffs.staff_id = {$staffId} ";
        }

        if (isset($filter['roles'])) {

            $roles = explode(',', $filter['roles']);

            $rolesSql .= " AND (";
            $rolesSqlArray = [];

            foreach ($roles as $role) {
                $rolesSqlArray[] = " roles.code = '{$role}' ";
            }

            $rolesSql .= implode(" OR ", $rolesSqlArray);
            $rolesSql .= ") ";

        }

        if (isset($filter['from'])) {
            $from = $filter['from'];
            $fromSql = " AND users.`created_at` >= DATE('{$from}') ";
        }

        if (isset($filter['to'])) {
            $to = $filter['to'];
            $toSql = " AND users.`created_at` <= DATE('{$to}') ";
        }

        if (isset($filter['range'])) {

            $range = $filter['range'];
            $now = Carbon::now();

            switch ($range) {
                case 'month':
                case 'monthly':
                    $from = $now->startOfMonth()->toDateTimeString();
                    $to = $now->endOfMonth()->toDateTimeString();
                    $fromSql = " AND users.`created_at` >= DATE('{$from}') ";
                    $toSql = " AND users.`created_at` <= DATE('{$to}') ";
                    break;
                    break;
                case 'year':
                case 'yearly':
                    $from = $now->startOfYear()->toDateTimeString();
                    $to = $now->endOfYear()->toDateTimeString();
                    $fromSql = " AND users.`created_at` >= DATE('{$from}') ";
                    $toSql = " AND users.`created_at` <= DATE('{$to}') ";
                    break;
                default:
                    //defaults to month
                    $from = $now->startOfMonth()->toDateTimeString();
                    $to = $now->endOfMonth()->toDateTimeString();
                    $fromSql = " AND users.`created_at` >= DATE('{$from}') ";
                    $toSql = " AND users.`created_at` <= DATE('{$to}') ";
                    break;
            }
        }

        $additionalSql = $searchKeySql . $roleSql . $branchSql . $privilegeSql . $staffSql . $rolesSql . $fromSql . $toSql .$sortSql;

        return $additionalSql;
    }

    private function getSoldItemsDateRange($filters)
    {
        $fromQuery = "";
        $toQuery = "";
        $dateRange = $fromQuery . $toQuery;

        if(isset($filters['sold_from'])) {
            $from = $filters['sold_from'];
            $fromQuery = "AND DATE(transactions.`created_at`) >= DATE('{$from}')";
            $dateRange .= $fromQuery;
        }

        if(isset($filters['sold_to'])) {
            $to = $filters['sold_to'];
            $toQuery = " AND DATE(transactions.`created_at`) <= DATE('{$to}')";
            $dateRange .= $toQuery;
        }

        return $dateRange;
    }

    public function getFranchiseeByEmail($email)
    {
        $email = strtolower($email);

        $query = "
            SELECT 
                branches.id as branchId,
                branches.name as branchName,
                branches.address,
                branches.key,
                users.id as userId,
                users.email,
                users.firstname,
                users.lastname
            FROM users
                INNER JOIN branches ON users.`branch_id_registered` = branches.`id`
            WHERE
              users.email = '{$email}'
        ";

        $user = DB::select($query);

        return $user;
    }

    public function getUsersByIdRange($id) 
    {

        $query = "SELECT * FROM users WHERE id > {$id}" ;

        $result = DB::select($query);

        return $result;
    }

    public function syncCustomerData($tablet, $server)
    {
        $this->result['customer_id'] = $tablet['customer_id'];
        $this->result['firstname'] = $tablet['firstname'];
        $this->result['lastname'] = $tablet['lastname'];
        $this->result['email'] = $tablet['email'];

        $customerId = $tablet['customer_id'];

        $tabletDate = Carbon::parse($tablet['updated_at']);

        $serverDate = Carbon::parse($server['updated_at']);

        if ($tabletDate->gte($serverDate)) { // tablet has latest info
        
            CustomerUser::where('customer_id', $customerId)->update(['reward_points' => $tablet['points'], 'updated_at' => $tablet['updated_at']]);

            $this->result['updated_at'] = $tablet['updated_at'];

            $this->result['points'] = $tablet['points'];

        }

        if ($serverDate->gt($tabletDate)) { // server has latest info

            $this->result['updated_at'] = $serverDate->toDateTimeString();

            // if ($server['points'] > $tablet['points']) {
            //     $currentPoints = $server['points'] - $tablet['points'];
            // }

            // if ($tablet['points'] - $server['points']) {
            //     $currentPoints = $tablet['points'] - $server['points'];
            // }

            $this->result['points'] = $server['points'];

        }

        $this->checkForUpdatedCustomerData($tablet, $server);

        return $this->result;

    }

    private function checkForUpdatedCustomerData($tablet, $server)
    {

        if ($tablet['firstname'] !== $server['firstname']) {
            $this->result['firstname'] = $server['firstname'];
        }

        if ($tablet['lastname'] !== $server['lastname']) {
            $this->result['lastname'] = $server['lastname'];
        }

        if ($tablet['email'] !== $server['email']) {
            $this->result['email'] = $server['email'];
        }
    }

    // DELETE

    public function deleteUserRoleByUserId($id)
    {
        $roleToDelete = DB::table('user_roles')->where('user_id', $id)->first();

        if (!$roleToDelete) {
            return;
        }

        $role = DB::table('roles')->where('id', $roleToDelete->role_id)->first();

        if ($role->code == 'staff') {
            DB::table('branch_staffs')->where('user_id', $roleToDelete->user_id)->delete();
        }

        return $deletedRole = DB::table('user_roles')->where('user_id', $id)->delete();
    }

    public function deleteCustomerRecordByUserId($id)
    {
        return DB::table('customer_users')->where('user_id', $id)->delete();
    }

    // MASS DELETE FUNCTIONS

    public function deleteOldMembers()
    {
        $getOldUserIdsQuery = "SELECT customer_users.user_id FROM customer_users WHERE LENGTH(customer_users.customer_id) < 16";

        $oldUserIds = DB::select($getOldUserIdsQuery);

        if ($oldUserIds <= 0) {
            return false;
        }

        $deletedRoles = $this->massDeleteRoleByUserId($oldUserIds);

        $deletedCustomerUsers = $this->massDeleteCustomerByUserId($oldUserIds);

        return $deletedUsers = $this->massDeleteOldUsers($oldUserIds);

    }

    private function massDeleteRoleByUserId(array $id)
    {
        $nullUserIds = DB::table('user_roles')->whereNull('user_id')->count();

        if (count($nullUserIds) > 0) {

            $deletedNull = DB::table('user_roles')->whereNull('user_id')->delete();

        }

        $idsToDelete = array_map(function($ids) {return $ids->user_id;}, $id);

        return $deleted = DB::table('user_roles')->whereIn('user_id', $idsToDelete)->delete();

    }

    private function massDeleteOldUsers(array $id)
    {
        set_time_limit(0);

        foreach($id as $userId) {
            $this->delete($userId->user_id);
        }

        return;
        
    }
    
    private function massDeleteCustomerByUserId(array $id)
    {
        $idsToDelete = array_map(function($ids) {return $ids->user_id;}, $id);

        return $deleted = DB::table('customer_users')->whereIn('user_id', $idsToDelete)->delete();
    }

    // SPECIFIC GET

    public function getCustomersUpdatedToday()
    {
        $query = "SELECT 
                    customer_users.`customer_id`, 
                    users.`firstname`,
                    users.`lastname`,
                    users.`email`, 
                    users.`phone`,
                    customer_users.`status`, 
                    customer_users.`reward_points`,
                    customer_users.`updated_at`,
                    customer_users.`is_new_member`
                FROM customer_users
                    INNER JOIN users ON users.`id` = customer_users.`user_id`
                WHERE users.`status` = 'active' AND DATE(customer_users.`updated_at`) = CURDATE()
        ";

        $customers = DB::select($query);

        return $customers;
    }

}