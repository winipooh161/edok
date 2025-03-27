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
        Schema::create('ingredient_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('recipe_id');
            $table->integer('position')->default(0);
            $table->timestamps();
            
            $table->foreign('recipe_id')
                  ->references('id')
                  ->on('recipes')
                  ->onDelete('cascade');
        });
        
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('quantity')->nullable();
            $table->string('unit')->default('по вкусу');
            $table->boolean('optional')->default(false);
            $table->string('state')->nullable();
            $table->string('notes')->nullable();
            $table->enum('priority', ['high', 'normal', 'low'])->default('normal');
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
            
            $table->foreign('recipe_id')
                  ->references('id')
                  ->on('recipes')
                  ->onDelete('cascade');
                  
            $table->foreign('group_id')
                  ->references('id')
                  ->on('ingredient_groups')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('ingredient_groups');
    }
};
