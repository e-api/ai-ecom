<?php

namespace App\Filament\Resources\AISettings\Pages;

use App\Filament\Resources\AISettings\AISettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAISetting extends EditRecord
{
    protected static string $resource = AISettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
