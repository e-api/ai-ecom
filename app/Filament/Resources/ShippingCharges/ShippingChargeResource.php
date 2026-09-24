<?php

namespace App\Filament\Resources\ShippingCharges;

use App\Filament\Resources\ShippingCharges\Pages\CreateShippingCharge;
use App\Filament\Resources\ShippingCharges\Pages\EditShippingCharge;
use App\Filament\Resources\ShippingCharges\Pages\ListShippingCharges;
use App\Filament\Resources\ShippingCharges\Schemas\ShippingChargeForm;
use App\Filament\Resources\ShippingCharges\Tables\ShippingChargesTable;
use App\Models\ShippingCharge;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShippingChargeResource extends Resource
{
    protected static ?string $model = ShippingCharge::class;

    protected static string|UnitEnum|null $navigationGroup = 'Shipping & Taxes';
    //protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::GlobeAlt;  // Remove 'Solid' prefix

    protected static ?string $recordTitleAttribute = 'min_amount';

    public static function form(Schema $schema): Schema
    {
        return ShippingChargeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingChargesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingCharges::route('/'),
            'create' => CreateShippingCharge::route('/create'),
            'edit' => EditShippingCharge::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        
        return match(true) {
            $count === 0 => 'danger',
            $count < 3 => 'danger',
            $count < 5 => 'warning',
            $count < 10 => 'success',
            default => 'gray',
        };
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return static::getModel()::count() . ' shipping charges available';
    }
}
