<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RecipeController extends Controller
{
    protected $seoService;
    
    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }
    
    /**
     * Показать список рецептов
     */
    public function index(Request $request)
    {
        $query = Recipe::where('is_published', true);

        // Поиск по названию
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Сортировка
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'popular') {
                $query->orderBy('views', 'desc');
            } elseif ($sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Получаем рецепты с пагинацией
        $recipes = $query->with('categories')->paginate(12);

        // Получаем категории для фильтра
        $categories = Category::withCount('recipes')
                    ->orderBy('name')
                    ->get();

        return view('recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Показать детали рецепта
     */
    public function show(string $slug)
    {
        $recipe = Recipe::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        // Проверяем существование колонки перед увеличением счетчика
        if (Schema::hasColumn('recipes', 'views')) {
            $recipe->increment('views');
        }
        
        // Получаем похожие рецепты (по категориям)
        $categoryIds = $recipe->categories->pluck('id')->toArray();
        
        $relatedRecipes = Recipe::where('id', '!=', $recipe->id)
            ->where('is_published', true)
            ->whereHas('categories', function($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->inRandomOrder()
            ->limit(3)
            ->get();
        
        return view('recipes.show', compact('recipe', 'relatedRecipes'))->with('seo', $this->seoService);
    }
}
