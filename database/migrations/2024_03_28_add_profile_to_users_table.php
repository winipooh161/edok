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
        // Сначала проверяем, существует ли колонка role
        if (!Schema::hasColumn('users', 'role')) {
            // Если роль не существует, сначала добавляем ее
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('email');
            });
            
            // Обновляем первого пользователя до администратора (если он существует)
            $user = DB::table('users')->first();
            if ($user) {
                DB::table('users')->where('id', $user->id)->update(['role' => 'admin']);
            }
        }
        
        // Теперь можем добавить поля профиля, так как role уже существует
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('role');
            $table->text('bio')->nullable()->after('avatar');
            $table->boolean('is_verified')->default(false)->after('bio');
            $table->timestamp('last_login_at')->nullable()->after('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'is_verified', 'last_login_at']);
            
            // Проверяем, была ли добавлена роль в этой миграции
            if (!Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
