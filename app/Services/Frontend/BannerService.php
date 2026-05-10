<?php

namespace App\Services\Frontend;

use App\Models\Banner;

class BannerService
{
  public function getSliderBanners()
  {
    return Banner::active()->where('type', 'slider')->orderBy('position')->get();
  }

  public function getGridBanners()
  {
    return Banner::active()->where('type', 'grid')->orderBy('position')->get();
  }
}