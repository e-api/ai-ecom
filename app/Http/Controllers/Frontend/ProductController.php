<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function detail($slug)
    {
        /*
        | Product Detail
        */
        $product = $this->productService
            ->getProductBySlug($slug);

        /*
        | Related Products
        */
        $relatedProducts = $this->productService
            ->getRelatedProducts($product);

        /*
        | Product Color Variations
        */
        $colorVariations = $this->productService
            ->getProductColorVariations($product);

        /*
        | Service Provider Variations
        */
        $providerVariations = $this->productService
            ->getProviderVariations($product);

        /*
        | Product Grade Variations
        */
        $gradeVariations = $this->productService
            ->getGradeVariations($product);

        return view('frontend.product.detail', compact(
            'product',
            'relatedProducts',
            'colorVariations',
            'providerVariations',
            'gradeVariations'
        ));
    }
}