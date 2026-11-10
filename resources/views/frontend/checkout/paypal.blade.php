@extends('frontend.layouts.app')

@section('title', 'PayPal Checkout')

@section('content')
<div class="col-span-full">
    <section class="space-y-6">
        <div class="space-y-8">
            <div class="relative bg-white p-4 md:p-8">
                
                <main class="w-full max-w-xl mx-auto">
                    <h1 class="text-2xl font-semibold tracking-tight text-gray-900 mb-8">
                        Complete Your Payment
                    </h1>

                    <div class="space-y-4 text-sm">
                        <p class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <strong class="text-gray-500 font-medium">Order ID:</strong>
                            <span class="font-medium text-gray-900">{{ $order->id }}</span>
                        </p>

                        <p class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <strong class="text-gray-500 font-medium">Order Total (KHR):</strong>
                            <span class="font-medium text-gray-900">៛{{ number_format((float) $order->grand_total, 2) }}</span>
                        </p>

                        <p class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <strong class="text-gray-500 font-medium">PayPal Amount (USD):</strong>
                            <span class="font-bold text-gray-900">${{ $usdAmount }} {{ $currency }}</span>
                        </p>
                    </div>

                    <div class="mt-6 rounded-md bg-red-50 p-4 border border-red-100">
                        <p class="text-sm text-red-700">
                            Sandbox demonstration only. The KHR-to-USD rate is configurable and is not a live exchange rate.
                        </p>
                    </div>

                    <!-- PayPal Button Container -->
                    <div id="paypal-button-container" class="mt-8 relative z-0"></div>
                    
                    <!-- Message Area -->
                    <p id="paypal-message" role="status" class="mt-4 text-center text-sm font-medium text-gray-600"></p>
                </main>

            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- PayPal JS SDK -->
<script src="https://www.paypal.com/sdk/js?client-id={{ urlencode(config('paypal.client_id')) }}&currency={{ urlencode($currency) }}&intent=capture"></script>

<script>
    const createOrderUrl = @json(
        route('paypal.order.create', ['order' => $order->id])
    );

    const csrfToken = @json(csrf_token());
    const message = document.getElementById('paypal-message');

    paypal.Buttons({
        createOrder: async function () {
            message.textContent = 'Preparing PayPal checkout...';
            message.classList.remove('text-red-500', 'text-green-600');
            message.classList.add('text-gray-600');

            const response = await fetch(createOrderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (!response.ok || !data.id) {
                throw new Error(
                    data.message || 'Unable to create PayPal order.'
                );
            }

            message.textContent = '';
            return data.id;
        },

        onApprove: function () {
            message.textContent = 'PayPal approval received. Final payment capture and order confirmation will be implemented in Part 71.';
            message.classList.remove('text-gray-600', 'text-red-500');
            message.classList.add('text-green-600');
        },

        onCancel: function () {
            message.textContent = 'Checkout cancelled. Our order remains pending.';
            message.classList.remove('text-gray-600', 'text-green-600');
            message.classList.add('text-amber-600');
        },

        onError: function (error) {
            console.error('PayPal checkout error:', error);
            
            message.textContent = 'Unable to continue with PayPal. Please try again.';
            message.classList.remove('text-gray-600', 'text-green-600');
            message.classList.add('text-red-500');
        }
    }).render('#paypal-button-container');
</script>
@endpush