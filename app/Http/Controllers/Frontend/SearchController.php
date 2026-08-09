<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\AIService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    protected $productService;

    public function __construct(
        ProductService $productService,
        AIService $aiService
    ) {
        $this->productService = $productService;
        $this->aiService = $aiService;
    }

    /*
    |--------------------------------------------------------------------------
    | Product Search 
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ) {
        $keyword = trim(
            $request->q
        );

        $products = collect();

        if (!empty($keyword)) {
            /*
            |--------------------------------------------------------------------------
            | Traditional Keyword Search 
            |--------------------------------------------------------------------------
            */
            $products = $this->productService
                ->searchProducts(
                    $keyword
                );

            /*
            |--------------------------------------------------------------------------
            | AI Search
            |--------------------------------------------------------------------------
            */
            if ($products->isEmpty()) {
                $filters = $this->aiService
                    ->extractSearchFilters(
                        $keyword
                    );

                $products = $this->productService
                    ->searchProductsUsingFilters(
                        $filters
                    );
            }
        }

        return view(
            'frontend.search.index',
            compact(
                'keyword',
                'products'
            )
        );
    }
}
