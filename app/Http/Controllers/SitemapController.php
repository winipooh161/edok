<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Главный Sitemap index, ссылающийся на отдельные карты сайта
     */
    public function index()
    {
        $sitemaps = [
            [
                'name' => 'Основные страницы',
                'url' => route('sitemap.main'),
                'lastmod' => now()->toIso8601String()
            ],
            [
                'name' => 'Категории',
                'url' => route('sitemap.categories'),
                'lastmod' => Category::max('updated_at') ? Category::max('updated_at')->toIso8601String() : now()->toIso8601String()
            ],
            [
                'name' => 'Рецепты',
                'url' => route('sitemap.recipes'),
                'lastmod' => Recipe::where('is_published', true)->max('updated_at') ? 
                    Recipe::where('is_published', true)->max('updated_at')->toIso8601String() : 
                    now()->toIso8601String()
            ],
            [
                'name' => 'Пользователи',
                'url' => route('sitemap.users'),
                'lastmod' => User::max('updated_at') ? User::max('updated_at')->toIso8601String() : now()->toIso8601String()
            ]
        ];
        
        return response()->view('sitemap.index', compact('sitemaps'))
            ->header('Content-Type', 'text/xml');
    }
    
    /**
     * Главные страницы сайта
     */
    public function main()
    {
        $routes = [
            [
                'url' => url('/'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => route('recipes.index'),
                'lastmod' => Recipe::where('is_published', true)->max('updated_at') ? 
                    Recipe::where('is_published', true)->max('updated_at')->toIso8601String() : 
                    now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'url' => route('categories.index'),
                'lastmod' => Category::max('updated_at') ? 
                    Category::max('updated_at')->toIso8601String() : 
                    now()->toIso8601String(),
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
        
        return response()->view('sitemap.urls', ['urls' => $routes])
            ->header('Content-Type', 'text/xml');
    }
    
    /**
     * Категории
     */
    public function categories()
    {
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
        
        return response()->view('sitemap.urls', ['urls' => $urls])
            ->header('Content-Type', 'text/xml');
    }
    
    /**
     * Рецепты (возможно постраничное отображение для большого количества)
     */
    public function recipes()
    {
        // Получаем только опубликованные рецепты
        $recipes = Recipe::where('is_published', true)->get();
        $urls = [];
        
        foreach ($recipes as $recipe) {
            // Рассчитываем приоритет на основе просмотров (популярные рецепты имеют более высокий приоритет)
            $priority = 0.5;
            if ($recipe->views > 1000) {
                $priority = 0.9;
            } elseif ($recipe->views > 500) {
                $priority = 0.8;
            } elseif ($recipe->views > 100) {
                $priority = 0.7;
            } elseif ($recipe->views > 10) {
                $priority = 0.6;
            }
            
            $urls[] = [
                'url' => route('recipes.show', $recipe->slug),
                'lastmod' => $recipe->updated_at ? $recipe->updated_at->toIso8601String() : now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => (string)$priority
            ];
        }
        
        return response()->view('sitemap.urls', ['urls' => $urls])
            ->header('Content-Type', 'text/xml');
    }
    
    /**
     * Профили пользователей
     */
    public function users()
    {
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
        
        return response()->view('sitemap.urls', ['urls' => $urls])
            ->header('Content-Type', 'text/xml');
    }
}
