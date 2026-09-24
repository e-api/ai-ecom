<?php

namespace App\Filament\Resources\ShippingCharges\Pages;

use App\Filament\Resources\ShippingCharges\ShippingChargeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShippingCharge extends EditRecord
{
    protected static string $resource = ShippingChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
