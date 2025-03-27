<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image_path'];

    /**
     * Автоматически создаем slug при создании категории, если он не указан
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Получить все рецепты, принадлежащие к этой категории
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
    
    /**
     * Количество опубликованных рецептов в категории
     */
    public function getPublishedRecipesCountAttribute()
    {
        return $this->recipes()->where('is_published', true)->count();
    }
    
    /**
     * Получить URL изображения категории или вернуть изображение по умолчанию
     */
    public function getImageUrl()
    {
        if (!empty($this->image_path)) {
            return asset($this->image_path);
        }
        
        // Возвращаем стандартные иконки для категорий
        $categoryIcons = [
            'завтрак' => 'breakfast.jpg',
            'обед' => 'lunch.jpg',
            'ужин' => 'dinner.jpg',
            'десерт' => 'dessert.jpg',
            'суп' => 'soup.jpg',
            'салат' => 'salad.jpg',
            'закуска' => 'appetizer.jpg',
            'напиток' => 'drink.jpg',
            'выпечка' => 'baking.jpg',
            'мясо' => 'meat.jpg',
            'рыба' => 'fish.jpg',
            'овощи' => 'vegetables.jpg',
        ];
        
        // Проверяем по ключевым словам в названии категории
        foreach ($categoryIcons as $keyword => $icon) {
            if (Str::contains(mb_strtolower($this->name), $keyword)) {
                return asset('images/categories/' . $icon);
            }
        }
        
        // Если ничего не подошло, возвращаем изображение по умолчанию
        return asset('images/categories/default.jpg');
    }
    
    /**
     * Получить цвет фона для категории
     */
    public function getColorClass()
    {
        $colors = [
            'bg-primary', 'bg-success', 'bg-info', 'bg-warning', 
            'bg-danger', 'bg-secondary', 'bg-dark', 'bg-indigo'
        ];
        
        // Используем ID категории для выбора цвета (чтобы один и тот же цвет 
        // всегда использовался для одной и той же категории)
        return $colors[$this->id % count($colors)];
    }
}
