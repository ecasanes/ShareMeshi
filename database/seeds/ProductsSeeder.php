<?php

use App\BranchStock;
use App\CompanyStock;
use App\Product;
use App\ProductCategory;
use App\ProductVariation;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menCategory = ProductCategory::firstOrCreate([
            'id' => 1,
            'name' => 'Men',
            'code' => 'men',
            'color' => 'blue'
        ]);

        $womenCategory = ProductCategory::firstOrCreate([
            'id' => 2,
            'name' => 'Women',
            'code' => 'women',
            'color' => 'red'
        ]);

        $menCategoryId = $menCategory->id;
        $womenCategoryId = $womenCategory->id;

        $mensProduct = Product::firstOrCreate([
            'name' => 'Musk',
            'code' => 'musk',
            'product_category_id' => $menCategoryId,
            'image_url' => 'uploads/musk.jpg'
        ]);

        $womensProduct = Product::firstOrCreate([
            'name' => 'Suede',
            'code' => 'suede',
            'product_category_id' => $womenCategoryId,
            'image_url' => 'uploads/suede.jpg'
        ]);

        $mensProductId = $mensProduct->id;
        $womensProductId = $womensProduct->id;

        $mensProductVariation1 = ProductVariation::create([
            'size' => 100,
            'metrics' => 'ml',
            'cost_price' => 50,
            'selling_price' => 60,
            'product_id' => $mensProductId
        ]);

        $mensProductVariation2 = ProductVariation::create([
            'size' => 250,
            'metrics' => 'ml',
            'cost_price' => 125,
            'selling_price' => 100,
            'product_id' => $mensProductId
        ]);

        $womensProductVariation1 = ProductVariation::create([
            'size' => 100,
            'metrics' => 'ml',
            'cost_price' => 160,
            'selling_price' => 200,
            'product_id' => $womensProductId
        ]);

        $womensProductVariation2 = ProductVariation::create([
            'size' => 250,
            'metrics' => 'ml',
            'cost_price' => 200,
            'selling_price' => 300,
            'product_id' => $womensProductId
        ]);

        $productVariation1Id = $mensProductVariation1->id;
        $productVariation2Id = $mensProductVariation2->id;
        $productVariation3Id = $womensProductVariation1->id;
        $productVariation4Id = $womensProductVariation2->id;

        CompanyStock::create([
            'product_variation_id' => $productVariation1Id,
            'company_id' => 1,
            'quantity' => 20
        ]);

        CompanyStock::create([
            'product_variation_id' => $productVariation2Id,
            'company_id' => 1,
            'quantity' => 10
        ]);

        CompanyStock::create([
            'product_variation_id' => $productVariation3Id,
            'company_id' => 1,
            'quantity' => 20
        ]);

        CompanyStock::create([
            'product_variation_id' => $productVariation4Id,
            'company_id' => 1,
            'quantity' => 10
        ]);


        BranchStock::create([
            'product_variation_id' => $productVariation1Id,
            'branch_id' => 1,
            'quantity' => 5
        ]);

        BranchStock::create([
            'product_variation_id' => $productVariation2Id,
            'branch_id' => 1,
            'quantity' => 5
        ]);

        BranchStock::create([
            'product_variation_id' => $productVariation3Id,
            'branch_id' => 1,
            'quantity' => 5
        ]);

        BranchStock::create([
            'product_variation_id' => $productVariation4Id,
            'branch_id' => 1,
            'quantity' => 5
        ]);
    }
}
