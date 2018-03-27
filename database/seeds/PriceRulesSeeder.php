<?php

use Illuminate\Database\Seeder;
use App\PriceRule;

class PriceRulesSeeder extends Seeder
{

    public function run()
    {
        # discount column is decimal
        # discount_type should ONLY be fixed or percent
        # apply_to can should ONLY be all, member, or guest

        $types = [
            'simple-discount',
            'spend-x-to-get-discount',
            'buy-x-to-get-discount',
            'no-discount'
        ];

        $priceRulesArray = [
            [
                'name' => 'Price Rule 1',
                'code' => 'price-rule-1',
                'description' => 'Only for testing. Do not use.',
                'type' => $types[0], # based on types array above (simple-discount)
                'discount' => 10.00,
                'discount_type' => 'percent',
                'apply_to' => 'all',
                'product_variation_id' => 1, # product_variation_id is based on the records created from ProductsSeeder
                'amount' => null, # should ONLY be filled IF type = spend-x-to-get-discount
                'quantity' => null # should ONLY be filled IF type = buy-x-to-get-discount
            ],
            [
                'name' => 'Price Rule 2',
                'code' => 'price-rule-2',
                'description' => 'Only for testing. Do not use.',
                'type' => $types[1], # based on types array above (spend-x-to-get-discount)
                'discount' => 20.00,
                'discount_type' => 'percent',
                'apply_to' => 'member',
                'product_variation_id' => 2, # product_variation_id is based on the records created from ProductsSeeder
                'amount' => 1000, # should ONLY be filled IF type = spend-x-to-get-discount, corresponds to pesos
                'quantity' => null # should ONLY be filled IF type = buy-x-to-get-discount, corresponds to number of items
            ],
            [
                'name' => 'Price Rule 3',
                'code' => 'price-rule-3',
                'description' => 'Only for testing. Do not use.',
                'type' => $types[2], # based on types array above (buy-x-to-get-discount)
                'discount' => 30.00,
                'discount_type' => 'percent',
                'apply_to' => 'guest',
                'product_variation_id' => 3, # product_variation_id is based on the records created from ProductsSeeder
                'amount' => null, # should ONLY be filled IF type = , corresponds to pesos
                'quantity' => 5 # should ONLY be filled IF type = , corresponds to number of items
            ],
            [
                'name' => 'Price Rule 4',
                'code' => 'price-rule-4',
                'description' => 'Only for testing. Do not use.',
                'type' => $types[0], # based on types array above (simple-discount)
                'discount' => 100.00,
                'discount_type' => 'fixed',
                'apply_to' => 'all',
                'product_variation_id' => 4, # product_variation_id is based on the records created from ProductsSeeder
                'amount' => null, # should ONLY be filled IF type = spend-x-to-get-discount
                'quantity' => null # should ONLY be filled IF type = buy-x-to-get-discount
            ],
            [
                'name' => 'Price Rule 5',
                'code' => 'price-rule-5',
                'description' => 'Only for testing. Do not use.',
                'type' => $types[1], # based on types array above (spend-x-to-get-discount)
                'discount' => 150.00,
                'discount_type' => 'fixed',
                'apply_to' => 'member',
                'product_variation_id' => 1, # product_variation_id is based on the records created from ProductsSeeder
                'amount' => 1000, # should ONLY be filled IF type = spend-x-to-get-discount, corresponds to pesos
                'quantity' => null # should ONLY be filled IF type = buy-x-to-get-discount, corresponds to number of items
            ],
            [
                'name' => 'Price Rule 6',
                'code' => 'price-rule-6',
                'description' => 'Only for testing. Do not use.',
                'type' => $types[2], # based on types array above (buy-x-to-get-discount)
                'discount' => 200.00,
                'discount_type' => 'fixed',
                'apply_to' => 'guest',
                'product_variation_id' => 2, # product_variation_id is based on the records created from ProductsSeeder
                'amount' => null, # should ONLY be filled IF type = spend-x-to-get-discount, corresponds to pesos
                'quantity' => 3 # should ONLY be filled IF type = buy-x-to-get-discount, corresponds to number of items
            ]

        ];

        foreach($priceRulesArray as $rule) {

            PriceRule::firstOrCreate($rule);

        }
    }

}