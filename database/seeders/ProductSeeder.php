<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     //
    // }
    public function run()
    {
        $category = Category::where('slug', 'electronics')->first();
    
        if (!$category) {
            return;
        }
    
        Product::create([
            'category_id' => $category->id,
            'name' => 'iPhone 15',
            'slug' => 'iphone-15',
            'description' => 'Latest Apple iPhone with advanced features',
            'short_description' => 'Apple iPhone 15',
            'price' => 80000,
            'sale_price' => 75000,
            'sku' => 'IPHONE15-BLK-128',
            'stock' => 50,
            'featured' => 1,
            'meta_title' => 'Buy iPhone 15 Online',
            'meta_description' => 'Best price for iPhone 15',
            'meta_keywords' => 'iphone, apple, mobile',
        ]);
    
        Product::create([
            'category_id' => $category->id,
            'name' => 'Samsung Galaxy S24',
            'slug' => 'samsung-galaxy-s24',
            'description' => 'Samsung flagship smartphone',
            'short_description' => 'Galaxy S24',
            'price' => 70000,
            'sale_price' => 65000,
            'sku' => 'SAMSUNG-S24-256',
            'stock' => 40,
            'featured' => 0,
        ]);
    }
}
