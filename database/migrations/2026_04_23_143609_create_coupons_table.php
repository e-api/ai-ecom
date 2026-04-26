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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
	        
            // Coupon code
            $table->string('code')->unique();

            // ✅ Manual or Automatic
            $table->enum('code_type', ['manual', 'auto'])->default('manual');

            // Discount type
            $table->enum('type', ['fixed', 'percentage']);

            // Discount value
            $table->decimal('value', 10, 2);

            // Minimum cart value
            $table->decimal('min_cart_value', 10, 2)->nullable();

            // Usage control
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);

            // Expiry
            $table->date('expires_at')->nullable();

            // Status
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
