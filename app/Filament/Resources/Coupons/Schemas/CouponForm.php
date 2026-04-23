<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Coupon Code')
                    ->required()
                    ->unique(ignoreRecord: true),
                
                Select::make('type')
                    ->label('Coupon Type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage',
                    ])
                    ->required(),
                
                TextInput::make('value')
                    ->label('Discount Value')
                    ->numeric()
                    ->required(),
                
                TextInput::make('min_cart_value')
                    ->label('Minimum Cart Value')
                    ->numeric()
                    ->nullable(),
                
                TextInput::make('usage_limit')
                    ->label('Usage Limit')
                    ->numeric()
                    ->nullable(),
                
                DatePicker::make('expires_at')
                    ->label('Expiry Date')
                    ->nullable(),
                
                Toggle::make('status')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
