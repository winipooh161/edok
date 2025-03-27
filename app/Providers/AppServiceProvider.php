<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Используем Bootstrap для пагинации
        Paginator::useBootstrap();
        
        // Устанавливаем длину строки по умолчанию для MySQL
        Schema::defaultStringLength(191);
        
        // Сохраняем параметры фильтрации и сортировки при пагинации
        Paginator::defaultView('pagination::bootstrap-5');
        
        // Явно указываем метод withQueryString для включения всех существующих параметров в пагинацию
        // Это предотвращает потерю фильтров при переходе между страницами
    }
}
