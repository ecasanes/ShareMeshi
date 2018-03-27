<?php

use App\ShareMeshi\Helpers\StatusHelper;
use App\Product;
use App\ProductCategory;
use Faker\Generator as Faker;

$factory->define(ProductCategory::class, function (Faker $faker){

    return [
        'name' => $faker->unique()->name,
        'code' => $faker->unique()->slug,
        'status' => StatusHelper::ACTIVE,
        'color' => $faker->safeHexColor
    ];

});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'code' => $faker->unique()->slug,
        'description' => $faker->sentence,
        'image_url' => $faker->imageUrl(),
        'status' => StatusHelper::ACTIVE,
        'product_category_id' => factory(ProductCategory::class)->create()->id,
    ];
});
