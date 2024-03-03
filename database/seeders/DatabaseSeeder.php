<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use \App\Models\Brand;
use \App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Province;
use App\Models\Transaction;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // $brand = Brand::factory(10)->create();
        // Category::factory(10)->find($brand)->create();



        // \App\Models\Brand::factory(3)->has(\App\Models\Category::factory(2)->has(\App\Models\Product::factory(1)->has(\App\Models\ProductImage::factory(1))))->create();
        // \App\Models\ProductImage::factory(10)->create();
        // \App\Models\OrderItem::factory(10)->create();
        // \App\Models\User::factory(3)->has(\App\Models\Order::factory(2)->has(\App\Models\Transaction::factory(2)))->has(\App\Models\Province::factory(2))->has(\App\Models\City::factory(2))->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $user = \App\Models\User::factory(2)->create();
 
        // $posts = \App\Models\Order::factory()
        //     ->count(3)
        //     ->for($user)
        //     ->create();
        Brand::factory(2)->create();
        City::factory(2)->create();
        Order::factory(2)->create();
        OrderItem::factory(2)->create();
        Product::factory(2)->create();
        ProductImage::factory(2)->create();
        Province::factory(2)->create();
        Transaction::factory(2)->create();
        User::factory(2)->create();





    }
}
