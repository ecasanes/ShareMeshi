<?php

use Illuminate\Database\Seeder;
use App\ActivityLogType as Type;

class ActivityLogTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Add an array here for activity log types
        $activityLogTypes = [
            [
                'name' => 'Login',
                'code' => 'login',
                'description' => 'When a user logs in',
                'is_transaction' => 0
            ],
            [
                'name' => 'Logout',
                'code' => 'logout',
                'description' => 'When a user logs out',
                'is_transaction' => 0
            ],
            [
                'name' => 'Request Stock',
                'code' => 'request_stock',
                'description' => 'When a stock is requested',
                'is_transaction' => 1
            ],
            [
                'name' => 'Deliver Stock',
                'code' => 'deliver_stock',
                'description' => 'When a stock is delivered',
                'is_transaction' => 1
            ],
            [
                'name' => 'Return Stocks',
                'code' => 'return_stock',
                'description' => 'When a stock is returned',
                'is_transaction' => 1
            ],
            [
                'name' => 'Restock Product',
                'code' => 'restock_product',
                'description' => 'When a product is restocked',
                'is_transaction' => 1
            ],
            [
                'name' => 'Subtract Product',
                'code' => 'subtract_product',
                'description' => 'When a product is subtracted',
                'is_transaction' => 1
            ],
            [
                'name' => 'Add Pending Delivery',
                'code' => 'add_pending_delivery',
                'description' => 'When a delivery request is made',
                'is_transaction' => 0
            ],
            [
                'name' => 'Confirm Pending Delivery',
                'code' => 'confirm_pending_delivery',
                'description' => 'When a pending delivery is approved',
                'is_transaction' => 0
            ],
            [
                'name' => 'Void Pending Delivery',
                'code' => 'void_pending_delivery',
                'description' => 'When a pending delivery is made void',
                'is_transaction' => 0
            ],
            [
                'name' => 'Return Pending Delivery',
                'code' => 'return_pending_delivery',
                'description' => 'When a pending delivery is returned',
                'is_transaction' => 0
            ],
            [
                'name' => 'Create Sale Transaction',
                'code' => 'add_sale',
                'description' => 'When a purchase is made',
                'is_transaction' => 1
            ],
            [
                'name' => 'Void Sale Transaction',
                'code' => 'void_sale',
                'description' => 'When a purchase is made void',
                'is_transaction' => 1
            ],
            [
                'name' => 'Void Return Sale Transaction',
                'code' => 'void_return_sale',
                'description' => 'When a return is made void',
                'is_transaction' => 1
            ],
            [
                'name' => 'Void Short Sale Transaction',
                'code' => 'void_short_sale',
                'description' => 'When a short sale is made void',
                'is_transaction' => 1
            ],
            [
                'name' => 'Void Shortover Sale Transaction',
                'code' => 'void_shortover_sale',
                'description' => 'When a shortover sale is made void',
                'is_transaction' => 1
            ],
            [
                'name' => 'Return Sale Transaction',
                'code' => 'return_sale',
                'description' => 'When a purchased product is returned',
                'is_transaction' => 1
            ],
            [
                'name' => 'Shortover Sale Transaction',
                'code' => 'shortover_sale',
                'description' => 'When there is a deficit and a surplus during the transaction',
                'is_transaction' => 1
            ],
            [
                'name' => 'Short Sale Transaction',
                'code' => 'short_sale',
                'description' => 'When there is a deficit in the inventory',
                'is_transaction' => 1
            ],
            [
                'name' => 'Add Branch',
                'code' => 'add_branch',
                'description' => 'When a new branch is added',
                'is_transaction' => 0
            ],
            [
                'name' => 'Update Branch',
                'code' => 'update_branch',
                'description' => 'When a branch record is modified',
                'is_transaction' => 0
            ],
            [
                'name' => 'Delete Branch',
                'code' => 'delete_branch',
                'description' => 'When a branch record is deleted',
                'is_transaction' => 0
            ],
            [
                'name' => 'Add Product',
                'code' => 'add_product',
                'description' => 'When a new product is added',
                'is_transaction' => 0
            ],
            [
                'name' => 'Update Product',
                'code' => 'update_product',
                'description' => 'When a product record is modified',
                'is_transaction' => 0
            ],
            [
                'name' => 'Delete product',
                'code' => 'delete_product',
                'description' => 'When a product record is deleted',
                'is_transaction' => 0
            ],
            [
                'name' => 'Add User',
                'code' => 'add_user',
                'description' => 'When a user is added or gets registered',
                'is_transaction' => 0
            ],
            [
                'name' => 'Update User',
                'code' => 'update_user',
                'description' => 'When a user record is modified',
                'is_transaction' => 0
            ],
            [
                'name' => 'Delete User',
                'code' => 'delete_user',
                'description' => 'When a user record is deleted',
                'is_transaction' => 0
            ],
            [
                'name' => 'Short Adjustment',
                'code' => 'short_adjustment',
                'description' => 'Adjustment when there is a deficit in the inventory',
                'is_transaction' => 1
            ],
            [
                'name' => 'Short/Over Adjustment',
                'code' => 'shortover_adjustment',
                'description' => 'Adjustment when there is a deficit and a surplus during the transaction',
                'is_transaction' => 1
            ],
            [
                'name' => 'Void Short Adjustment',
                'code' => 'void_short_adjustment',
                'description' => 'When there is a mistake in declaring a deficit in the inventory',
                'is_transaction' => 1
            ],
            [
                'name' => 'Void Short/Over Adjustment',
                'code' => 'void_shortover_adjustment',
                'description' => 'When there is a mistake in declaring a deficit and/or surplus during the transaction',
                'is_transaction' => 1
            ],
            [
                'name' => 'Notification',
                'code' => 'notification',
                'description' => 'For notifying users of activity',
                'is_transaction' => 0
            ],
            [
                'name' => 'Inventory Low',
                'code' => 'inventory_low',
                'description' => 'Triggers when stocks are below the set threshold',
                'is_transaction' => 0
            ],
            [
                'name' => 'Sold Out',
                'code' => 'inventory_sold_out',
                'description' => 'Triggers when a stock quantity is 0',
                'is_transaction' => 0
            ]

        ];

        foreach($activityLogTypes as $type) {
            Type::firstOrCreate($type);
        }
    }

}
