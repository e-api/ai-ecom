<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use App\Filament\Resources\Categories\Schemas\CategoryForm;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // BASIC INFO
            Section::make('Basic Information')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Product Name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                            
                            TextInput::make('slug')
                                ->required(),
                            
                            Select::make('category_id')
                                ->label('Category')
                                ->options(CategoryForm::getCategoryOptions())
                                ->searchable()
                                ->required(),
                            
                            TextInput::make('sku')
                                ->required(),
                            
                            TextInput::make('price')
                                ->numeric()
                                ->required(),
                            
                            TextInput::make('sale_price')
                                ->numeric()
                                ->nullable()
                                ->dehydrateStateUsing(fn ($state) => $state ?: null),
                            
                            TextInput::make('stock')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->dehydrateStateUsing(fn ($state) => $state ?? 0),
                            
                            FileUpload::make('image')
                                ->image()
                                ->directory('products'),
                            
                            Toggle::make('status')
                                ->default(true),
                            
                            Toggle::make('featured'),
                        ]),
                ]),

            // DESCRIPTION
            Section::make('Description')
                ->schema([
                    Textarea::make('description')
                        ->rows(4),
                    
                    Textarea::make('short_description')
                        ->rows(4),
                ]),

            // SEO
            Section::make('SEO Settings')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title'),
                    TextInput::make('meta_keywords'),
                    Textarea::make('meta_description')
                        ->rows(3),
                ]),

            // PRODUCT IMAGES
            Section::make('Product Images')
                ->schema([
                    Repeater::make('images')
                        ->relationship()
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->directory('products')
                                ->disk('public')
                                ->visibility('public')
                                ->required(),
                            
                            TextInput::make('position')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->dehydrateStateUsing(fn ($state) => $state ?? 1),
                            
                            TextInput::make('alt_text'),
                            
                            Toggle::make('status')
                                ->default(true),
                        ])
                        ->columns(2),
                ]),

            // PRODUCT VARIANTS
            Section::make('Product Variants')
                ->schema([
                    Repeater::make('variants')
                        ->relationship()
                        ->schema([
                            TextInput::make('size')
                                ->required(),
                            
                            TextInput::make('sku')
                                ->required(),
                            
                            TextInput::make('price')
                                ->numeric()
                                ->required(),
                            
                            TextInput::make('stock')
                                ->required()
                                ->default(0),
                            
                            TextInput::make('position')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->dehydrateStateUsing(fn ($state) => $state ?? 1),
                            
                            Toggle::make('status')
                                ->default(true),
                        ])
                        ->columns(2),
                ]),
        ]);
    }
}