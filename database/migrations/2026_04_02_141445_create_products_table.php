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
        Schema::create('products', function (Blueprint $table) {
            
            $table->id();
        
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete()
                ->index();
        
            $table->string('name');
            $table->string('slug')->unique();
        
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
        
            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
        
            // Inventory
            $table->string('sku')->unique();
            $table->integer('stock')->default(0);
        
            // Product Image
            $table->string('image')->nullable();
        
            // Product visibility
            $table->boolean('featured')->default(0);
            $table->boolean('status')->default(1);
        
            // Analytics (future AI usage)
            $table->integer('views')->default(0);
            $table->integer('sales_count')->default(0);
        
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
        
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
