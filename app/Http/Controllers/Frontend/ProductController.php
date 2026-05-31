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

        return view('frontend.product.detail', compact(
            'product',
            'relatedProducts'
        ));
    }
}