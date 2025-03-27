<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientGroup extends Model
{
    use HasFactory;
    
    /**
     * Атрибуты, доступные для массового присвоения
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'recipe_id',
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
     * Отношение к ингредиентам в группе
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'group_id')
                    ->orderBy('position');
    }
}
