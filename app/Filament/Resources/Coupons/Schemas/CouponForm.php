<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions\Action;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ⚠️ Coupon Mode
                Select::make('code_type')
                    ->label('Coupon Mode')
                    ->options([
                        'manual' => 'Manual',
                        'auto' => 'Automatic',
                    ])
                    ->default('manual')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state === 'auto') {
                            // Generate a random code only if code is empty
                            if (empty($get('code'))) {
                                $set('code', strtoupper(Str::random(8)));
                            }
                        } else {
                            // Clear the code field when switching back to manual
                            $set('code', null);
                        }
                    }),
                
                // Manual Code
                TextInput::make('code')
                    ->label('Coupon Code')
                    ->required(fn ($get) => $get('code_type') === 'manual')
                    ->unique(ignoreRecord: true)
                    ->visible(fn ($get) => $get('code_type') === 'manual'),

                // Auto Generated Code (same field reused)
                TextInput::make('code')
                    ->label('Generated Code')
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn ($get) => $get('code_type') === 'auto')
                    ->helperText('Code will be automatically generated on save')
                    ->default(function ($record, $get) {
                        // Only generate code when in auto mode AND creating new record
                        if ($get('code_type') === 'auto' && !$record) {
                            return strtoupper(Str::random(8));
                        }
                        // On edit, return existing code
                        return $record?->code ?? null;
                    }),
                
                // Categories (Multi Select)
                Select::make('categories')
                    ->label('Categories (Optional)')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload()
                    ->searchable()
                    ->helperText('Leave empty to apply to all categories'),

                // Brands (Multi Select)
                Select::make('brands')
                    ->label('Brands (Optional)')
                    ->multiple()
                    ->relationship('brands', 'name')
                    ->preload()
                    ->searchable()
                    ->helperText('Leave empty to apply to all brands'),
                
                // Coupon Type
                Select::make('type')
                    ->label('Coupon Type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage',
                    ])
                    ->required(),
                
                // Discount Value
                TextInput::make('value')
                    ->label('Discount Value')
                    ->numeric()
                    ->required()
                    ->helperText(fn ($get) => 
                        $get('type') === 'percentage' 
                            ? 'Enter percentage (e.g., 10 for 10% off)' 
                            : 'Enter amount (e.g., 20.00 for $20 off)'
                    ),
                
                // Minimum Cart Value
                TextInput::make('min_cart_value')
                    ->label('Minimum Cart Value')
                    ->numeric()
                    ->nullable()
                    ->prefix('$'),
                
                // Usage Limit
                TextInput::make('usage_limit')
                    ->label('Usage Limit')
                    ->numeric()
                    ->nullable()
                    ->helperText('Maximum number of times this coupon can be used'),
                
                // Expiry Date
                DatePicker::make('expires_at')
                    ->label('Expiry Date')
                    ->nullable()
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y')
                    ->native(false),
                
                // Status
                Toggle::make('status')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}