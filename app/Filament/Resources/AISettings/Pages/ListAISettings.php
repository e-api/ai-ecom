<?php

namespace App\Filament\Resources\AISettings\Pages;

use App\Filament\Resources\AISettings\AISettingResource;
use App\Models\AISetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAISettings extends ListRecords
{
    protected static string $resource = AISettingResource::class;

    protected function getHeaderActions(): array
    {
        if( AISetting::exists() ) {
            return [];
        }
        return [
            CreateAction::make(),
        ];
    }
}
