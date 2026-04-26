<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),  // Explicitly set slug
            'position' => 1,
            'level' => 1,
        ]);
        
        $mobiles = Category::create([
            'name' => 'Mobile Phones',
            'slug' => Str::slug('Mobile Phones'),  // Explicitly set slug
            'parent_id' => $electronics->id,
            'position' => 1,
            'level' => 2,
        ]);
        
        Category::create([
            'name' => 'Android Phones',
            'slug' => Str::slug('Android Phones'),  // Explicitly set slug
            'parent_id' => $mobiles->id,
            'position' => 1,
            'level' => 3,
        ]);
        
        Category::create([
            'name' => 'Fashion',
            'slug' => Str::slug('Fashion'),  // Explicitly set slug
            'position' => 2,
            'level' => 1,
        ]);
        
        Category::create([
            'name' => 'Books',
            'slug' => Str::slug('Books'),  // Explicitly set slug
            'position' => 3,
            'level' => 1,
        ]);
    }
}
