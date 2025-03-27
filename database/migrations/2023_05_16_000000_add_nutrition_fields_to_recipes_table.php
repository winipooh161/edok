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
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('calories')->nullable()->after('cooking_time');
            $table->integer('proteins')->nullable()->after('calories');
            $table->integer('fats')->nullable()->after('proteins');
            $table->integer('carbs')->nullable()->after('fats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['calories', 'proteins', 'fats', 'carbs']);
        });
    }
};
