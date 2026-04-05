<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Product;


class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $product = Product::where('slug', 'iphone-15')->first();

        if (!$product) {
            return;
        }

        ProductImage::create([
            'product_id' => $product->id,
            'image' => 'iphone1.jpg',
            'position' => 1,
            'alt_text' => 'iPhone 15 front view',
            'status' => 1,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'image' => 'iphone4.jpg',
            'position' => 2,
            'alt_text' => 'iPhone 15 back view',
            'status' => 1,
        ]);
    }
}
