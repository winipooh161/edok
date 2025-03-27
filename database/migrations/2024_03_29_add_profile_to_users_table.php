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
        // Проверяем, существуют ли уже эти колонки
        if (!Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar')->nullable()->after('role');
                $table->text('bio')->nullable()->after('avatar');
                $table->boolean('is_verified')->default(false)->after('bio');
                $table->timestamp('last_login_at')->nullable()->after('is_verified');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Оставляем пустым, так как удаление этих колонок должно происходить 
        // в миграции 2024_03_28_add_profile_to_users_table
    }
};
