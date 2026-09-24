<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('delivery_name');

            $table->string('delivery_phone');

            $table->text('delivery_address');

            $table->string('delivery_city');

            $table->string('delivery_state')
                ->nullable();

            $table->string('delivery_country');

            $table->string('delivery_postal_code')
                ->nullable();

            $table->decimal('subtotal', 10, 2);

            $table->decimal('coupon_discount', 10, 2)
                ->default(0);

            $table->decimal('shipping_charge', 10, 2)
                ->default(0);

            $table->decimal('grand_total', 10, 2);

            $table->string('payment_method')
                ->default('cod');

            $table->string('payment_status')
                ->default('pending');

            $table->string('order_status')
                ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
