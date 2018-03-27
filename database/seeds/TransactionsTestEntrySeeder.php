<?php

use App\TransactionType;
use Illuminate\Database\Seeder;

class TransactionsTestEntrySeeder extends Seeder
{

    public function run()
    {
        $saleTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000000,
            'transaction_type_id' => 6,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1
        ]);

        \App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 2,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $saleTransaction->id
        ]);

        $returnSaleTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000000,
            'transaction_type_id' => 7,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1,
            'referenced_transaction_id' => $saleTransaction->id
        ]);

        \App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 1,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $returnSaleTransaction->id
        ]);

        $requestStockTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000001,
            'transaction_type_id' => 5,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1
        ]);

        \App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 10,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $requestStockTransaction->id
        ]);

        $restockProductTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000002,
            'transaction_type_id' => 1,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1
        ]);

        \App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 5,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $restockProductTransaction->id
        ]);

        $voidSaleTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000003,
            'transaction_type_id' => 8,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1,
            'referenced_transaction_id' => $saleTransaction->id
        ]);

        \App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 1,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $voidSaleTransaction->id
        ]);

        /*$shortSaleTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000004,
            'transaction_type_id' => 9,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1,
            'referenced_transaction_id' => $saleTransaction->id
        ]);*/

        /*\App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 1,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $shortSaleTransaction->id
        ]);*/

        $shortAdjustmentTransaction = \App\Transaction::firstOrCreate([
            'or_no' => 10000005,
            'transaction_type_id' => 14,
            'staff_id' => 11111111,
            'customer_id' => 11111111,
            'customer_firstname' => 'Firstname',
            'customer_lastname' => 'Lastname',
            'staff_firstname' => 'Firstname',
            'staff_lastname' => 'Lastname',
            'branch_id' => 1,
            'referenced_transaction_id' => $saleTransaction->id
        ]);

        \App\TransactionItem::create([
            'product_variation_id' => 1,
            'quantity' => 1,
            'product_name' => 'Musk',
            'product_size' => 100,
            'product_metrics' => 'ml',
            'product_cost_price' => 50,
            'product_selling_price' => 60,
            'transaction_id' => $shortAdjustmentTransaction->id
        ]);
    }
}
