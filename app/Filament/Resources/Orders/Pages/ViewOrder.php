<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use App\Models\OrderStatusHistory;
use App\Models\OrderShipment;
use App\Mail\OrderShippedMail;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

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
            Action::make('printInvoice')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => OrderResource::getUrl(
                    'invoice',
                    ['record' => $this->record]
                ))
                ->openUrlInNewTab(),

            Action::make('updateOrderStatus')
                ->label('Update Order Status')
                ->icon('heroicon-o-pencil-square')
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
                                throw \Illuminate\Validation\ValidationException::withMessages([
                                    $field => 'This field is required when an order is shipped.',
                                ]);
                            }
                        }
                    }

                    // Save order status, history, and shipment details.
                    $shipment = DB::transaction(function () use ($order, $data) {
                        $order->update([
                            'order_status' => $data['status'],
                        ]);

                        OrderStatusHistory::create([
                            'order_id' => $order->id,
                            'status' => $data['status'],
                        ]);

                        if ($data['status'] === 'shipped') {
                            return OrderShipment::create([
                                'order_id' => $order->id,
                                'courier_name' => $data['courier_name'],
                                'tracking_number' => $data['tracking_number'],
                                'tracking_link' => $data['tracking_link'],
                            ]);
                        }
                        return null;
                    });

                    $this->record->refresh();

                    // Send email only when a shipment is created.
                    if ($shipment) {
                        try {
                            Mail::to($order->user->email)
                                ->send(new OrderShippedMail($shipment));
                        } catch (\Throwable $exception) {
                            report($exception);
                            Notification::make()
                                ->warning()
                                ->title('Order shipped, but email could not be sent')
                                ->body('Please check your mail configuration and application logs.')
                                ->send();
                            return;
                        }
                    }

                    Notification::make()
                        ->success()
                        ->title('Order status updated successfully')
                        ->send();
                })
        ];
    }
}
