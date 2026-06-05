<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Brand Name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->directory('brands')
                    ->disk(env('FILESYSTEM_DISK', config('filesystems.default'))),
                Toggle::make('status')
                    ->label('Status')
                    ->required(),
            ]);
    }
}
