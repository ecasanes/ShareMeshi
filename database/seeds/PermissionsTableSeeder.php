<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'User Management',
                'code' => 'user',
            ],
            [
                'name' => 'Sales Management',
                'code' => 'sales'
            ],
            [
                'name' => 'Inventory Management',
                'code' => 'inventory'
            ],
            [
                'name' => 'HR Management',
                'code' => 'hr'
            ]
        ];

        $counter = 1;
        foreach($permissions as $permission){

            Permission::firstOrCreate([
                'id' => $counter,
                'name' => $permission['name'],
                'code' => $permission['code']
            ]);

            $counter++;

        }
    }
}
