<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Category;
use App\Services\SearchService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected $searchService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Получаем последние рецепты для отображения на главной странице
        $latestRecipes = Recipe::where('is_published', true)
                               ->latest()
                               ->take(6)
                               ->get();
        
        // Получаем популярные категории
        $popularCategories = Category::withCount(['recipes' => function($query) {
            $query->where('is_published', true);
        }])
        ->orderByDesc('recipes_count')
        ->take(8)
        ->get();
        
        // Быстрые рецепты (до 30 минут)
        $quickRecipes = Recipe::where('is_published', true)
                             ->where('cooking_time', '<=', 30)
                             ->inRandomOrder()
                             ->take(4)
                             ->get();
        
        // Получаем популярные поисковые запросы из кэша или генерируем новые
        $popularSearchTerms = Cache::remember('popular_search_terms', 60*24, function() {
            return $this->searchService->getPopularSearchTerms(10);
        });
        
        // Получаем сезонные рецепты
        $seasonalRecipes = $this->getSeasonalRecipes();
        
        return view('home', compact(
            'latestRecipes', 
            'popularCategories', 
            'quickRecipes', 
            'popularSearchTerms',
            'seasonalRecipes'
        ));
    }
    
    /**
     * Автодополнение для поиска
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Получаем подсказки из SearchService
        $suggestions = $this->searchService->getAutocompleteSuggestions($query);
        
        return response()->json($suggestions);
    }
    
    /**
     * Получить сезонные рецепты в зависимости от текущего месяца
     */
    private function getSeasonalRecipes()
    {
        $currentMonth = (int) date('m');
        $seasonalKeywords = [];
        
        // Определяем сезонные ключевые слова в зависимости от времени года
        if ($currentMonth >= 3 && $currentMonth <= 5) {
            // Весна
            $seasonalKeywords = ['весенний', 'зелень', 'редис', 'спаржа', 'щавель'];
        } elseif ($currentMonth >= 6 && $currentMonth <= 8) {
            // Лето
            $seasonalKeywords = ['летний', 'ягоды', 'окрошка', 'гриль', 'салат'];
        } elseif ($currentMonth >= 9 && $currentMonth <= 11) {
            // Осень
            $seasonalKeywords = ['осенний', 'тыква', 'грибы', 'яблоки', 'айва'];
        } else {
            // Зима
            $seasonalKeywords = ['зимний', 'горячий', 'суп', 'рождественский', 'глинтвейн'];
        }
        
        // Ищем рецепты с сезонными ключевыми словами
        $recipes = Recipe::where('is_published', true)
            ->where(function($query) use ($seasonalKeywords) {
                foreach ($seasonalKeywords as $keyword) {
                    $query->orWhere('title', 'like', '%' . $keyword . '%')
                          ->orWhere('ingredients', 'like', '%' . $keyword . '%')
                          ->orWhere('description', 'like', '%' . $keyword . '%');
                }
            })
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return [
            'recipes' => $recipes,
            'keywords' => $seasonalKeywords,
            'season' => $this->getCurrentSeasonName()
        ];
    }
    
    /**
     * Получить название текущего сезона
     */
    private function getCurrentSeasonName()
    {
        $currentMonth = (int) date('m');
        
        if ($currentMonth >= 3 && $currentMonth <= 5) {
            return 'Весна';
        } elseif ($currentMonth >= 6 && $currentMonth <= 8) {
            return 'Лето';
        } elseif ($currentMonth >= 9 && $currentMonth <= 11) {
            return 'Осень';
        } else {
            return 'Зима';
        }
    }
}
