<?php

use Illuminate\Database\Seeder;
use App\ActivityLog as Log;
use App\ActivityLogType as LogType;
use App\User;
use App\TransactionType;

class ActivityLogsSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branchManager = User::where('email', 'branch@email.com')->first();
        $staff = User::where('email', 'staff@email.com')->first();

        # activities
        $addBranchLogType = LogType::where('code', 'add_branch')->first();
        $updateBranchLogType = LogType::where('code', 'update_branch')->first();
        $deleteBranchLogType = LogType::where('code', 'delete_branch')->first();
        $addProductLogType = LogType::where('code', 'add_product')->first();
        $updateProductLogType = LogType::where('code', 'update_product')->first();
        $deleteProductLogType = LogType::where('code', 'delete_product')->first();

        # transaction activities
        $addSaleType = LogType::where('code', 'add_sale')->first();
        $requestStockType = LogType::where('code', 'request_stock')->first();
        $restockProductType = LogType::where('code', 'restock_product')->first();
        $voidSaleType = LogType::where('code', 'void_sale')->first();
        $shortSaleType = LogType::where('code', 'short_sale')->first();
        $shortAdjustmentType = LogType::where('code', 'short_adjustment')->first();

        # transaction types
        $addSaleTransactionType = TransactionType::where('code', 'sale')->first();
        $requestStockTransactionType = TransactionType::where('code', 'request_stock')->first();
        $restockTransactionType = TransactionType::where('code', 'add_stock')->first();
        $voidSaleTransactionType = TransactionType::where('code', 'void_sale')->first();
        $shortSaleTransactionType = TransactionType::where('code', 'short_sale')->first();
        $shortAdjustmentTransactionType = TransactionType::where('code', 'adjustment_short')->first();

        $activitiesSampleArray = [
            [
                'activity_log_type_id' => $addBranchLogType->id,
                'user_id' => $branchManager->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'readable_message' => 'Sample activity log for add_branch',
                'created_at' => '2017-10-01 09:00:00'
            ],
            [
                'activity_log_type_id' => $addProductLogType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'readable_message' => 'Sample activity log for add_product',
                'created_at' => '2017-10-02 11:00:00'
            ],
            [
                'activity_log_type_id' => $updateBranchLogType->id,
                'user_id' => $branchManager->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'readable_message' => 'Sample activity log for update_branch',
                'created_at' => '2017-10-03 09:00:00'
            ],
            [
                'activity_log_type_id' => $updateProductLogType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'readable_message' => 'Sample activity log for update_product',
                'created_at' => '2017-10-04 11:00:00'
            ],
            [
                'activity_log_type_id' => $deleteProductLogType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'readable_message' => 'Sample activity log for delete_product',
                'created_at' => '2017-10-05 11:00:00'
            ],
            [
                'activity_log_type_id' => $deleteBranchLogType->id,
                'user_id' => $branchManager->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'readable_message' => 'Sample activity log for delete_branch',
                'created_at' => '2017-10-06 09:00:00'
            ]
        ];

        $transactionActivitiesSampleArray = [

            [
                'activity_log_type_id' => $addSaleType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder,
                'transaction_id' => 1, # transaction_id is based on TransactionsTestEntrySeeder
                'transaction_type_id' => $addSaleTransactionType->id, # transaction_type_id is based on TransactionsTestEntrySeeder
                'readable_message' => 'Sample transaction activity log for add_sale',
                'created_at' => '2017-10-01 09:00:00'
            ],
            [
                'activity_log_type_id' => $requestStockType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'transaction_id' => 3,
                'transaction_type_id' => $requestStockTransactionType->id,
                'readable_message' => 'Sample transaction activity log for request_stock',
                'created_at' => '2017-10-02 11:00:00'
            ],
            [
                'activity_log_type_id' => $restockProductType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'transaction_id' => 4,
                'transaction_type_id' => $restockTransactionType->id,
                'readable_message' => 'Sample transaction activity log for restock_product',
                'created_at' => '2017-10-03 09:00:00'
            ],
            [
                'activity_log_type_id' => $voidSaleType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'transaction_id' => 5,
                'transaction_type_id' => $voidSaleTransactionType->id,
                'readable_message' => 'Sample transaction activity log for void_sale',
                'created_at' => '2017-10-04 11:00:00'
            ],
            [
                'activity_log_type_id' => $shortSaleType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'transaction_id' => 6,
                'transaction_type_id' => $shortSaleTransactionType->id,
                'readable_message' => 'Sample transaction activity log for short_sale',
                'created_at' => '2017-10-05 11:00:00'
            ],
            [
                'activity_log_type_id' => $shortAdjustmentType->id,
                'user_id' => $staff->id,
                'branch_id' => 1, # branch_id is based on CompaniesTableSeeder
                'transaction_id' => 7,
                'transaction_type_id' => $shortAdjustmentTransactionType->id,
                'readable_message' => 'Sample transaction activity log for short_adjustment',
                'created_at' => '2017-10-06 09:00:00'
            ]

        ];

        foreach($activitiesSampleArray as $activity) {
            Log::firstOrCreate($activity);
        }

        foreach($transactionActivitiesSampleArray as $transaction) {
            Log::firstOrCreate($transaction);
        }
    }
}
