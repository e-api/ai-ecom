<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $attributes = [
            'Fabric' => ['Cotton', 'Polyester', 'Linen'],
            'Sleeve' => ['Half Sleeve', 'Full Sleeve'],
            'Pattern' => ['Solid', 'Printed'],
        ];

        foreach ($attributes as $key => $values) {
            $attribute = Attribute::create([
                'name' => $key,
                'slug' => Str::slug($key),
                'status' => 1,
            ]);

            foreach ($values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
