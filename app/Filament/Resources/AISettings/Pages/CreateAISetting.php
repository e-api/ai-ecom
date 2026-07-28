<?php

namespace App\Filament\Resources\AISettings\Pages;

use App\Filament\Resources\AISettings\AISettingResource;
use App\Models\AISetting;
use Filament\Resources\Pages\CreateRecord;

class CreateAISetting extends CreateRecord
{
    protected static string $resource = AISettingResource::class;

    public function mount(): void
    {
        if( AISetting::exists() ) {
            $this->redirect($this->getResource()::getUrl());
            return;
        }
        parent::mount();
    }
}
