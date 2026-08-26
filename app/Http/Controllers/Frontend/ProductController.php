<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\AIService;

class ProductController extends Controller
{
    protected $productService;
    protected $aiService;

    public function __construct(
        ProductService $productService,
        AIService $aiService
    ) {
        $this->productService = $productService;
        $this->aiService = $aiService;
    }

    public function detail($slug)
    {
        /*
        | Product Detail
        */
        $product = $this->productService
            ->getProductBySlug($slug);

        // Send Product Description and Get Key Features from AI

        $keyFeatures = [];

        if (!empty($product->description)) {

            $aiResult = $this->aiService
                ->generateProductHighlights(
                    $product->description
                );

            $keyFeatures = $aiResult['key_features'] ?? [];
        }

        // echo "<pre>"; print_r($keyFeatures); die;

        /*
        |--------------------------------------------------------------------------
        | AI Product FAQs
        |--------------------------------------------------------------------------
        */

        $faqs = [];

        if (!empty($product->description)) {

            $aiFaqResult = $this->aiService
                ->generateProductFAQs(
                    $product->description
                );

            $faqs = $aiFaqResult['faqs'] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | AI Product Specifications
        |--------------------------------------------------------------------------
        */

        $specifications = [];

        if (
            !empty($product->name) &&
            !empty($product->brand)
        ) {
            $specificationResult = $this->aiService
                ->generateProductSpecifications(
                    $product->name,
                    $product->brand->name
                );
            
            $specifications = $specificationResult['specifications'] ?? [];
        }

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

        /*
        | Style Variations
        */
        $styleVariations = $this->productService
            ->getStyleVariations($product);

        /*
        | Pattern Name Variations
        */
        $patternNameVariations = $this->productService
            ->getPatternNameVariations($product);

        return view('frontend.product.detail', compact(
            'product',
            'relatedProducts',
            'colorVariations',
            'providerVariations',
            'gradeVariations',
            'styleVariations',
            'patternNameVariations',
            'faqs',
            'specifications'
        ));
    }
}