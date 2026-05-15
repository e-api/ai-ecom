<?php

namespace App\Models;

// use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'level',
        'position',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // protected static function boot() {
    //     parent::boot();
    //     static::creating(function ($category) {
    //         if (empty($category->slug)) {
    //             $category->slug = Str::slug($category->name);
    //         }
    //     });
    // }

    // Get All Child Category IDs Recursively
    public function getAllChildrenIds()
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }
        return $ids;
    }
}
