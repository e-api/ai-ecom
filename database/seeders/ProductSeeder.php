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
            'service_provider' => 'Tracfone',
            'product_grade' => 'Renewed',
            'box_contents' => "iPhone 15 Pro\nUSB-C Charge Cable\nDocumentation",
            'specifications' => [
                ['section' => 'Display & Hardware', 'items' => [
                    ['label' => 'Screen Size', 'value' => '6.1 Inches'],
                    ['label' => 'Resolution', 'value' => '2556 × 1179'],
                    ['label' => 'Refresh Rate', 'value' => '120 Hz'],
                    ['label' => 'Display Type', 'value' => 'OLED'],
                    ['label' => 'Operating System', 'value' => 'iOS 17'],
                    ['label' => 'CPU Model', 'value' => 'Apple A17 Pro'],
                    ['label' => 'CPU Speed', 'value' => '3.78 GHz'],
                    ['label' => 'Ram Memory Installed Size', 'value' => '8 GB'],
                    ['label' => 'Memory Storage Capacity', 'value' => '128 GB'],
                    ['label' => 'Model Name', 'value' => 'iPhone 15 Pro'],
                ]],
                ['section' => 'Battery & Dimensions', 'items' => [
                    ['label' => 'Battery', 'value' => '3582 mAh'],
                    ['label' => 'Dimensions', 'value' => '5.89 x 2.81 x 0.32 inches'],
                ]],
                ['section' => 'Connectivity', 'items' => [
                    ['label' => 'Wireless Provider', 'value' => 'Unlocked for All Carriers'],
                    ['label' => 'Cellular Technology', 'value' => '5G'],
                ]],
                ['section' => 'Item Details', 'items' => [
                    ['label' => 'Brand', 'value' => 'Apple'],
                    ['label' => 'Model Year', 'value' => '2024'],
                    ['label' => 'Built-In Media', 'value' => 'Apple iPhone 16 Pro, USB Cable'],
                    ['label' => 'Warranty', 'value' => '1 Year Amazon Renewed Guarantee'],
                    ['label' => 'Manufacturer', 'value' => 'Apple'],
                    ['label' => 'UPC', 'value' => '724129131017'],
                    ['label' => 'ASIN', 'value' => 'B0DNTC3HXX'],
                ]],
                ['section' => 'Customer Feedback', 'items' => [
                    ['label' => 'Customer Reviews', 'value' => '4.3 out of 5 stars (811 reviews)'],
                    ['label' => 'Lower Price Feedback', 'value' => 'Yes ✔'],
                    ['label' => 'Best Sellers Rank', 'value' => '#680 in Cell Phones & Accessories, #9 in Renewed Smartphones, #13 in Cell Phones'],
                ]],
            ],
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
            'service_provider' => 'Unlocked',
            'product_grade' => 'New',
            'box_contents' => "Samsung Galaxy S24\nUSB-C Cable\nSIM Tray Ejector\nDocumentation",
            'specifications' => [
                ['section' => 'Display & Hardware', 'items' => [
                    ['label' => 'Screen Size', 'value' => '6.2 inches'],
                    ['label' => 'Resolution', 'value' => '2340 x 1080'],
                    ['label' => 'Refresh Rate', 'value' => '120 Hz'],
                    ['label' => 'Display Type', 'value' => 'Dynamic AMOLED 2X'],
                    ['label' => 'Pixel Density', 'value' => '416 PPI'],
                    ['label' => 'Operating System', 'value' => 'Android 14'],
                    ['label' => 'CPU Model', 'value' => 'Exynos 2400'],
                    ['label' => 'CPU Speed', 'value' => '3.2 GHz'],
                    ['label' => 'Ram Memory Installed Size', 'value' => '8 GB'],
                    ['label' => 'Memory Storage Capacity', 'value' => '256 GB'],
                    ['label' => 'Color', 'value' => 'Marble Gray'],
                    ['label' => 'Connector', 'value' => 'USB Type C'],
                    ['label' => 'Form Factor', 'value' => 'Bar'],
                    ['label' => 'SIM', 'value' => 'Dual SIM'],
                    ['label' => 'Water Resistance', 'value' => 'IP68'],
                ]],
                ['section' => 'Battery & Dimensions', 'items' => [
                    ['label' => 'Battery', 'value' => '4000 mAh'],
                    ['label' => 'Dimensions', 'value' => '5.75 x 2.78 x 0.30 inches'],
                ]],
                ['section' => 'Connectivity', 'items' => [
                    ['label' => 'Wireless Provider', 'value' => 'Unlocked for All Carriers'],
                    ['label' => 'Cellular Technology', 'value' => '5G'],
                ]],
                ['section' => 'Item Details', 'items' => [
                    ['label' => 'Brand', 'value' => 'Samsung'],
                    ['label' => 'Model Year', 'value' => '2024'],
                    ['label' => 'Built-In Media', 'value' => 'Samsung Galaxy S24, USB Cable'],
                    ['label' => 'Warranty', 'value' => '1 Year Manufacturer Warranty'],
                    ['label' => 'Manufacturer', 'value' => 'Samsung'],
                    ['label' => 'UPC', 'value' => '887276789012'],
                    ['label' => 'ASIN', 'value' => 'B0CN9X1Y2Z'],
                ]],
                ['section' => 'Customer Feedback', 'items' => [
                    ['label' => 'Customer Reviews', 'value' => '4.5 out of 5 stars (1,234 reviews)'],
                    ['label' => 'Lower Price Feedback', 'value' => 'Yes ✔'],
                    ['label' => 'Best Sellers Rank', 'value' => '#245 in Cell Phones & Accessories'],
                ]],
            ],
        ]);
    }
}
