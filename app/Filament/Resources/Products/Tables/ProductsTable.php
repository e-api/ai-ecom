<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use App\Filament\Resources\Products\ProductResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Str;

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
                TextColumn::make('product_family')
                    ->label('Product Family')
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Product Color')
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
                    ->disk(env('FILESYSTEM_DISK', config('filesystems.default'))),
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
                // Single Replication each items
                // ReplicateAction::make()
                //     ->label('Replicate Product')
                //     ->icon('heroicon-o-square-2-stack')
                //     // Don't exclude 'name' and 'slug', let's handle them manually below
                //     ->excludeAttributes([
                //         'sku', 
                //         'color'
                //     ])
                //     // Set a temporary name before saving to the database
                //     ->mutateRecordDataUsing(function (array $data, \Illuminate\Database\Eloquent\Model $record): array {
                //         $data['name'] = $record->name . ' (Copy)';
                //         $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
                //         $data['sku'] = $record->sku . '-COPY-' . \Illuminate\Support\Str::random(5);
                        
                //         return $data;
                //     })
                //     ->successNotificationTitle('Product replicated. Please edit the details.')
                //     ->successRedirectUrl(fn (\Illuminate\Database\Eloquent\Model $replica): string => 
                //         ProductResource::getUrl('edit', ['record' => $replica])
                // ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('replication')
                        ->label('Replication selected')
                        ->icon('heroicon-o-square-2-stack')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Replicate Products')
                        ->modalDescription('Are you sure you want to replicate the selected products? Temporary names and SKUs will be generated.')
                        ->action(function (Collection $records) {
                            $replicatedCount = 0;

                            foreach ($records as $record) {
                                // 1. Replicate the base product
                                $replication = $record->replicate();
                                
                                // 2. Set temporary unique values for required fields
                                $replication->name = $record->name . ' (Replica)';
                                $replication->slug = Str::slug($replication->name) . '-' . Str::random(4); 
                                $replication->sku = $record->sku . '-replica-' . Str::random(5);        
                                
                                // Save the new product
                                $replication->save();

                                // 3. Duplicate relationships (Images & Variants)
                                // Note: Make sure 'images' and 'variants' match the exact relationship names in your Product model
                                
                                foreach ($record->images as $image) {
                                    $replicatedImage = $image->replicate();
                                    $replicatedImage->product_id = $replication->id;
                                    $replicatedImage->save();
                                }

                                foreach ($record->variants as $variant) {
                                    $replicatedVariant = $variant->replicate();
                                    $replicatedVariant->product_id = $replication->id;
                                    $replicatedVariant->sku = $variant->sku . '-replica-' . Str::random(5); 
                                    $replicatedVariant->save();
                                }

                                $replicatedCount++;
                            }

                            // 4. Send success notification
                            Notification::make()
                                ->title('Products replicated')
                                ->body("Successfully replicated {$replicatedCount} products. Please review the new items in the list.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
