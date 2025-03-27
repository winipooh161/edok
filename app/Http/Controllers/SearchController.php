<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    protected $searchService;
    
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }
    
    /**
     * Функция поиска рецептов
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $searchType = $request->input('search_type', 'title');
        $ingredients = $request->input('ingredients', []);
        $categoryId = $request->input('category');
        $cookingTime = $request->input('cooking_time');
        $hasImage = $request->has('has_image');
        $sort = $request->input('sort', 'relevance');
        $fuzzySearch = $request->input('fuzzy_search', true);
        
        // Используем SearchService для поиска
        $recipesQuery = $this->searchService->searchRecipes($query, [
            'save_history' => true,
            'fuzzy_search' => $fuzzySearch
        ]);
        
        // Если выбран поиск по ингредиентам
        if ($searchType === 'ingredients' && !empty($ingredients)) {
            $recipesQuery->where(function($q) use ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    // Для ингредиентов используем строгое соответствие
                    $q->where('ingredients', 'like', '%' . $ingredient . '%');
                }
            });
        }
        
        // Фильтрация по категории
        if (!empty($categoryId)) {
            $recipesQuery->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        
        // Фильтрация по времени приготовления
        if (!empty($cookingTime)) {
            $recipesQuery->where('cooking_time', '<=', (int) $cookingTime);
        }
        
        // Только рецепты с фото
        if ($hasImage) {
            $recipesQuery->whereNotNull('image_url');
        }
        
        // Сортировка результатов
        switch ($sort) {
            case 'latest':
                $recipesQuery->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $recipesQuery->orderBy('views', 'desc');
                break;
            case 'cooking_time_asc':
                $recipesQuery->orderBy('cooking_time', 'asc');
                break;
            case 'cooking_time_desc':
                $recipesQuery->orderBy('cooking_time', 'desc');
                break;
            case 'relevance':
            default:
                // По умолчанию сортировка по релевантности уже применена
                break;
        }
        
        // Получаем результаты с пагинацией
        $recipes = $recipesQuery->paginate(12)->withQueryString();
        
        // Сохраняем количество результатов в историю поиска
        if (!empty($query)) {
            $this->searchService->updateSearchResultsCount($query, $recipes->total());
        }
        
        // Разбиваем поисковый запрос на слова для подсветки
        $searchTerms = [];
        if (!empty($query)) {
            $searchTerms = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
            $searchTerms = array_filter($searchTerms, function($term) {
                return mb_strlen($term) >= 3;
            });
        }
        
        // Расчёт процентного соответствия для каждого рецепта
        if (!empty($query) && !empty($searchTerms)) {
            foreach ($recipes as $recipe) {
                // Если есть fuzzy_relevance, используем его, иначе рассчитываем
                if (isset($recipe->fuzzy_relevance) && $recipe->fuzzy_relevance > 0) {
                    $recipe->relevance_percent = $recipe->fuzzy_relevance;
                } else {
                    $recipe->relevance_percent = $this->calculateRelevancePercent($recipe, $searchTerms);
                }
            }
        }
        
        // Получаем популярные категории для фильтра
        $categories = Category::withCount(['recipes' => function($query) {
            $query->where('is_published', true);
        }])
        ->orderBy('recipes_count', 'desc')
        ->take(10)
        ->get();
        
        // Получаем историю поиска для текущего пользователя
        $searchHistory = [];
        if (auth()->check()) {
            $searchHistory = DB::table('search_histories')
                ->select('query', DB::raw('COUNT(*) as count'))
                ->where('user_id', auth()->id())
                ->groupBy('query')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();
        }
        
        // Получаем подсказки для текущего запроса
        $searchSuggestions = [];
        if (!empty($query) && $recipes->total() < 3) {
            $searchSuggestions = $this->getSuggestions($query);
        }
        
        return view('recipes.index', [
            'recipes' => $recipes,
            'categories' => $categories,
            'search' => $query,
            'searchTerms' => $searchTerms,
            'selectedCategory' => $categoryId,
            'selectedCookingTime' => $cookingTime,
            'searchType' => $searchType,
            'ingredients' => $ingredients,
            'searchHistory' => $searchHistory,
            'searchSuggestions' => $searchSuggestions,
            'fuzzySearch' => $fuzzySearch,
            'sort' => $sort
        ]);
    }
    
    /**
     * Сохранить клик по результату поиска
     */
    public function recordClick(Request $request)
    {
        $recipeId = $request->input('recipe_id');
        $query = $request->input('query');
        
        if (auth()->check() && $recipeId && $query) {
            // Находим последний поиск пользователя с этим запросом
            $lastSearch = DB::table('search_histories')
                ->where('user_id', auth()->id())
                ->where('query', $query)
                ->latest()
                ->first();
                
            if ($lastSearch) {
                DB::table('search_histories')
                    ->where('id', $lastSearch->id)
                    ->update(['clicked_recipe_id' => $recipeId]);
            }
        }
        
        return response()->json(['success' => true]);
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
        
        $suggestions = $this->searchService->getAutocompleteSuggestions($query);
        
        return response()->json($suggestions);
    }
    
    /**
     * Расчет процентного соответствия рецепта поисковому запросу
     */
    private function calculateRelevancePercent($recipe, $searchTerms)
    {
        $totalTerms = count($searchTerms);
        $matchedTerms = 0;
        $weightedMatches = 0;
        
        // Объединяем все текстовые поля для поиска
        $allText = strtolower($recipe->title . ' ' . $recipe->description . ' ' . $recipe->ingredients);
        
        foreach ($searchTerms as $term) {
            if (mb_strlen($term) >= 3) {
                $termLower = strtolower($term);
                
                // Проверяем наличие в разных полях с разными весами
                $weightForThisTerm = 0;
                
                if (Str::contains(strtolower($recipe->title), $termLower)) {
                    $weightForThisTerm += 2; // Высокий вес для заголовка
                }
                
                if (Str::contains(strtolower($recipe->ingredients), $termLower)) {
                    $weightForThisTerm += 1.5; // Средний вес для ингредиентов
                }
                
                if (Str::contains(strtolower($recipe->description), $termLower)) {
                    $weightForThisTerm += 1; // Нормальный вес для описания
                }
                
                if (Str::contains(strtolower($recipe->instructions), $termLower)) {
                    $weightForThisTerm += 0.5; // Низкий вес для инструкций
                }
                
                if ($weightForThisTerm > 0) {
                    $matchedTerms++;
                    $weightedMatches += $weightForThisTerm;
                }
            }
        }
        
        if ($totalTerms > 0) {
            // Базовый процент - процент найденных слов
            $basePercent = ($matchedTerms / $totalTerms) * 100;
            
            // Весовой процент - с учетом веса найденных слов
            $maxPossibleWeight = $totalTerms * 5; // Максимально возможный вес
            $weightPercent = ($weightedMatches / $maxPossibleWeight) * 100;
            
            // Комбинированный процент (60% базовый + 40% весовой)
            $combinedPercent = ($basePercent * 0.6) + ($weightPercent * 0.4);
            
            return round($combinedPercent);
        }
        
        return 0;
    }
    
    /**
     * Получить предложения для поиска при малом количестве результатов
     */
    private function getSuggestions($query)
    {
        // Разбиваем запрос на слова
        $terms = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
        
        // Если запрос состоит из одного слова, предлагаем похожие слова
        if (count($terms) === 1 && mb_strlen($terms[0]) >= 3) {
            $term = $terms[0];
            
            // Ищем рецепты с похожими словами
            $recipes = Recipe::where('is_published', true)
                ->select('title')
                ->limit(100)
                ->get();
                
            $allWords = collect();
            
            foreach ($recipes as $recipe) {
                $words = preg_split('/\s+/', $recipe->title, -1, PREG_SPLIT_NO_EMPTY);
                $allWords = $allWords->merge($words);
            }
            
            $allWords = $allWords->map(function($word) {
                return preg_replace('/[^\p{L}\p{N}]/u', '', $word);
            })->unique()->filter();
            
            $suggestions = [];
            
            foreach ($allWords as $word) {
                if (mb_strlen($word) >= 3) {
                    $distance = $this->searchService->levenshtein_utf8(mb_strtolower($term), mb_strtolower($word));
                    
                    if ($distance <= 2 && $distance > 0) {
                        $suggestions[] = $word;
                    }
                }
            }
            
            return array_slice($suggestions, 0, 5);
        }
        
        // Если запрос из нескольких слов, предлагаем исключить некоторые слова
        if (count($terms) > 1) {
            $suggestions = [];
            
            foreach ($terms as $i => $term) {
                $newTerms = $terms;
                array_splice($newTerms, $i, 1);
                $suggestions[] = implode(' ', $newTerms);
            }
            
            return array_slice($suggestions, 0, 3);
        }
        
        return [];
    }
}
