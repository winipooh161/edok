<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Добавляем команду обновления структуры ингредиентов каждый день в полночь
        $schedule->command('recipes:update-ingredients')->daily();
        $schedule->command('sitemap:generate')->daily();
        
        // Генерируем sitemap каждый день в 3:00 утра
        $schedule->command('sitemap:generate')->dailyAt('03:00');
        
        // Еженедельно заново индексируем все рецепты для обновления сео-данных
        $schedule->command('recipes:reindex')->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
    /**
     * Get the commands that should be registered.
     *
     * @return array
     */
    protected $commands = [
        \App\Console\Commands\UpdateIngredientsStructure::class,
        \App\Console\Commands\GenerateSitemap::class,
        \App\Console\Commands\ReindexRecipes::class,
    ];
}
