<?php

use Faker\Generator as Faker;
use App\User;
use App\Category;
use App\Product;
use App\Transaction;
use App\Seller;

/*
  |--------------------------------------------------------------------------
  | Model Factories
  |--------------------------------------------------------------------------
  |
  | This directory should contain each of the model factory definitions for
  | your application. Factories provide a convenient way to generate new
  | model instances for testing / seeding your application's database.
  |
 */

$factory->define(User::class, function (Faker $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        //'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'password' => $password ?: $password = bcrypt('secret'), // secret
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'verification_token' => User::generateVerificationCode(),
        'admin' => $admin = $faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
    ];
});

$factory->define(Category::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

$factory->define(Product::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1,100),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        'image' => $faker->randomElement(['bar1.jpeg','bar2.jpeg','bar3.jpeg']),
        'seller_id' => User::all()->random()->id,
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    
    $sellers = Seller::has('products')->get()->random();    
    $buyer = User::all()->except($sellers->id)->random();


    return [
        'quantity' => $faker->numberBetween(1,100),
        'product_id' => $sellers->products->random()->id,
        'buyer_id' => $buyer->id,
    ];
});
