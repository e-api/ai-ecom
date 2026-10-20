<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use App\Models\OrderStatusHistory;
use App\Models\OrderShipment;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            ...$this->getResourceBreadcrumbs(),
            $this->getBreadcrumb(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateOrderStatus')
                ->label('Update Order Status')
                ->icon('heroicon-o-truck')
                ->color('primary')
                ->modalHeading('Update Order Status')
                ->modalSubmitActionLabel('Update Status')
                ->fillForm(fn () => [
                    'status' => $this->record->order_status,
                ])
                ->schema([
                    Select::make('status')
                        ->label('Order Status')
                        ->options([
                            'pending' => 'Pending',
                            'processing' => 'In Process',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->live(),

                    TextInput::make('courier_name')
                        ->label('Courier Name')
                        ->visible(fn (Get $get): bool =>
                            $get('status') === 'shipped'
                        )
                        ->required(fn (Get $get): bool =>
                            $get('status') === 'shipped'
                        )
                        ->maxLength(255),

                    TextInput::make('tracking_number')
                        ->label('Tracking Number')
                        ->visible(fn (Get $get): bool =>
                            $get('status') === 'shipped'
                        )
                        ->required(fn (Get $get): bool =>
                            $get('status') === 'shipped'
                        )
                        ->maxLength(255),

                    TextInput::make('tracking_link')
                        ->label('Tracking Link')
                        ->url()
                        ->visible(fn (Get $get): bool =>
                            $get('status') === 'shipped'
                        )
                        ->required(fn (Get $get): bool =>
                            $get('status') === 'shipped'
                        )
                        ->maxLength(2048),
                ])
                ->action(function (array $data): void {
                    $order = $this->record;

                    if ($data['status'] === 'shipped') {
                        foreach ([
                            'courier_name',
                            'tracking_number',
                            'tracking_link',
                        ] as $field) {
                            if (blank($data[$field] ?? null)) {
                                throw ValidationException::withMessages([
                                    $field => 'This field is required when an order is shipped.',
                                ]);
                            }
                        }
                    }

                    DB::transaction(function () use ($order, $data) {
                        $order->update([
                            'order_status' => $data['status'],
                        ]);

                        OrderStatusHistory::create([
                            'order_id' => $order->id,
                            'status' => $data['status'],
                        ]);

                        if ($data['status'] === 'shipped') {
                            OrderShipment::create([
                                'order_id' => $order->id,
                                'courier_name' => $data['courier_name'],
                                'tracking_number' => $data['tracking_number'],
                                'tracking_link' => $data['tracking_link'],
                            ]);
                        }
                    });

                    Notification::make()
                        ->success()
                        ->title('Order status updated successfully')
                        ->send();

                    $this->record->refresh();
                }),
        ];
    }
}
