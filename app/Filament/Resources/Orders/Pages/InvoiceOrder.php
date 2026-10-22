<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected static string $layout = 'filament-panels::components.layout.base';

    protected static ?string $title = 'Order Invoice';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printInvoice')
                ->label('Print / Save as PDF')
                ->icon('heroicon-o-printer')
                ->extraAttributes([
                    'class' => 'invoice-print-action',
                    'onclick' => 'window.print()',
                ]),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Invoice Details')
                ->schema([
                    TextEntry::make('order_number')
                        ->label('Order Number')
                        ->inlineLabel(),

                    TextEntry::make('created_at')
                        ->label('Order Date')
                        ->dateTime('d M Y, h:i A')
                        ->inlineLabel(),

                    TextEntry::make('user.name')
                        ->label('Customer')
                        ->inlineLabel(),

                    TextEntry::make('payment_method')
                        ->label('Payment Method')
                        ->formatStateUsing(
                            fn ($state) => $state === 'cod'
                                ? 'Cash on Delivery'
                                : ucfirst((string) $state)
                        )
                        ->inlineLabel(),

                    TextEntry::make('payment_status')
                        ->label('Payment Status')
                        ->badge()
                        ->inlineLabel(),
                ])
                ->columns(3)
                ->columnSpanFull(),

            Section::make('Delivery Address')
                ->schema([
                    TextEntry::make('delivery_name')
                        ->label('Name')
                        ->inlineLabel(),

                    TextEntry::make('delivery_phone')
                        ->label('Phone')
                        ->inlineLabel(),

                    TextEntry::make('delivery_address')
                        ->label('Address')
                        ->inlineLabel()
                        ->columnSpanFull(),

                    TextEntry::make('delivery_city')
                        ->label('City')
                        ->inlineLabel(),

                    TextEntry::make('delivery_state')
                        ->label('State')
                        ->inlineLabel(),

                    TextEntry::make('delivery_country')
                        ->label('Country')
                        ->inlineLabel(),

                    TextEntry::make('delivery_postal_code')
                        ->label('Postal Code')
                        ->inlineLabel(),
                ])
                ->columns(3)
                ->columnSpanFull(),

            Section::make('Items')
                ->schema([
                    RepeatableEntry::make('orderItems')
                        ->label('')
                        ->table([
                            TableColumn::make('Product'),
                            TableColumn::make('Price'),
                            TableColumn::make('Qty'),
                            TableColumn::make('Subtotal'),
                        ])
                        ->schema([
                            TextEntry::make('product_name')
                                ->label('Product'),

                            TextEntry::make('price')
                                ->label('Price')
                                ->money('USD'),

                            TextEntry::make('quantity')
                                ->label('Qty'),

                            TextEntry::make('subtotal')
                                ->label('Subtotal')
                                ->money('USD'),
                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),

            Section::make('Order Total')
                ->schema([
                    TextEntry::make('subtotal')
                        ->label('Subtotal')
                        ->money('USD')
                        ->inlineLabel(),

                    TextEntry::make('coupon_discount')
                        ->label('Discount')
                        ->money('USD')
                        ->inlineLabel(),

                    TextEntry::make('shipping_charge')
                        ->label('Shipping')
                        ->money('USD')
                        ->inlineLabel(),

                    TextEntry::make('grand_total')
                        ->label('Grand Total')
                        ->money('USD')
                        ->weight('bold')
                        ->inlineLabel(),
                ])
                ->columns(4)
                ->columnSpanFull(),

        ]);
    }
}