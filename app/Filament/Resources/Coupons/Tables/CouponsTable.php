<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No data yet')
            ->emptyStateDescription('Create your first coupon to get started.')
            ->emptyStateIcon('heroicon-o-ticket')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create a Coupon')
                    ->url('coupons/create')
                    ->button(),
            ])
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('code_type')
                    ->label('Mode')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'manual' => 'info',
                        'auto' => 'success',
                    }),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'fixed' => 'success',
                        'percentage' => 'info',
                    }),

                TextColumn::make('value')
                    ->label('Discount')
                    ->formatStateUsing(fn ($record) =>
                        $record->type === 'fixed'
                            ? '$' . number_format($record->value, 2)
                            : $record->value . '%'
                    )
                    ->sortable(),

                TextColumn::make('min_cart_value')
                    ->label('Min Cart')
                    ->formatStateUsing(fn ($state) =>
                        $state ? '$' . number_format($state, 2) : '—'
                    )
                    ->sortable(),

                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->separator(', ')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('brands.name')
                    ->label('Brands')
                    ->badge()
                    ->separator(', ')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state) => $state ?? '∞'),

                TextColumn::make('used_count')
                    ->label('Used')
                    ->badge()
                    ->color(fn ($state, $record) => 
                        $record->usage_limit && $state >= $record->usage_limit ? 'danger' : 'success'
                    ),

                TextColumn::make('expires_at')
                    ->getStateUsing(fn ($record) => $record->expires_at)
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('Y-m-d') : '—')
                    ->sortable()
                    ->color(fn ($state) => 
                        $state && \Carbon\Carbon::parse($state)->isPast() ? 'danger' : null
                    ),

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
            ->defaultSort('created_at', 'desc')
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