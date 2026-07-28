<?php

namespace App\Filament\Resources\AISettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AISettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('openai_model')
                    ->label('OpenAI Model')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('temperature')
                    ->label('Temperature')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('max_tokens')
                    ->label('Max Tokens')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn ($record) => false
            )
            ->paginated(false);
    }
}
