<?php

namespace App\Services\Frontend;

use App\Models\Category;

class CategoryService
{
    public function getHeaderCategories()
    {
        return Category::with('children.children')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('position')
            ->get();
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->where('status', 1)->firstOrFail();
    }
}