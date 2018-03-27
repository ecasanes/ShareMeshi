<?php

use App\ShareMeshi\Services\CompanyService;
use App\ShareMeshi\Services\RoleService;
use App\ShareMeshi\Services\UserService;
use App\Role;
use App\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    protected $userService;
    protected $companyService;
    protected $roleService;
    protected $password;

    public function __construct(UserService $userService, CompanyService $companyService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->roleService = $roleService;

        $this->password = Hash::make('testing');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();

        $this->createCompanyManager();
        $this->createCompanyStaffBoth();
        $this->createCompanyStaffInventory();
        $this->createCompanyStaffSales();

        $this->createBranchManager();

        $this->createStaff();

        $this->createMember();

        $this->createGuest();

    }

    private function createAdmin()
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@email.com',
            'firstname' => 'Admin',
            'lastname' => 'Istrator',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('admin');

        if ($adminRole) {
            $this->userService->createUserRole($admin->id, $adminRole->id);
        }
    }

    private function createCompanyManager()
    {
        $admin = User::firstOrCreate([
            'email' => 'company@email.com',
            'firstname' => 'Company',
            'lastname' => 'Manager',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('company');

        if ($adminRole) {
            $this->userService->createUserRole($admin->id, $adminRole->id);
        }
    }

    private function createCompanyStaffBoth()
    {
        $companyStaffBoth = User::firstOrCreate([
            'email' => 'company.staff@email.com',
            'firstname' => 'Company',
            'lastname' => 'Staff',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('company_staff');

        if ($adminRole) {
            $this->userService->createUserRole($companyStaffBoth->id, $adminRole->id);
        }

        $this->userService->createUserPermissionsByCode($companyStaffBoth->id, ['sales', 'inventory']);
    }

    private function createCompanyStaffInventory()
    {
        $companyStaffBoth = User::firstOrCreate([
            'email' => 'company.staff.inventory@email.com',
            'firstname' => 'Company',
            'lastname' => 'Staff Inventory',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('company_staff');

        if ($adminRole) {
            $this->userService->createUserRole($companyStaffBoth->id, $adminRole->id);
        }

        $this->userService->createUserPermissionByCode($companyStaffBoth->id, 'inventory');
    }

    private function createCompanyStaffSales()
    {
        $companyStaffBoth = User::firstOrCreate([
            'email' => 'company.staff.sales@email.com',
            'firstname' => 'Company',
            'lastname' => 'Staff Sales',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('company_staff');

        if ($adminRole) {
            $this->userService->createUserRole($companyStaffBoth->id, $adminRole->id);
        }

        $this->userService->createUserPermissionByCode($companyStaffBoth->id, 'sales');
    }

    private function createBranchManager()
    {
        $branchManager = User::firstOrCreate([
            'email' => 'branch@email.com',
            'firstname' => 'Branch',
            'lastname' => 'Manager',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('branch');

        if (!$adminRole) {
            return false;
        }

        $this->userService->createUserRole($branchManager->id, $adminRole->id);
        $branch = $this->companyService->findBranchByKey(11111111);

        if (!$branch) {
            return false;
        }

        $branchId = $branch->id;

        \App\BranchStaff::create([
            'branch_id' => $branchId,
            'can_void' => 1,
            'user_id' => $branchManager->id,
            'staff_id' => 11111111
        ]);

    }

    private function createStaff()
    {
        $staffOrdinary = User::firstOrCreate([
            'email' => 'staff@email.com',
            'firstname' => 'Staff',
            'lastname' => 'Only',
            'password' => $this->password
        ]);

        $adminRole = $this->roleService->findByCode('staff');

        if (!$adminRole) {
            return false;
        }

        $this->userService->createUserRole($staffOrdinary->id, $adminRole->id);
        $branch = $this->companyService->findBranchByKey(11111111);

        if (!$branch) {
            return false;
        }

        $branchId = $branch->id;

        \App\BranchStaff::create([
            'branch_id' => $branchId,
            'user_id' => $staffOrdinary->id,
            'staff_id' => 10000000
        ]);

    }

    private function createMember()
    {
        $member = User::firstOrCreate([
            'email' => 'member@email.com',
            'firstname' => 'Member',
            'lastname' => 'Only',
            'password' => $this->password
        ]);

        $memberRole = $this->roleService->findByCode('member');

        if ($memberRole) {
            $this->userService->createUserRole($member->id, $memberRole->id);
        }

        \App\CustomerUser::create([
            'customer_id' => 11111111,
            'user_id' => $member->id
        ]);
    }

    private function createGuest()
    {
        $guest = User::firstOrCreate([
            'email' => 'guest@email.com',
            'firstname' => 'Guest',
            'lastname' => 'Only',
            'password' => $this->password
        ]);

        $guestRole = $this->roleService->findByCode('guest');

        if ($guestRole) {
            $this->userService->createUserRole($guest->id, $guestRole->id);
        }

        \App\CustomerUser::create([
            'customer_id' => 10000000,
            'user_id' => $guest->id
        ]);
    }
}
