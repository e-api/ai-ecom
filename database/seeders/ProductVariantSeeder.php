<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
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
        $product = Product::where('slug', 'iphone-15')->first();

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => '128GB',
            'sku' => 'IPHONE15-128',
            'price' => 75000,
            'stock' => 20,
            'position' => 1,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => '256GB',
            'sku' => 'IPHONE15-256',
            'price' => 85000,
            'stock' => 15,
            'position' => 2,
        ]);
    }
}
