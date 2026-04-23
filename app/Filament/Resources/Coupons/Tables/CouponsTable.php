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
                    ->sortable(),
                
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
                            ? '€' . $record->value
                            : $record->value . '%'
                    ),
                
                TextColumn::make('min_cart_value')
                    ->label('Min Cart')
                    ->formatStateUsing(fn ($state) =>
                        $state ? '€' . $state : ''
                    ),
                
                TextColumn::make('usage_limit')
                    ->label('Limit'),
                
                TextColumn::make('used_count')
                    ->label('Used'),
                
                TextColumn::make('expires_at')
                    ->date()
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
