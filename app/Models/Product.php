<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    //
    protected $fillable = [
        'category_id',
        'product_family',
        'color',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'image',
        'featured',
        'status',
        'views',
        'sales_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'specifications',
        'box_contents',
        'service_provider',
        'product_grade',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    protected static function boot() {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get a specification value by label name from the specifications JSON.
     * Traverses all sections to find the first matching label.
     */
    public function getSpecValue(string $label): ?string
    {
        $specs = $this->specifications ?? [];
        foreach ($specs as $section) {
            foreach ($section['items'] as $item) {
                if (strcasecmp($item['label'], $label) === 0) {
                    return $item['value'];
                }
            }
        }
        return null;
    }
}
