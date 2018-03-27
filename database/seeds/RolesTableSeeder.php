<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            'id' => 1,
            'name' => 'Administrator',
            'rank' => 1,
            'code' => 'admin',
            'status' => 'active'
        ]);

        Role::firstOrCreate([
            'id' => 2,
            'name' => 'Company Manager',
            'rank' => 2,
            'code' => 'company',
            'status' => 'active'
        ]);

        Role::firstOrCreate([
            'id' => 3,
            'name' => 'Company Staff',
            'rank' => 3,
            'code' => 'company_staff',
            'status' => 'active',
            'has_permissions' => 1
        ]);

        Role::firstOrCreate([
            'id' => 4,
            'name' => 'Branch Manager',
            'rank' => 4,
            'code' => 'branch',
            'status' => 'active'
        ]);

        Role::firstOrCreate([
            'id' => 5,
            'name' => 'Staff',
            'rank' => 5,
            'code' => 'staff',
            'status' => 'active'
        ]);

        Role::firstOrCreate([
            'id' => 6,
            'name' => 'Member',
            'rank' => 6,
            'code' => 'member',
            'status' => 'active'
        ]);

        Role::firstOrCreate([
            'id' => 7,
            'name' => 'Guest',
            'rank' => 7,
            'code' => 'guest',
            'status' => 'active'
        ]);

        Role::firstOrCreate([
            'id' => 8,
            'name' => 'Company Coordinator',
            'rank' => 8,
            'code' => 'company_coordinator',
            'status' => 'active'
        ]);
    }
}
