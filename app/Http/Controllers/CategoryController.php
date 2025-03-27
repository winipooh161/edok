<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $seoService;
    
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }
    
    /**
     * Отображение списка всех категорий
     */
    public function index()
    {
        // Получаем все категории с количеством рецептов
        $categories = Category::withCount(['recipes' => function($query) {
            $query->where('is_published', true);
        }])
        ->orderBy('name')
        ->get();
        
        // Группируем категории по первой букве для алфавитного указателя
        $categoriesByLetter = $categories->groupBy(function($category) {
            return mb_strtoupper(mb_substr($category->name, 0, 1));
        })->sortKeys();
        
        // Получаем популярные категории для отображения в топе
        $popularCategories = $categories->sortByDesc('recipes_count')->take(8);
        
        // Получаем несколько случайных рецептов для отображения
        $featuredRecipes = Recipe::where('is_published', true)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('categories.index', compact('categories', 'categoriesByLetter', 'popularCategories', 'featuredRecipes'));
    }
    
    /**
     * Показать страницу с рецептами конкретной категории
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Параметры для фильтрации рецептов
        $search = $request->input('search');
        $cookingTime = $request->input('cooking_time');
        $sort = $request->input('sort', 'newest');
        
        // Инициализация массива терминов для поиска
        $searchTerms = $search ? preg_split('/\s+/', trim(strtolower($search))) : [];
        
        // Базовый запрос для получения рецептов категории
        $query = $category->recipes()
            ->where('is_published', true);
        
        // Поиск по тексту внутри категории
        if (!empty($search)) {
            // Разбиваем поисковый запрос на слова
            $searchTerms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
            
            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    if (mb_strlen($term) >= 3) {
                        $q->where(function($sq) use ($term) {
                            $sq->where('title', 'like', '%' . $term . '%')
                               ->orWhere('description', 'like', '%' . $term . '%')
                               ->orWhere('ingredients', 'like', '%' . $term . '%');
                        });
                    }
                }
            });
            
            // Добавляем вычисляемое поле для сортировки по релевантности
            $query->addSelect([
                '*',
                DB::raw('(
                    CASE 
                        WHEN title LIKE "%' . $search . '%" THEN 10
                        ELSE 0 
                    END +
                    CASE 
                        WHEN description LIKE "%' . $search . '%" THEN 5
                        ELSE 0 
                    END +
                    CASE 
                        WHEN ingredients LIKE "%' . $search . '%" THEN 3
                        ELSE 0 
                    END
                ) as search_relevance')
            ]);
        }
        
        // Фильтрация по времени приготовления
        if (!empty($cookingTime)) {
            $query->where('cooking_time', '<=', (int) $cookingTime);
        }
        
        // Сортировка результатов
        switch ($sort) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'cooking_time_asc':
                $query->orderBy('cooking_time', 'asc');
                break;
            case 'cooking_time_desc':
                $query->orderBy('cooking_time', 'desc');
                break;
            case 'relevance':
                if (!empty($search)) {
                    $query->orderBy('search_relevance', 'desc');
                } else {
                    $query->latest();
                }
                break;
            case 'latest':
            default:
                $query->latest();
        }
        
        // Получаем рецепты с пагинацией
        $recipes = $query->paginate(12)->withQueryString();
        
        // Расчёт процентного соответствия для каждого рецепта при поиске
        if (!empty($search) && !empty($searchTerms)) {
            foreach ($recipes as $recipe) {
                $recipe->relevance_percent = $this->calculateRelevancePercent($recipe, $searchTerms);
            }
        }
        
        // Получаем другие популярные категории для боковой панели
        $otherCategories = Category::withCount(['recipes' => function($query) {
            $query->where('is_published', true);
        }])
        ->where('id', '!=', $category->id)
        ->orderByDesc('recipes_count')
        ->take(10)
        ->get();
        
        // Получаем популярные рецепты этой категории
        $popularRecipes = $category->recipes()
            ->where('is_published', true)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();
        
        // Генерируем структурированные данные для категории
        $schemaData = $this->generateCategorySchema($category, $recipes, $popularRecipes);
        
        return view('categories.show', compact(
            'category', 
            'recipes', 
            'otherCategories', 
            'popularRecipes',
            'search',
            'cookingTime',
            'sort',
            'searchTerms',
            'schemaData'
        ))->with('seo', $this->seoService);
    }
    
    /**
     * Расчет процентного соответствия рецепта поисковому запросу
     */
    private function calculateRelevancePercent($recipe, $searchTerms)
    {
        $totalTerms = count($searchTerms);
        $matchedTerms = 0;
        
        // Объединяем все текстовые поля для поиска
        $allText = strtolower($recipe->title . ' ' . $recipe->description . ' ' . $recipe->ingredients);
        
        foreach ($searchTerms as $term) {
            if (mb_strlen($term) >= 3 && Str::contains($allText, strtolower($term))) {
                $matchedTerms++;
            }
        }
        
        if ($totalTerms > 0) {
            return round(($matchedTerms / $totalTerms) * 100);
        }
        
        return 0;
    }
    
    /**
     * Генерирует структурированные данные Schema.org для категории
     */
    private function generateCategorySchema($category, $recipes, $popularRecipes)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name . ' - рецепты',
            'description' => $this->seoService->getCategoryDescription($category),
            'url' => route('categories.show', $category->slug),
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => []
            ]
        ];
        
        // Добавляем рецепты как элементы списка
        $position = 1;
        foreach ($recipes as $recipe) {
            $schema['mainEntity']['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'url' => route('recipes.show', $recipe->slug),
                'name' => $recipe->title,
                'image' => $recipe->getImageUrl()
            ];
        }
        
        // Добавляем хлебные крошки
        $schema['breadcrumb'] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Главная',
                    'item' => url('/')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Категории',
                    'item' => route('categories.index')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $category->name,
                    'item' => route('categories.show', $category->slug)
                ]
            ]
        ];
        
        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
