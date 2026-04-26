<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;

class Coupon extends Model
{
    //
    protected $fillable = [
        'code',
        'code_type',
        'type',
        'value',
        'min_cart_value',
        'usage_limit',
        'used_count',
        'expires_at',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_coupon');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_coupon');
    }
}
