<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $productService;

    public function __construct(
        CategoryService $categoryService,
        ProductService $productService
    ) {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    public function listing($slug)
    {
        $category = $this->categoryService
            ->getCategoryBySlug($slug);

        $products = $this->productService
            ->getCategoryProducts($category);

        return view('frontend.category.listing', compact(
            'category',
            'products'
        ));
    }
}