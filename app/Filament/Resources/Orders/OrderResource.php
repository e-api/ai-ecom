<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Schemas\Components\Section;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|UnitEnum|null $navigationGroup = 'Products';
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Ticket;  // Remove 'Solid' prefix

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        
        return match(true) {
            $count === 0 => 'danger',
            $count < 3 => 'danger',
            $count < 5 => 'warning',
            $count < 10 => 'success',
            default => 'gray',
        };
    }
    
    public static function getNavigationBadgeTooltip(): ?string
    {
        return static::getModel()::count() . ' orders available';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Order Information')
                    ->schema([

                        TextEntry::make('order_number')
                            ->label('Order Number'),

                        TextEntry::make('user.name')
                            ->label('Customer'),

                        TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime(),

                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->formatStateUsing(
                                fn ($state) =>
                                    $state === 'cod'
                                    ? 'Cash on Delivery'
                                    : ucfirst($state)
                            ),

                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge(),

                        TextEntry::make('order_status')
                            ->label('Order Status')
                            ->badge(),

                    ])
                    ->columns(2),

                Section::make('Delivery Address')
                    ->schema([

                        TextEntry::make('delivery_name')
                            ->label('Name'),

                        TextEntry::make('delivery_phone')
                            ->label('Phone'),

                        TextEntry::make('delivery_address')
                            ->label('Address')
                            ->columnSpanFull(),

                        TextEntry::make('delivery_city')
                            ->label('City'),

                        TextEntry::make('delivery_state')
                            ->label('State'),

                        TextEntry::make('delivery_country')
                            ->label('Country'),

                        TextEntry::make('delivery_postal_code')
                            ->label('Postal Code'),

                    ])
                    ->columns(2),

                Section::make('Order Items')
                    ->schema([

                        RepeatableEntry::make('orderItems')
                            ->label('')
                            ->table([
                                TableColumn::make('Product'),
                                TableColumn::make('Price'),
                                TableColumn::make('Quantity'),
                                TableColumn::make('Subtotal'),
                            ])
                            ->schema([

                                TextEntry::make('product_name')
                                    ->label('Product'),

                                TextEntry::make('price')
                                    ->label('Price')
                                    ->money('USD'),

                                TextEntry::make('quantity')
                                    ->label('Quantity'),

                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('USD'),

                            ])
                            ->columnSpanFull(),

                    ]),

                Section::make('Order Summary')
                    ->schema([

                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('USD'),

                        TextEntry::make('coupon_discount')
                            ->label('Coupon Discount')
                            ->money('USD'),

                        TextEntry::make('shipping_charge')
                            ->label('Shipping Charge')
                            ->money('USD'),

                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->money('USD'),

                    ])
                    ->columns(2),
                Section::make('Order Status History')
                    ->schema([
                        RepeatableEntry::make('statusHistories')
                            ->label('')
                            ->table([
                                TableColumn::make('Status'),
                                TableColumn::make('Date & Time'),
                            ])
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($state) => ucfirst($state))
                                    ->badge(),

                                TextEntry::make('created_at')
                                    ->label('Date & Time')
                                    ->dateTime(),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Shipment History')
                    ->schema([
                        RepeatableEntry::make('shipments')
                            ->label('')
                            ->table([
                                TableColumn::make('Courier'),
                                TableColumn::make('Tracking Number'),
                                TableColumn::make('Tracking Link'),
                                TableColumn::make('Shipment Date'),
                            ])
                            ->schema([
                                TextEntry::make('courier_name')
                                    ->label('Courier'),

                                TextEntry::make('tracking_number')
                                    ->label('Tracking Number'),

                                TextEntry::make('tracking_link')
                                    ->label('Tracking Link')
                                    ->url(fn ($state) => $state)
                                    ->openUrlInNewTab(),

                                TextEntry::make('created_at')
                                    ->label('Shipment Date')
                                    ->dateTime(),
                            ])
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
