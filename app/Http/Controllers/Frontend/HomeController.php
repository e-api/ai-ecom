<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\BannerService;
use App\Services\Frontend\ProductService;

class HomeController extends Controller
{
    protected $bannerService;
    protected $productService;

    public function __construct(
        BannerService $bannerService,
        ProductService $productService
    ) {
        $this->bannerService = $bannerService;
        $this->productService = $productService;
    }

    public function index()
    {
        $sliderBanners = $this->bannerService->getSliderBanners();
        $gridBanners = $this->bannerService->getGridBanners();
        $featuredProducts = $this->productService->getFeaturedProducts();
        $latestProducts = $this->productService->getLatestProducts();

        return view('frontend.home', compact(
            'sliderBanners',
            'gridBanners',
            'featuredProducts',
            'latestProducts'
        ));
    }
}