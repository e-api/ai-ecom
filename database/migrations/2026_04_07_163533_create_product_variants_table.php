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
        Schema::create('product_variants', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Variant attribute (for now size)
            $table->string('size');

            // SKU for each variant
            $table->string('sku')->unique();

            // Price for variant
            $table->decimal('price', 10, 2);

            // Stock per variant
            $table->integer('stock')->default(0);

            // Sorting
            $table->integer('position')->default(0);

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
        Schema::dropIfExists('product_variants');
    }
};
