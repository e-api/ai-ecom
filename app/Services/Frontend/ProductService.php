<?php

namespace App\Services\Frontend;

use App\Models\Product;

class ProductService
{
    // Featured Products
    public function getFeaturedProducts($limit = 8)
    {
        return Product::active()
            ->where('featured', 1)
            ->latest()
            ->take($limit)
            ->get();
    }

    // Latest Products
    public function getLatestProducts($limit = 8)
    {
        return Product::active()
            ->latest()
            ->take($limit)
            ->get();
    }
}