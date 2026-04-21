<?php

namespace App\Filament\Resources\AttributeValues\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributeValuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No data yet')
                ->emptyStateDescription('Create your first attribute value to get started.')
                ->emptyStateIcon('heroicon-o-shape')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Create an Attribute Value')
                        ->url('attribute-values/create')
                        ->button(),
                ])
            ->columns([
                TextColumn::make('attribute.name')
                    ->label('Attribute')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
