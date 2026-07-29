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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use App\Services\Frontend\AIService;
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

                            TextInput::make('product_family')
                                ->label('Product Family')
                                ->helperText('eg: IPHONE15')
                                ->required(),

                            TextInput::make('color')
                                ->label('Product Color')
                                ->helperText('eg: Red, Blue, Green')
                                ->required(),

                            TextInput::make('service_provider')
                                ->label('Service Provider')
                                ->helperText('e.g. AT&T, T-Mobile, Verizon, Tracfone, Unlocked')
                                ->nullable(),

                            TextInput::make('product_grade')
                                ->label('Product Grade')
                                ->helperText('e.g. Renewed, Renewed Premium, New')
                                ->nullable(),

                            TextInput::make('style')
                                ->label('Style')
                                ->helperText('e.g. Modern, Classic, Minimalist')
                                ->nullable(),

                            TextInput::make('pattern_name')
                                ->label('Pattern Name')
                                ->helperText('e.g. Floral, Geometric, Striped')
                                ->nullable(),

                            Select::make('category_id')
                                ->label('Category')
                                ->options(CategoryForm::getCategoryOptions())
                                ->searchable()
                                ->required(),

                            Select::make('brand_id')
                                ->label('Brand')
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->preload(),

                            Select::make('attributeValues')
                                ->label('Attributes')
                                ->multiple()
                                ->relationship('attributeValues', 'value')
                                ->getOptionLabelFromRecordUsing(fn ($record) =>
                                    $record->attribute->name . ': ' . $record->value)
                                ->preload()
                                ->searchable(),
                            
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
                                ->directory('products')
                                ->maxSize(5120)
                                ->helperText('Recommended size: 1080x1080px. Maximum file size: 5MB.')
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp',
                                ])
                                ->getUploadedFileNameForStorageUsing(function ($file) {
                                    $part1 = Str::random(10);
                                    $part2 = Str::random(8);

                                    return "{$part1}._{$part2}_." . $file->getClientOriginalExtension();
                                })
                                ->disk(env('FILESYSTEM_DISK', config('filesystems.default')))
                                ->visibility('public')
                                ->imageResizeMode('cover')
                                ->imageResizeTargetWidth('1080')
                                ->imageResizeTargetHeight('1080')
                                ->required(),
                            
                            Toggle::make('status')
                                ->default(true),
                            
                            Toggle::make('featured'),
                        ]),
                ]),

            // DESCRIPTION
            Section::make('Description')
                ->headerActions([
                    Action::make('generateDescription')
                        ->label(
                            'Generate AI Content'
                        )
                        ->icon(
                            'heroicon-o-sparkles'
                        )
                        ->action(
                            function (
                                $get,
                                $set
                            ) {
                                $productName = 
                                    $get('name');

                                if (
                                    empty(
                                        $productName
                                    )
                                ) {
                                    Notification::make()
                                        ->title('Product Name Required')
                                        ->body('Please enter a Product Name before generating content.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $categoryName = "";

                                if (
                                    $get(
                                        'category_id'
                                    )
                                ) {
                                    $category = 
                                        \App\Models\Category::find(
                                            $get(
                                                'category_id'
                                            )
                                        );

                                    $categoryName = 
                                        $category?->name;
                                }

                                $content = 
                                    app(
                                        AIService::class
                                    )
                                    ->generateProductContent(
                                        $productName,
                                        $categoryName
                                    );

                                if (! isset($content['error'])) {
                                    $set(
                                        'description',
                                        $content['description'] ?? ""
                                    );

                                    $set(
                                        'short_description',
                                        $content['short_description'] ?? ""
                                    );

                                    $set(
                                        'meta_title',
                                        $content['meta_title'] ?? ""
                                    );

                                    $set(
                                        'meta_description',
                                        $content['meta_description'] ?? ""
                                    );

                                    $set(
                                        'meta_keywords',
                                        $content['meta_keywords'] ?? ""
                                    );

                                    // 👇 Optional: Show success notification
                                    Notification::make()
                                        ->title('AI content generated successfully!')
                                        ->success()
                                        ->send();
                                } else {
                                    // 👇 Optional: Show error notification
                                    Notification::make()
                                        ->title('Unexpected error, content failed to show')
                                        ->body($content['error'])
                                        ->danger()
                                        ->send();
                                    // 👇 NEW: Populate the description field with the error so it's visible
                                    $set('description', 'AI Error: ' . $content['error']);
                                    $set('short_description', 'AI Error: ' . $content['error']);
                                    $set('meta_title', 'AI Error: ' . $content['error']);
                                    $set('meta_description', 'AI Error: ' . $content['error']);
                                    $set('meta_keywords', 'AI Error: ' . $content['error']);
                                }
                            }
                        ),
                ])
                ->schema([
                    Textarea::make('description')
                        ->rows(6),
                    
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
                                ->maxSize(5120)
                                ->helperText('Recommended size: 1080x1080px. Maximum file size: 5MB.')
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp',
                                ])
                                ->getUploadedFileNameForStorageUsing(function ($file) {
                                    $part1 = Str::random(10);
                                    $part2 = Str::random(8);

                                    return "{$part1}._{$part2}_." . $file->getClientOriginalExtension();
                                })
                                ->disk(env('FILESYSTEM_DISK', config('filesystems.default')))
                                ->visibility('public')
                                ->imageResizeMode('cover')
                                ->imageResizeTargetWidth('1080')
                                ->imageResizeTargetHeight('1080')
                                ->required(),
                            
                            TextInput::make('position')
                                ->numeric()
                                ->required()
                                ->default(fn (Get $get) => count($get('../../images') ?? []) + 1)
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
                                ->required()
                                ->default(fn (Get $get) => $get('../../sku')),
                            
                            TextInput::make('price')
                                ->numeric()
                                ->required(),
                            
                            TextInput::make('stock')
                                ->required()
                                ->default(0),
                            
                            TextInput::make('position')
                                ->numeric()
                                ->required()
                                ->default(fn (Get $get) => count($get('../../variants') ?? []) + 1)
                                ->dehydrateStateUsing(fn ($state) => $state ?? 1),
                            
                            Toggle::make('status')
                                ->default(true),
                        ])
                        ->columns(2),
                ]),

            // WHAT'S IN THE BOX
            Section::make("What's in the Box")
                ->collapsed()
                ->schema([
                    Textarea::make('box_contents')
                        ->label('Box Contents')
                        ->helperText('Enter each item on a new line. e.g. iPhone, USB Cable, Charger')
                        ->rows(4),
                ]),

            // PRODUCT SPECIFICATIONS
            Section::make('Product Specifications')
                ->collapsed()
                ->schema([
                    Repeater::make('specifications')
                        ->label('Specification Sections')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    TextInput::make('section')
                                        ->label('Section Name')
                                        ->helperText('e.g. Display & Hardware, Battery & Dimensions, Connectivity')
                                        ->required(),
                                    
                                    Repeater::make('items')
                                        ->label('Specification Items')
                                        ->schema([
                                            Grid::make(2)
                                                ->schema([
                                                    TextInput::make('label')
                                                        ->label('Label')
                                                        ->helperText('e.g. Screen Size, RAM, Battery')
                                                        ->required(),
                                                    
                                                    TextInput::make('value')
                                                        ->label('Value')
                                                        ->helperText('e.g. 6.3 inches, 256 GB, 3582 mAh')
                                                        ->required(),
                                                ]),
                                        ])
                                        ->addActionLabel('Add Specification')
                                        ->columns(1),
                                ]),
                        ])
                        ->addActionLabel('Add Section')
                        ->columns(1),
                ]),
        ]);
    }
}