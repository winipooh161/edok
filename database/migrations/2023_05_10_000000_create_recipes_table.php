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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('ingredients');
            $table->text('instructions');
            $table->string('image_url')->nullable();
            $table->string('source_url')->nullable();
            $table->string('slug')->unique();
            $table->integer('cooking_time')->nullable(); // Время приготовления в минутах
            $table->boolean('is_published')->default(true); // Статус публикации
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
