<?php

namespace App\Filament\Resources\ShippingCharges\Pages;

use App\Filament\Resources\ShippingCharges\ShippingChargeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShippingCharges extends ListRecords
{
    protected static string $resource = ShippingChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
