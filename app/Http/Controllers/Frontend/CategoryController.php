<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CategoryService;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;

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
        $filterCategories = Category::where('status', 1)->orderBy('name')->get();
        // Get filter brands for sidebar (all active brands)
        $brands = Brand::where('status', 1)->orderBy('name')->get();
        // Get filter sizes for sidebar (all active sizes)
        $sizes = ProductVariant::where('status', 1)->select('size')->distinct()->orderBy('size')->get();

        $filters = [];
        
        if ($request->has('price')) {
            $filters['price'] = $request->price;
        }
        
        if ($request->has('categories')) {
            $filters['categories'] = $request->categories;
        }

        if ($request->has('brands')) {
            $filters['brands'] = $request->brands;
        }

        if ($request->has('sizes')) {
            $filters['sizes'] = $request->sizes;
        }

        $products = $this->productService->getCategoryProducts($category, $filters);
        
        if ($request->ajax()) {
            return view('frontend.category.partials.products', compact('products', 'category', 'brands', 'sizes'))->render();
        }
        
        return view('frontend.category.listing', compact('category', 'products', 'filterCategories', 'brands', 'sizes'));
    }
}