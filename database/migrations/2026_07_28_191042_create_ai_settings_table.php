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
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('openai_model')
                ->default('gpt-4.1-mini');
            $table->decimal('temperature', 3, 2)
                ->default(0.7);
            $table->integer('max_tokens')
                ->default(600);
            $table->string('writing_tone')
                ->default('Professional');
            $table->integer('description_length')
                ->default(120);
            $table->integer('short_description_length')
                ->default(40);
            $table->integer('keyword_count')
                ->default(10);
            $table->text('system_prompt')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
