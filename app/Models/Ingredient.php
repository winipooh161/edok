<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    
    /**
     * Атрибуты, доступные для массового присвоения
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'optional',
        'state',
        'notes',
        'priority',
        'recipe_id',
        'group_id',
        'position'
    ];
    
    /**
     * Отношение к рецепту
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
    
    /**
     * Отношение к группе ингредиентов
     */
    public function group()
    {
        return $this->belongsTo(IngredientGroup::class);
    }
    
    /**
     * Преобразует ингредиент в строку
     */
    public function toString(): string
    {
        $result = $this->name;
        
        if ($this->quantity) {
            $result .= " - {$this->quantity} {$this->unit}";
        } elseif ($this->unit && $this->unit !== 'по вкусу') {
            $result .= " - {$this->unit}";
        } elseif ($this->unit === 'по вкусу') {
            $result .= " - по вкусу";
        }
        
        if ($this->notes) {
            $result .= " ({$this->notes})";
        }
        
        if ($this->optional) {
            $result .= " (по желанию)";
        }
        
        return $result;
    }
    
    /**
     * Конвертирует ингредиент в формат для JSON
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // Добавляем вычисляемые поля
        $array['text'] = $this->toString();
        
        return $array;
    }
}
