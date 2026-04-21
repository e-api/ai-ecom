<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->nullable()
                    ->maxLength(255),
                
                FileUpload::make('image')
                    ->image()
                    ->disk('public') // important
                    ->directory('banners')
                    ->required(),
                
                TextInput::make('link')
                    ->label('Redirect URL')
                    ->url()
                    ->nullable(),
                
                Select::make('type')
                    ->label('Banner Type')
                    ->options([
                        'slider' => 'Slider Banner',
                        'grid' => 'Grid Banner',
                    ])
                    ->default('slider')
                    ->required(),
                
                TextInput::make('position')
                    ->numeric()
                    ->default(0),
                
                Toggle::make('status')
                    ->default(true),
            ]);
    }
}
