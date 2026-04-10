<?php

namespace App\Filament\Resources\Categories\Schemas;  // Use your existing namespace

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Category Name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('slug', Str::slug($state))
                ),

            TextInput::make('slug')
                ->required(),

            Select::make('parent_id')
                ->label('Parent Category')
                ->options(self::getCategoryOptions())
                ->searchable()
                ->nullable(),

            Textarea::make('description')
                ->rows(3),

            FileUpload::make('image')
                ->image()
                ->directory('categories'),

            TextInput::make('position')
                ->numeric()
                ->default(0),

            Toggle::make('status')
                ->default(true),

            TextInput::make('meta_title'),

            Textarea::make('meta_description')
                ->rows(2),

            TextInput::make('meta_keywords'),
        ]);
    }

    // Hierarchical Category Dropdown
    public static function getCategoryOptions($parentId = null, $prefix = '')
    {
        $categories = Category::where('parent_id', $parentId)
            ->orderBy('position')
            ->get();

        $options = [];

        foreach ($categories as $category) {
            $options[$category->id] = $prefix . $category->name;
            $options += self::getCategoryOptions($category->id, $prefix . '— ');
        }

        return $options;
    }
}