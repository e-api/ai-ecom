<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No data yet')
                ->emptyStateDescription('Create your first banner to get started.')
                ->emptyStateIcon('heroicon-o-squares-2x2')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Create a Banner')
                        ->url('banners/create')
                        ->button(),
                ])
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->height(50),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'slider' => 'success',
                        'grid' => 'info',
                    }),
                TextColumn::make('link')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('position')
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
