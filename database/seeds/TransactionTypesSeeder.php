<?php

use App\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionType::firstOrCreate([
            'name' => 'Add Stocks',
            'code' => 'add_stock',
            'group' => 'inventory'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Subtract Stocks',
            'code' => 'sub_stock',
            'group' => 'inventory'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Deliver Stocks',
            'code' => 'deliver_stock',
            'group' => 'inventory'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Return Stocks',
            'code' => 'return_stock',
            'group' => 'inventory'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Request Stocks',
            'code' => 'request_stock',
            'group' => 'inventory'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Sale',
            'code' => 'sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Return Sale',
            'code' => 'return_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Sale',
            'code' => 'void_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Short Sale',
            'code' => 'short_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Shortover Sale',
            'code' => 'shortover_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Return Sale',
            'code' => 'void_return_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Short Sale',
            'code' => 'void_short_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Shortover Sale',
            'code' => 'void_shortover_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Short',
            'code' => 'adjustment_short',
            'group' => 'adjustment'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Short/Over',
            'code' => 'adjustment_shortover',
            'group' => 'adjustment'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Short',
            'code' => 'void_adjustment_short',
            'group' => 'adjustment'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Short/Over',
            'code' => 'void_adjustment_shortover',
            'group' => 'adjustment'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Delivery Sale',
            'code' => 'delivery_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Franchisee Sale',
            'code' => 'franchisee_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Delivery Sale',
            'code' => 'void_delivery_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Franchisee Sale',
            'code' => 'void_franchisee_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Return Delivery Sale',
            'code' => 'return_delivery_sale',
            'group' => 'sale'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Redeem',
            'code' => 'redeem',
            'group' => 'rewards'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Redeem',
            'code' => 'void_redeem',
            'group' => 'rewards'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Positive Adjustment',
            'code' => 'positive_adjustment',
            'group' => 'adjustment'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Negative Adjustment',
            'code' => 'negative_adjustment',
            'group' => 'adjustment'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Return Redeem',
            'code' => 'return_redeem',
            'group' => 'rewards'
        ]);

        TransactionType::firstOrCreate([
            'name' => 'Void Return Redeem',
            'code' => 'void_return_redeem',
            'group' => 'rewards'
        ]);
    }
}
