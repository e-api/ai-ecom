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
        
        // Get filter categories for sidebar (all active categories)
        $filterCategories = \App\Models\Category::where('status', 1)
            ->orderBy('name')
            ->get();
        
        $filters = [];
        
        if ($request->has('price')) {
            $filters['price'] = $request->price;
        }
        
        if ($request->has('categories')) {
            $filters['categories'] = $request->categories;
        }

        $products = $this->productService->getCategoryProducts($category, $filters);
        
        if ($request->ajax()) {
            return view('frontend.category.partials.products', compact('products', 'category'))->render();
        }
        
        return view('frontend.category.listing', compact('category', 'products', 'filterCategories'));
    }
}