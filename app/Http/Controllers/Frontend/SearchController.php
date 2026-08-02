<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    protected $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $keyword = trim($request->q);
        $products = collect();

        if (!empty($keyword)) {
            $products = $this->productService->searchProducts($keyword);
        }

        return view(
            'frontend.search.index', compact('keyword', 'products')
        );
    }
}
