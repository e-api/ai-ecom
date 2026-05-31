<?php

namespace App\Services\Frontend;

use App\Models\Product;
use App\Models\Category;

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

    public function getCategoryProducts($category, $filters = [], $limit = 12)
    {
        /*
        |-------------------------------|
        | Category IDs
        |-------------------------------*/
        $categoryIds = [$category->id];

        $categoryIds = array_merge(
            $categoryIds,
            $category->getAllChildrenIds()
        );

        $query = Product::active()
            ->whereIn('category_id', $categoryIds);

        /*
        | UPDATED: Multiple Price Filters
        |---
        |
        | IMPORTANT:
        | Use sale_price if available,
        | otherwise use regular price
        |
        */

        if (!empty($filters['price'])) {

            $prices = explode(',', $filters['price']);

            $query->where(function ($q) use ($prices) {
                foreach ($prices as $price) {

                    [$min, $max] = explode('-', $price);

                    $q->orWhere(function ($subQuery) use ($min, $max) {

                        /*
                        | Sale Price
                        |
                        */
                        $subQuery->whereNotNull('sale_price')
                            ->whereBetween('sale_price', [$min, $max]);

                        /*
                        | Regular Price
                        */
                        $subQuery->orWhere(function ($regularPriceQuery) use ($min, $max) {
                            $regularPriceQuery
                                ->whereNull('sale_price')
                                ->whereBetween('price', [$min, $max]);
                        });
                    });
                }
            });
        }

        /*
        NEW: Category Filters
        */

        if (!empty($filters['categories'])) {
            $selectedCategoryIds = [];
            $categoryFilters = explode(',', $filters['categories']);
            
            foreach ($categoryFilters as $categoryId) {
                $filterCategory = Category::find($categoryId);
                
                if ($filterCategory) {
                    /*
                    | Current Category
                    */
                    $selectedCategoryIds[] = $filterCategory->id;
                    
                    /*
                    | Child Categories
                    */
                    $selectedCategoryIds = array_merge(
                        $selectedCategoryIds,
                        $filterCategory->getAllChildrenIds()
                    );
                }
            }
            
            $query->whereIn('category_id', $selectedCategoryIds);
        }

        /*
        | NEW: Brand Filters
        */
        if (!empty($filters['brands'])) {
            $brands = explode(',', $filters['brands']);
            $query->whereIn('brand_id', $brands);
        }

        /*
        | NEW: Size Filters
        */
        if (!empty($filters['sizes'])) {
            $sizes = explode(',', $filters['sizes']);
            $query->whereHas('variants', function ($variantQuery) use ($sizes) {
                $variantQuery->whereIn('size', $sizes)->where('status', 1);
            });
        }
        
        /*
        | NEW: Dynamic Attribute Filters
        */
        if (!empty($filters['attribute_values'])) {
        
            $attributes = explode(',', $filters['attribute_values']);
        
            $query->whereHas('attributeValues', function ($attributeQuery) use ($attributes) {
        
                $attributeQuery->whereIn(
                    'attribute_values.id',
                    $attributes
                );
        
            });
        }

        return $query
            ->latest()
            ->take($limit)
            ->get();
    }

    /*
    | Product Detail
    */
    public function getProductBySlug($slug)
    {
        return Product::with([
            'images',
            'variants',
            'brand',
            'category',
            'attributeValues.attribute'
        ])
        ->where('slug', $slug)
        ->where('status', 1)
        ->firstOrFail();
    }

    /*
    Related Products
    */
    public function getRelatedProducts($product, $limit = 4)
    {
        return Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take($limit)
            ->get();
    }
}