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
        Schema::create('search_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('query');
            $table->integer('results_count')->default(0);
            $table->foreignId('clicked_recipe_id')->nullable()->constrained('recipes')->onDelete('set null');
            $table->timestamps();
            
            // Индексы для быстрого поиска
            $table->index(['user_id', 'created_at']);
            $table->index('query');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};
