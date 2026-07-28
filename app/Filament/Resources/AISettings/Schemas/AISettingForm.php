<?php

namespace App\Filament\Resources\AISettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AISettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('openai_model')
                    ->label('OpenAI Model')
                    ->options([
                        'gpt-4.1-mini' => 'GPT-4.1 Mini',
                        'gpt-4.1' => 'GPT-4.1',
                        'gpt-4o' => 'GPT-4o',
                        'gpt-4o-mini' => 'GPT-4o Mini',
                        'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                        'gpt-3.5-turbo-16k' => 'GPT-3.5 Turbo 16k',
                    ])
                    ->searchable()
                    ->required(),
                TextInput::make('temperature')
                    ->label('Temperature')
                    ->numeric()
                    ->default(0.7)
                    ->required(),
                TextInput::make('max_tokens')
                    ->label('Max Tokens')
                    ->numeric()
                    ->default(600)
                    ->required(),
            ]);
    }
}
