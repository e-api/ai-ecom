<?php

namespace App\Filament\Resources\ShippingCharges\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ShippingChargeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Shipping Rule')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('min_amount')
                                    ->label('Minimum Order Amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),

                                TextInput::make('max_amount')
                                    ->label('Maximum Order Amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->nullable(),

                                TextInput::make('shipping_charge')
                                    ->label('Shipping Charge')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),
                        ]),
                    ]),
            ]);
    }
}
