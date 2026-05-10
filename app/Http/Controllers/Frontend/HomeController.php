<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\BannerService;

class HomeController extends Controller 
{
  protected $bannerService;

  public function __construct(BannerService $bannerService)
  {
    $this->bannerService = $bannerService;
  }

  public function index()
  {
    $sliderBanners = $this->bannerService->getSliderBanners();
    $gridBanners = $this->bannerService->getGridBanners();

    return view('frontend.home', compact('sliderBanners', 'gridBanners'));
  }
}