<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Проверяем, существует ли уже колонка role
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('email'); // Возможные значения: 'user', 'admin'
            });

            // Обновляем первого пользователя до администратора (если он существует)
            $user = DB::table('users')->first();
            if ($user) {
                DB::table('users')->where('id', $user->id)->update(['role' => 'admin']);
            }
        } else {
            // Если колонка уже существует, убедимся, что есть хотя бы один админ
            $adminExists = DB::table('users')->where('role', 'admin')->exists();
            if (!$adminExists) {
                $user = DB::table('users')->first();
                if ($user) {
                    DB::table('users')->where('id', $user->id)->update(['role' => 'admin']);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Проверяем, существует ли колонка role, и удаляем ее только если она существует
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
