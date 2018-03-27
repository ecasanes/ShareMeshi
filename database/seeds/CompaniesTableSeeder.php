<?php

use App\Branch;
use App\Company;
use App\Permission;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::firstOrCreate([
            'name' => 'Augustus',
            'default_metrics' => 'ml',
            'default_color' => 'red'
        ]);

        Branch::create([
            'name' => 'Centrio Branch',
            'override_default_store_time' => 1,
            'default_start_time' => '10:00:00',
            'default_end_time' => '21:00:00',
            'company_id' => $company->id,
            'key' => 11111111
        ]);

        Branch::create([
            'name' => 'Limketkai Branch',
            'override_default_store_time' => 1,
            'default_start_time' => '10:00:00',
            'default_end_time' => '20:00:00',
            'company_id' => $company->id,
            'key' => 22222222
        ]);
    }
}
