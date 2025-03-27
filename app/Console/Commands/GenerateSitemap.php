<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate all sitemap XML files';

    public function handle()
    {
        $this->info('Generating sitemaps...');
        
        // Создаем временную папку для хранения sitemap
        $tempDir = storage_path('app/temp_sitemaps');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        // 1. Генерируем основной sitemap.xml (индекс)
        $this->generateSitemapIndex($tempDir);
        
        // 2. Генерируем sitemap для основных страниц
        $this->generateMainSitemap($tempDir);
        
        // 3. Генерируем sitemap для категорий
        $this->generateCategoriesSitemap($tempDir);
        
        // 4. Генерируем sitemap для рецептов (возможно несколько файлов для крупного сайта)
        $this->generateRecipesSitemap($tempDir);
        
        // 5. Генерируем sitemap для пользователей
        $this->generateUsersSitemap($tempDir);
        
        // 6. Копируем все файлы из временной папки в публичную директорию
        $this->copyFilesToPublic($tempDir);
        
        $this->info('All sitemaps generated successfully!');
        return 0;
    }
    
    /**
     * Генерирует основной sitemap index
     */
    private function generateSitemapIndex($tempDir)
    {
        $this->info('Generating sitemap index...');
        
        // Получаем последние даты обновления для каждого типа контента
        $categoriesLastMod = $this->getLastModifiedDate(Category::class);
        $recipesLastMod = $this->getLastModifiedDate(Recipe::class, ['is_published' => true]);
        $usersLastMod = $this->getLastModifiedDate(User::class);
        
        $sitemaps = [
            [
                'name' => 'Основные страницы',
                'url' => url('sitemap-main.xml'),
                'lastmod' => now()->toIso8601String()
            ],
            [
                'name' => 'Категории',
                'url' => url('sitemap-categories.xml'),
                'lastmod' => $categoriesLastMod->toIso8601String()
            ],
            [
                'name' => 'Рецепты',
                'url' => url('sitemap-recipes.xml'),
                'lastmod' => $recipesLastMod->toIso8601String()
            ],
            [
                'name' => 'Пользователи',
                'url' => url('sitemap-users.xml'),
                'lastmod' => $usersLastMod->toIso8601String()
            ]
        ];
        
        $content = view('sitemap.index', compact('sitemaps'))->render();
        file_put_contents($tempDir . '/sitemap.xml', $content);
    }
    
    /**
     * Получить дату последнего обновления для модели
     */
    private function getLastModifiedDate($modelClass, $conditions = [])
    {
        $query = $modelClass::query();
        
        // Применяем условия, если они есть
        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }
        
        // Получаем самую последнюю дату обновления
        $latest = $query->latest('updated_at')->first();
        
        // Если есть данные, возвращаем дату обновления, иначе текущую дату
        return $latest ? $latest->updated_at : now();
    }
    
    /**
     * Генерирует sitemap для основных страниц
     */
    private function generateMainSitemap($tempDir)
    {
        $this->info('Generating main pages sitemap...');
        
        $recipesLastMod = $this->getLastModifiedDate(Recipe::class, ['is_published' => true]);
        $categoriesLastMod = $this->getLastModifiedDate(Category::class);
        
        $routes = [
            [
                'url' => url('/'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => route('recipes.index'),
                'lastmod' => $recipesLastMod->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'url' => route('categories.index'),
                'lastmod' => $categoriesLastMod->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'url' => route('search'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'url' => route('legal.terms'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            [
                'url' => route('legal.disclaimer'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            [
                'url' => route('legal.dmca'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
        ];
        
        $content = view('sitemap.urls', ['urls' => $routes])->render();
        file_put_contents($tempDir . '/sitemap-main.xml', $content);
    }
    
    /**
     * Генерирует sitemap для категорий
     */
    private function generateCategoriesSitemap($tempDir)
    {
        $this->info('Generating categories sitemap...');
        
        $categories = Category::all();
        $urls = [];
        
        foreach ($categories as $category) {
            $urls[] = [
                'url' => route('categories.show', $category->slug),
                'lastmod' => $category->updated_at ? $category->updated_at->toIso8601String() : now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ];
        }
        
        $content = view('sitemap.urls', ['urls' => $urls])->render();
        file_put_contents($tempDir . '/sitemap-categories.xml', $content);
    }
    
    /**
     * Генерирует sitemap для рецептов
     */
    private function generateRecipesSitemap($tempDir)
    {
        $this->info('Generating recipes sitemap...');
        
        // Получаем только опубликованные рецепты
        $recipes = Recipe::where('is_published', true)->get();
        $this->info('Found ' . $recipes->count() . ' published recipes');
        
        $urls = [];
        
        foreach ($recipes as $recipe) {
            // Рассчитываем приоритет на основе просмотров и даты
            $priority = $this->calculateRecipePriority($recipe);
            
            $urls[] = [
                'url' => route('recipes.show', $recipe->slug),
                'lastmod' => $recipe->updated_at ? $recipe->updated_at->toIso8601String() : $recipe->created_at->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => (string)$priority
            ];
        }
        
        // Для большого сайта может потребоваться разделение файлов (макс. ~50000 URL на файл для sitemap)
        $content = view('sitemap.urls', ['urls' => $urls])->render();
        file_put_contents($tempDir . '/sitemap-recipes.xml', $content);
    }
    
    /**
     * Генерирует sitemap для пользователей
     */
    private function generateUsersSitemap($tempDir)
    {
        $this->info('Generating users sitemap...');
        
        // Включаем только пользователей, у которых есть опубликованные рецепты
        $users = User::whereHas('recipes', function($query) {
            $query->where('is_published', true);
        })->get();
        
        $urls = [];
        
        foreach ($users as $user) {
            $recipeCount = $user->recipes()->where('is_published', true)->count();
            
            // Рассчитываем приоритет на основе количества рецептов
            $priority = 0.3;
            if ($recipeCount > 50) {
                $priority = 0.6;
            } elseif ($recipeCount > 20) {
                $priority = 0.5;
            } elseif ($recipeCount > 5) {
                $priority = 0.4;
            }
            
            $urls[] = [
                'url' => route('profile.show', $user->id),
                'lastmod' => $user->updated_at ? $user->updated_at->toIso8601String() : now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => (string)$priority
            ];
        }
        
        $content = view('sitemap.urls', ['urls' => $urls])->render();
        file_put_contents($tempDir . '/sitemap-users.xml', $content);
    }
    
    /**
     * Копирует все файлы из временной папки в публичную директорию
     */
    private function copyFilesToPublic($tempDir)
    {
        $this->info('Copying sitemap files to public directory...');
        
        $files = glob($tempDir . '/*.xml');
        
        foreach ($files as $file) {
            $filename = basename($file);
            copy($file, public_path($filename));
            $this->info('Copied: ' . $filename);
        }
    }
    
    /**
     * Рассчитывает приоритет для рецепта на основе его популярности и даты
     */
    private function calculateRecipePriority($recipe)
    {
        // Базовый приоритет
        $priority = 0.5;
        
        // Увеличиваем приоритет на основе просмотров
        if ($recipe->views > 1000) {
            $priority += 0.3;
        } elseif ($recipe->views > 500) {
            $priority += 0.2;
        } elseif ($recipe->views > 100) {
            $priority += 0.1;
        } elseif ($recipe->views > 10) {
            $priority += 0.05;
        }
        
        // Уменьшаем приоритет для старых рецептов
        $ageInDays = $recipe->created_at->diffInDays(now());
        if ($ageInDays > 365) {
            $priority -= 0.1;
        } elseif ($ageInDays > 180) {
            $priority -= 0.05;
        }
        
        // Ограничиваем приоритет между 0.1 и 0.9
        $priority = max(0.1, min(0.9, $priority));
        
        return $priority;
    }
}
