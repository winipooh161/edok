<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;
use App\Services\IngredientParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    protected $ingredientParser;
    
    /**
     * Конструктор с проверкой аутентификации
     */
    public function __construct(IngredientParser $ingredientParser)
    {
        $this->ingredientParser = $ingredientParser;
        $this->middleware('auth');
    }

    /**
     * Отображение списка рецептов в админке
     */
    public function index(Request $request)
    {
        $query = Recipe::with(['categories', 'user']);
        
        // Для обычных пользователей показываем только их рецепты
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }
        
        // Применяем фильтр по поиску
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('ingredients', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Применяем фильтр по категории
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        
        // Применяем фильтр по статусу публикации
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_published', $request->status);
        }

        // Фильтр по автору (только для админов)
        if (auth()->user()->isAdmin() && $request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $recipes = $query->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();
        
        // Для админов добавляем список пользователей для фильтра
        $users = null;
        if (auth()->user()->isAdmin()) {
            $users = User::orderBy('name')->get();
        }
        
        return view('admin.recipes.index', compact('recipes', 'categories', 'users'));
    }

    /**
     * Форма создания рецепта
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.recipes.create', compact('categories'));
    }

    /**
     * Сохранение нового рецепта
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'cooking_time' => 'nullable|integer|min:1',
            'servings' => 'nullable|integer|min:1',
            'calories' => 'nullable|integer|min:0',
            'proteins' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'terms_agreement' => 'required|accepted',
        ]);
        
        try {
            // Создаем рецепт
            $recipe = new Recipe();
            $recipe->title = $request->title;
            
            // Генерируем уникальный слаг
            $baseSlug = Str::slug($request->title);
            $slug = $this->generateUniqueSlug($baseSlug);
            $recipe->slug = $slug;
            
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('recipes', 'public');
                $recipe->image_url = Storage::url($path);
            }
            
            $recipe->description = $request->description;
            $recipe->ingredients = $request->ingredients;
            $recipe->instructions = $request->instructions;
            $recipe->cooking_time = $request->cooking_time;
            $recipe->servings = $request->servings;
            $recipe->calories = $request->calories;
            $recipe->proteins = $request->proteins;
            $recipe->fats = $request->fats;
            $recipe->carbs = $request->carbs;
            $recipe->is_published = $request->has('is_published');
            
            // Добавляем привязку к пользователю
            $recipe->user_id = auth()->id();
            $recipe->save();
            
            // Прикрепляем категории
            if ($request->has('categories')) {
                $recipe->categories()->attach($request->categories);
            }
            
            // Обрабатываем ингредиенты
            if ($request->ingredients) {
                // Генерируем структурированные данные
                $parser = new IngredientParser();
                $structuredData = $parser->parseToStructuredData($request->ingredients);
                
                // Сохраняем в additional_data
                $additionalData = [];
                $additionalData['structured_ingredients'] = $structuredData['ingredients'];
                $additionalData['ingredients_json'] = json_encode($structuredData, JSON_UNESCAPED_UNICODE);
                $recipe->additional_data = json_encode($additionalData, JSON_UNESCAPED_UNICODE);
                $recipe->save();
                
                // Опционально синхронизируем с моделями DB
                if ($request->has('use_db_ingredients')) {
                    $recipe->syncIngredientsFromJson();
                }
            }

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Рецепт успешно создан!');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при создании рецепта: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Форма редактирования рецепта
     */
    public function edit(Recipe $recipe)
    {
        $categories = Category::orderBy('name')->get();
        $selectedCategories = $recipe->categories->pluck('id')->toArray();
        return view('admin.recipes.edit', compact('recipe', 'categories', 'selectedCategories'));
    }

    /**
     * Обновление рецепта
     */
    public function update(Request $request, Recipe $recipe)
    {
        // Проверяем, имеет ли пользователь право редактировать этот рецепт
        if (!$recipe->isOwnedBy(auth()->user())) {
            return redirect()->route('admin.recipes.index')
                ->with('error', 'У вас нет доступа к редактированию этого рецепта.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'cooking_time' => 'nullable|integer|min:1',
            'servings' => 'nullable|integer|min:1',
            'calories' => 'nullable|integer|min:0',
            'proteins' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        try {
            // Обновляем рецепт
            $recipe->title = $request->title;
            
            // Если название изменилось, обновляем слаг, обеспечивая его уникальность
            if ($recipe->isDirty('title')) {
                $baseSlug = Str::slug($request->title);
                // При обновлении учитываем текущий id, чтобы не считать конфликтом сам рецепт
                $slug = $this->generateUniqueSlug($baseSlug, $recipe->id);
                $recipe->slug = $slug;
            }
            
            if ($request->hasFile('image')) {
                // Удаляем старое изображение, если оно существует
                if ($recipe->image_url) {
                    $oldPath = str_replace('/storage/', '', $recipe->image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('image')->store('recipes', 'public');
                $recipe->image_url = Storage::url($path);
            }
            
            $recipe->description = $request->description;
            $recipe->ingredients = $request->ingredients;
            $recipe->instructions = $request->instructions;
            $recipe->cooking_time = $request->cooking_time;
            $recipe->servings = $request->servings;
            $recipe->calories = $request->calories;
            $recipe->proteins = $request->proteins;
            $recipe->fats = $request->fats;
            $recipe->carbs = $request->carbs;
            $recipe->is_published = $request->has('is_published');
            $recipe->save();
            
            // Обновляем категории
            $recipe->categories()->sync($request->categories ?? []);
            
            // Обрабатываем ингредиенты
            if ($request->ingredients) {
                // Генерируем структурированные данные
                $parser = new IngredientParser();
                $structuredData = $parser->parseToStructuredData($request->ingredients);
                
                // Получаем существующие дополнительные данные
                $additionalData = [];
                if ($recipe->additional_data) {
                    $additionalData = is_array($recipe->additional_data) ? 
                                      $recipe->additional_data : 
                                      json_decode($recipe->additional_data, true) ?? [];
                }
                
                // Обновляем данные
                $additionalData['structured_ingredients'] = $structuredData['ingredients'];
                $additionalData['ingredients_json'] = json_encode($structuredData, JSON_UNESCAPED_UNICODE);
                $recipe->additional_data = json_encode($additionalData, JSON_UNESCAPED_UNICODE);
                $recipe->save();
                
                // Опционально синхронизируем с моделями DB
                if ($request->has('use_db_ingredients')) {
                    $recipe->syncIngredientsFromJson();
                }
            }

            return redirect()->route('admin.recipes.index')
                ->with('success', 'Рецепт успешно обновлен!');
        } catch (\Exception $e) {
            return back()->with('error', 'Произошла ошибка при обновлении рецепта: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Удаление рецепта
     */
    public function destroy(Recipe $recipe)
    {
        // Проверяем, имеет ли пользователь право удалять этот рецепт
        if (!$recipe->isOwnedBy(auth()->user())) {
            return redirect()->route('admin.recipes.index')
                ->with('error', 'У вас нет доступа к удалению этого рецепта.');
        }
        
        // Удаляем изображение
        if ($recipe->image_url) {
            $path = str_replace('/storage/', '', $recipe->image_url);
            Storage::disk('public')->delete($path);
        }
        
        $recipe->delete();
        
        return redirect()->route('admin.recipes.index')
            ->with('success', 'Рецепт успешно удален!');
    }

    /**
     * Генерирует уникальный слаг для рецепта
     *
     * @param string $baseSlug Базовый слаг
     * @param int|null $exceptId ID рецепта, который нужно исключить из проверки
     * @return string Уникальный слаг
     */
    private function generateUniqueSlug($baseSlug, $exceptId = null)
    {
        $slug = $baseSlug;
        $counter = 1;
        
        // Проверяем существование слага, исключая указанный ID
        $query = Recipe::where('slug', $slug);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        
        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            
            // Предотвращаем бесконечный цикл
            if ($counter > 100) {
                $slug = $baseSlug . '-' . uniqid();
                break;
            }
            
            $query = Recipe::where('slug', $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
        }
        
        return $slug;
    }
}
