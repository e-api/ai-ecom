<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
            'position' => 1,
            'level' => 1,
        ]);
        
        $mobiles = $electronics->children()->create([
            'name' => 'Mobile Phones',
            'position' => 1,
            'level' => 2,
        ]);
        
        $mobiles->children()->create([
            'name' => 'Android Phones',
            'position' => 1,
            'level' => 3,
        ]);
        
        Category::create([
            'name' => 'Fashion',
            'position' => 2,
            'level' => 1,
        ]);
        
        Category::create([
            'name' => 'Books',
            'position' => 3,
            'level' => 1,
        ]);
    }
}
