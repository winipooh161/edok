<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Services\IngredientParser;

class Recipe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'ingredients',
        'instructions',
        'image_url',
        'cooking_time',
        'servings',
        'calories',
        'proteins',
        'fats',
        'carbs',
        'source_url',
        'is_published',
        'views',
        'additional_data',
        'user_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'additional_data' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            if (empty($recipe->slug)) {
                $recipe->slug = Str::slug($recipe->title);
            }
        });
    }

    // Преобразует текстовое представление ингредиентов в массив
    public function getIngredientsArrayAttribute()
    {
        return explode("\n", $this->ingredients);
    }

    // Преобразует текстовое представление инструкций в массив
    public function getInstructionsArrayAttribute()
    {
        return explode("\n", $this->instructions);
    }
    
    /**
     * Категории, к которым относится рецепт
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    /**
     * Похожие рецепты (из тех же категорий)
     */
    public function relatedRecipes($limit = 3)
    {
        $categoryIds = $this->categories()->pluck('categories.id');
        
        return self::where('id', '!=', $this->id)
            ->where('is_published', true)
            ->whereHas('categories', function($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Получить структурированные ингредиенты рецепта
     */
    public function getStructuredIngredientsAttribute()
    {
        if (empty($this->additional_data)) {
            // Если additional_data отсутствует, пробуем получить структурированные ингредиенты на лету
            if ($this->ingredients) {
                $parser = new IngredientParser();
                return $parser->parseIngredients($this->ingredients);
            }
            return null;
        }
        
        $data = is_array($this->additional_data) 
            ? $this->additional_data 
            : json_decode($this->additional_data, true);
        
        // Если есть structured_ingredients, возвращаем их
        if (isset($data['structured_ingredients'])) {
            return $data['structured_ingredients'];
        }
        
        // Если нет, но есть текст ингредиентов, парсим на лету
        if ($this->ingredients) {
            $parser = new IngredientParser();
            return $parser->parseIngredients($this->ingredients);
        }
        
        return null;
    }

    /**
     * Получить URL изображения рецепта
     * Если изображение не задано, возвращает изображение по умолчанию
     *
     * @return string
     */
    public function getImageUrl()
    {
        if (!empty($this->image_url)) {
            // Проверяем, является ли путь абсолютным URL
            if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
                return $this->image_url;
            }
            
            // Проверяем, существует ли файл физически
            $publicPath = public_path(ltrim($this->image_url, '/'));
            if (file_exists($publicPath)) {
                return asset($this->image_url);
            }
        }
        
        // Проверка, есть ли информация о сохраненных изображениях в additional_data
        if (!empty($this->additional_data)) {
            $additionalData = is_array($this->additional_data) ? 
                $this->additional_data : 
                json_decode($this->additional_data, true);
                
            if (isset($additionalData['saved_images']) && !empty($additionalData['saved_images'])) {
                $firstImage = $additionalData['saved_images'][0];
                if (isset($firstImage['saved_path'])) {
                    $publicPath = public_path(ltrim($firstImage['saved_path'], '/'));
                    if (file_exists($publicPath)) {
                        return asset($firstImage['saved_path']);
                    }
                }
            }
        }
        
        // Возвращаем изображение по умолчанию
        return asset('images/default-recipe.jpg');
    }

    /**
     * Получить оптимизированное изображение в WebP формате
     */
    public function getOptimizedImageUrl($width = 800, $height = 600)
    {
        $originalUrl = $this->getImageUrl();
        
        // Проверяем, является ли изображение внешним
        if (filter_var($originalUrl, FILTER_VALIDATE_URL) && parse_url($originalUrl, PHP_URL_HOST) !== request()->getHost()) {
            return $originalUrl;
        }
        
        // Если это локальное изображение, можно использовать библиотеку для оптимизации
        // Здесь можно интегрировать Glide, Intervention Image или другую библиотеку
        // Это просто заглушка для примера
        return $originalUrl;
    }

    /**
     * Отношение к ингредиентам
     */
    public function allIngredients()
    {
        return $this->hasMany(Ingredient::class)->orderBy('position');
    }
    
    /**
     * Отношение к группам ингредиентов
     */
    public function ingredientGroups()
    {
        return $this->hasMany(IngredientGroup::class)->orderBy('position');
    }
    
    /**
     * Получает JSON представление ингредиентов
     */
    public function getIngredientsJsonAttribute()
    {
        if ($this->additional_data) {
            $data = is_array($this->additional_data) 
                  ? $this->additional_data 
                  : json_decode($this->additional_data, true);
                  
            if (isset($data['ingredients_json'])) {
                return $data['ingredients_json'];
            }
        }
        
        return $this->generateIngredientsJson();
    }
    
    /**
     * Генерирует JSON представление ингредиентов
     */
    protected function generateIngredientsJson()
    {
        if ($this->ingredients) {
            $parser = new IngredientParser();
            $structuredData = $parser->parseToStructuredData($this->ingredients);
            return json_encode($structuredData, JSON_UNESCAPED_UNICODE);
        }
        
        return '{"version":"2.0","format":"structured","count":0,"ingredients":[]}';
    }
    
    /**
     * Обновляет структурированные данные ингредиентов
     */
    public function updateStructuredIngredients()
    {
        if (!$this->ingredients) {
            return;
        }
        
        $parser = new IngredientParser();
        $structuredData = $parser->parseToStructuredData($this->ingredients);
        
        $additionalData = [];
        if ($this->additional_data) {
            $additionalData = is_array($this->additional_data) 
                             ? $this->additional_data 
                             : json_decode($this->additional_data, true) ?? [];
        }
        
        $additionalData['structured_ingredients'] = $structuredData['ingredients'];
        $additionalData['ingredients_json'] = json_encode($structuredData, JSON_UNESCAPED_UNICODE);
        
        $this->additional_data = json_encode($additionalData, JSON_UNESCAPED_UNICODE);
        $this->save();
    }
    
    /**
     * Синхронизирует DB модели ингредиентов с JSON представлением
     */
    public function syncIngredientsFromJson()
    {
        // Получаем данные
        $data = json_decode($this->ingredients_json, true);
        
        if (!$data || !isset($data['ingredients']) || !is_array($data['ingredients'])) {
            return;
        }
        
        // Транзакция для атомарной операции
        \DB::beginTransaction();
        
        try {
            // Удаляем существующие ингредиенты
            $this->allIngredients()->delete();
            $this->ingredientGroups()->delete();
            
            $position = 0;
            
            foreach ($data['ingredients'] as $item) {
                if (isset($item['name']) && isset($item['items'])) {
                    // Это группа ингредиентов
                    $group = $this->ingredientGroups()->create([
                        'name' => $item['name'],
                        'position' => $position++
                    ]);
                    
                    // Добавляем ингредиенты группы
                    $itemPosition = 0;
                    foreach ($item['items'] as $ingredient) {
                        $group->ingredients()->create([
                            'name' => $ingredient['name'],
                            'quantity' => $ingredient['quantity'] ?? null,
                            'unit' => $ingredient['unit'] ?? 'по вкусу',
                            'optional' => $ingredient['optional'] ?? false,
                            'state' => $ingredient['state'] ?? null,
                            'notes' => $ingredient['notes'] ?? null,
                            'priority' => $ingredient['priority'] ?? 'normal',
                            'recipe_id' => $this->id,
                            'position' => $itemPosition++
                        ]);
                    }
                } else {
                    // Это отдельный ингредиент
                    $this->allIngredients()->create([
                        'name' => $item['name'],
                        'quantity' => $item['quantity'] ?? null,
                        'unit' => $item['unit'] ?? 'по вкусу',
                        'optional' => $item['optional'] ?? false,
                        'state' => $item['state'] ?? null,
                        'notes' => $item['notes'] ?? null,
                        'priority' => $item['priority'] ?? 'normal',
                        'position' => $position++
                    ]);
                }
            }
            
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Error syncing ingredients: " . $e->getMessage());
        }
    }

    /**
     * Получить пользователя, создавшего рецепт
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Проверить, является ли указанный пользователь владельцем рецепта
     *
     * @param User $user
     * @return bool
     */
    public function isOwnedBy(User $user)
    {
        return $this->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Разбивает текст инструкций на массив шагов
     *
     * @return array
     */
    public function getInstructionsArray(): array
    {
        if (empty($this->instructions)) {
            return [];
        }
        
        // Разбиваем текст инструкций по переводам строки
        $instructions = preg_split('/\r\n|\r|\n/', $this->instructions);
        
        // Удаляем пустые строки и лишние пробелы
        return array_map('trim', array_filter($instructions, function($line) {
            return !empty(trim($line));
        }));
    }

    /**
     * Получить комментарии к рецепту
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->orderBy('created_at', 'desc');
    }
}
