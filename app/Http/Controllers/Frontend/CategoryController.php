<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;

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

    public function listing(Request $request, $slug)
    {
        $category = $this->categoryService->getCategoryBySlug($slug);
        
        $filters = [];
        if ($request->has('price')) {
            $filters['price'] = $request->price;
        }
        
        $products = $this->productService->getCategoryProducts($category, $filters, 12);
        
        if ($request->ajax()) {
            // Return only the products partial (which now has the wrapper)
            return view('frontend.category.partials.products', compact('products', 'category'))->render();
        }
        
        return view('frontend.category.listing', compact('category', 'products'));
    }
}