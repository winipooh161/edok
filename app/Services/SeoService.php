<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Генерирует SEO-заголовок для рецепта
     */
    public function getRecipeTitle(Recipe $recipe)
    {
        $title = $recipe->title;
        $category = $recipe->categories->first();
        
        if ($category) {
            $title .= ' | ' . $category->name;
        }
        
        $title .= ' | ' . config('app.name');
        
        // Ограничиваем длину заголовка
        return Str::limit($title, 65, '');
    }
    
    /**
     * Генерирует SEO-описание для рецепта
     */
    public function getRecipeDescription(Recipe $recipe)
    {
        $description = $recipe->description;
        
        // Если описания нет или оно короткое, создаем описание на основе ингредиентов
        if (empty($description) || strlen($description) < 50) {
            $ingredients = explode("\n", $recipe->ingredients);
            $ingredientsSample = array_slice($ingredients, 0, 3);
            $ingredientsCount = count($ingredients);
            
            $description = "Рецепт {$recipe->title}. ";
            
            if ($recipe->cooking_time) {
                $description .= "Время приготовления: {$recipe->cooking_time} мин. ";
            }
            
            $description .= "Используется $ingredientsCount ингредиентов";
            
            if (!empty($ingredientsSample)) {
                $description .= ", включая " . implode(', ', $ingredientsSample) . ". ";
            } else {
                $description .= ". ";
            }
            
            if ($recipe->calories) {
                $description .= "Калорийность: {$recipe->calories} ккал.";
            }
        }
        
        return Str::limit($description, 160, '');
    }
    
    /**
     * Генерирует SEO-заголовок для категории
     */
    public function getCategoryTitle(Category $category)
    {
        $title = $category->name . ' - рецепты | ' . config('app.name');
        return Str::limit($title, 65, '');
    }
    
    /**
     * Генерирует SEO-описание для категории
     */
    public function getCategoryDescription(Category $category)
    {
        $description = $category->description;
        
        if (empty($description)) {
            $recipesCount = $category->recipes()->where('is_published', true)->count();
            $description = "Лучшие рецепты в категории {$category->name}. У нас вы найдете $recipesCount проверенных рецептов с подробными инструкциями и фото.";
        }
        
        return Str::limit($description, 160, '');
    }
    
    /**
     * Генерирует микроразметку Schema.org для рецепта
     */
    public function getRecipeSchema(Recipe $recipe)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Recipe',
            'name' => $recipe->title,
            'author' => [
                '@type' => 'Person',
                'name' => $recipe->user ? $recipe->user->name : config('app.name')
            ],
            'datePublished' => $recipe->created_at->toIso8601String(),
            'dateModified' => $recipe->updated_at->toIso8601String(),
            'description' => $this->getRecipeDescription($recipe),
            'prepTime' => $recipe->cooking_time ? 'PT' . floor($recipe->cooking_time / 3) . 'M' : 'PT10M',
            'cookTime' => $recipe->cooking_time ? 'PT' . floor($recipe->cooking_time * 2/3) . 'M' : 'PT20M',
            'totalTime' => $recipe->cooking_time ? 'PT' . $recipe->cooking_time . 'M' : 'PT30M',
            'keywords' => $this->generateKeywords($recipe),
            'recipeCategory' => $recipe->categories->first() ? $recipe->categories->first()->name : 'Основные блюда',
            'recipeCuisine' => $this->detectCuisine($recipe),
            'recipeYield' => $recipe->servings ?: '4 порции',
            'url' => route('recipes.show', $recipe->slug),
        ];
        
        // Добавляем изображение
        if ($recipe->image_url) {
            $imageUrl = $recipe->getImageUrl();
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => $imageUrl,
                'width' => '800',
                'height' => '600',
                'caption' => $recipe->title
            ];
        }
        
        // Добавляем ингредиенты
        $ingredients = explode("\n", $recipe->ingredients);
        $schema['recipeIngredient'] = array_map('trim', array_filter($ingredients));
        
        // Добавляем инструкции
        $instructions = explode("\n", $recipe->instructions);
        $schema['recipeInstructions'] = [];
        
        foreach ($instructions as $i => $step) {
            $trimmedStep = trim($step);
            if (!empty($trimmedStep)) {
                $schema['recipeInstructions'][] = [
                    '@type' => 'HowToStep',
                    'text' => $trimmedStep,
                    'position' => $i + 1
                ];
            }
        }
        
        // Добавляем пищевую ценность
        if ($recipe->calories || $recipe->proteins || $recipe->fats || $recipe->carbs) {
            $schema['nutrition'] = [
                '@type' => 'NutritionInformation'
            ];
            
            if ($recipe->calories) {
                $schema['nutrition']['calories'] = $recipe->calories . ' ккал';
            }
            
            if ($recipe->proteins) {
                $schema['nutrition']['proteinContent'] = $recipe->proteins . ' г';
            }
            
            if ($recipe->fats) {
                $schema['nutrition']['fatContent'] = $recipe->fats . ' г';
            }
            
            if ($recipe->carbs) {
                $schema['nutrition']['carbohydrateContent'] = $recipe->carbs . ' г';
            }
        }
        
        // Добавляем рейтинг 
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.8',
            'ratingCount' => max(5, $recipe->views / 10), // Эмуляция рейтинга на основе просмотров
            'bestRating' => '5',
            'worstRating' => '1'
        ];
        
        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * Генерирует ключевые слова для рецепта
     */
    private function generateKeywords(Recipe $recipe)
    {
        $keywords = [];
        
        // Добавляем название рецепта
        $keywords[] = $recipe->title;
        
        // Добавляем категории
        foreach ($recipe->categories as $category) {
            $keywords[] = $category->name;
        }
        
        // Добавляем основные ингредиенты (первые 5)
        $ingredients = explode("\n", $recipe->ingredients);
        $mainIngredients = array_slice($ingredients, 0, 5);
        foreach ($mainIngredients as $ingredient) {
            // Извлекаем только название ингредиента (без количества)
            preg_match('/(?:\d+\s*\w+\s*)?(.+)/i', $ingredient, $matches);
            if (isset($matches[1])) {
                $keywords[] = trim($matches[1]);
            }
        }
        
        // Добавляем общие ключевые слова
        $keywords[] = 'рецепт';
        $keywords[] = 'приготовление';
        $keywords[] = 'как приготовить';
        
        // Если есть время приготовления, добавляем соответствующее ключевое слово
        if ($recipe->cooking_time) {
            if ($recipe->cooking_time <= 30) {
                $keywords[] = 'быстрый рецепт';
            } elseif ($recipe->cooking_time >= 120) {
                $keywords[] = 'сложный рецепт';
            }
            $keywords[] = 'время приготовления ' . $recipe->cooking_time . ' минут';
        }
        
        // Убираем дубликаты и объединяем
        return implode(', ', array_unique($keywords));
    }
    
    /**
     * Определяет кухню на основе названия и ингредиентов рецепта
     */
    private function detectCuisine(Recipe $recipe)
    {
        $title = mb_strtolower($recipe->title);
        $ingredients = mb_strtolower($recipe->ingredients);
        $description = mb_strtolower($recipe->description);
        $allText = $title . ' ' . $ingredients . ' ' . $description;
        
        $cuisineKeywords = [
            'Русская кухня' => ['борщ', 'щи', 'блины', 'пельмени', 'окрошка', 'квас', 'гречка', 'кисель'],
            'Итальянская кухня' => ['паста', 'пицца', 'лазанья', 'ризотто', 'карбонара', 'тирамису', 'панна котта'],
            'Французская кухня' => ['багет', 'круассан', 'киш', 'фуа-гра', 'рататуй', 'крем-брюле'],
            'Японская кухня' => ['суши', 'роллы', 'васаби', 'темпура', 'мисо', 'саке', 'рамен'],
            'Китайская кухня' => ['вок', 'димсам', 'рисовая лапша', 'соевый соус', 'дим сам'],
            'Мексиканская кухня' => ['тако', 'буррито', 'гуакамоле', 'начос', 'кесадилья', 'тортилья'],
            'Индийская кухня' => ['карри', 'масала', 'нан', 'чапати', 'самоса', 'панир'],
        ];
        
        foreach ($cuisineKeywords as $cuisine => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($allText, $keyword)) {
                    return $cuisine;
                }
            }
        }
        
        return 'Интернациональная кухня';
    }
}
