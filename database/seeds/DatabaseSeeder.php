<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);

        $this->call(CompaniesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->call(ProductsSeeder::class);
        $this->call(TransactionTypesSeeder::class);

        $this->call(ActivityLogTypesSeeder::class);

        $this->call(TransactionsTestEntrySeeder::class);

        $this->call(PriceRulesSeeder::class);
        $this->call(ActivityLogsSampleSeeder::class);
    }
}
