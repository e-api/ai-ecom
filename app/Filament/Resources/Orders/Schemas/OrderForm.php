<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('delivery_name')
                    ->required(),
                TextInput::make('delivery_phone')
                    ->tel()
                    ->required(),
                Textarea::make('delivery_address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('delivery_city')
                    ->required(),
                TextInput::make('delivery_state'),
                TextInput::make('delivery_country')
                    ->required(),
                TextInput::make('delivery_postal_code'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('coupon_discount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('shipping_charge')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('grand_total')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_method')
                    ->required()
                    ->default('cod'),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('order_status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
