<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No data yet')
                ->emptyStateDescription('Create your first product to get started.')
                ->emptyStateIcon('heroicon-o-shopping-bag')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Create a Product')
                        ->url('products/create')
                        ->button(),
                ])
            ->columns([
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image')
                    ->disk('public'),
                IconColumn::make('featured')
                    ->boolean(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sales_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('meta_title')
                    ->searchable(),
                TextColumn::make('meta_keywords')
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
