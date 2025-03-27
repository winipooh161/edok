<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\SearchHistory;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchService
{
    /**
     * Расчет расстояния Левенштейна с ограничением
     */
    public function levenshtein_utf8($s1, $s2, $max_distance = 3)
    {
        // Если строки идентичны, расстояние равно 0
        if ($s1 === $s2) {
            return 0;
        }
        
        // Преобразуем в нижний регистр и удаляем пробелы
        $s1 = mb_strtolower(trim($s1));
        $s2 = mb_strtolower(trim($s2));
        
        // Максимальная разница в длине, после которой сразу возвращаем max_distance+1
        $len1 = mb_strlen($s1);
        $len2 = mb_strlen($s2);
        
        if (abs($len1 - $len2) > $max_distance) {
            return $max_distance + 1;
        }
        
        // Если одна из строк пустая, возвращаем длину другой
        if ($len1 === 0) return $len2;
        if ($len2 === 0) return $len1;
        
        // Используем алгоритм Вагнера-Фишера для UTF-8 строк
        $previous_row = range(0, $len2);
        
        for ($i = 0; $i < $len1; $i++) {
            $current_row = [$i + 1];
            $min_distance = $max_distance + 1;
            
            for ($j = 0; $j < $len2; $j++) {
                $cost = (mb_substr($s1, $i, 1) === mb_substr($s2, $j, 1)) ? 0 : 1;
                $current_row[] = min(
                    $current_row[$j] + 1,             // удаление
                    $previous_row[$j + 1] + 1,        // вставка
                    $previous_row[$j] + $cost         // замена
                );
                
                // Отслеживаем минимальное расстояние в строке
                if ($current_row[$j + 1] < $min_distance) {
                    $min_distance = $current_row[$j + 1];
                }
            }
            
            // Если все расстояния в строке больше max_distance, возвращаем превышение
            if ($min_distance > $max_distance) {
                return $max_distance + 1;
            }
            
            $previous_row = $current_row;
        }
        
        return $previous_row[$len2];
    }
    
    /**
     * Поиск рецептов с учетом опечаток и похожих слов
     */
    public function searchRecipes($query, $options = [])
    {
        if (empty($query)) {
            return Recipe::where('is_published', true)->latest();
        }
        
        // Разбиваем запрос на отдельные слова
        $searchTerms = preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);
        $filteredTerms = [];
        
        // Фильтруем короткие слова и стоп-слова
        foreach ($searchTerms as $term) {
            if (mb_strlen($term) >= 3 && !$this->isStopWord($term)) {
                $filteredTerms[] = $term;
            }
        }
        
        // Если нет подходящих терминов, возвращаем все рецепты
        if (empty($filteredTerms)) {
            return Recipe::where('is_published', true)->latest();
        }
        
        // Добавляем запрос в историю поиска
        if (isset($options['save_history']) && $options['save_history'] && auth()->check()) {
            $this->saveSearchQuery($query);
        }
        
        // Базовый поиск с весами для разных полей
        $recipesQuery = Recipe::where('is_published', true)
            ->where(function($q) use ($filteredTerms) {
                foreach ($filteredTerms as $term) {
                    $q->where(function($subq) use ($term) {
                        $subq->where('title', 'like', '%' . $term . '%')
                            ->orWhere('description', 'like', '%' . $term . '%')
                            ->orWhere('ingredients', 'like', '%' . $term . '%')
                            ->orWhere('instructions', 'like', '%' . $term . '%');
                    });
                }
            });
        
        // Добавляем вычисление релевантности
        $relevanceSql = '(';
        foreach ($filteredTerms as $term) {
            $relevanceSql .= "
                CASE 
                    WHEN title LIKE '" . addslashes($term) . "' THEN 30
                    WHEN title LIKE '" . addslashes($term) . "%' THEN 20
                    WHEN title LIKE '% " . addslashes($term) . "%' THEN 15
                    WHEN title LIKE '%" . addslashes($term) . "%' THEN 10
                    ELSE 0 
                END +
                CASE 
                    WHEN ingredients LIKE '" . addslashes($term) . "' THEN 15
                    WHEN ingredients LIKE '" . addslashes($term) . "%' THEN 10
                    WHEN ingredients LIKE '% " . addslashes($term) . "%' THEN 8
                    WHEN ingredients LIKE '%" . addslashes($term) . "%' THEN 5
                    ELSE 0 
                END +
                CASE 
                    WHEN description LIKE '%" . addslashes($term) . "%' THEN 3
                    ELSE 0 
                END +
                CASE 
                    WHEN instructions LIKE '%" . addslashes($term) . "%' THEN 1
                    ELSE 0 
                END + ";
        }
        $relevanceSql = rtrim($relevanceSql, "+ ") . ") as search_relevance";
        
        $recipesQuery->addSelect(['*', DB::raw($relevanceSql)])
                    ->orderBy('search_relevance', 'desc')
                    ->orderBy('views', 'desc');
        
        // Расширенный поиск с учетом опечаток
        if (isset($options['fuzzy_search']) && $options['fuzzy_search']) {
            // Получаем все рецепты для нечеткого поиска
            $allRecipes = Recipe::where('is_published', true)
                ->select('id', 'title', 'ingredients')
                ->get();
            
            $fuzzyMatches = [];
            
            // Ищем похожие слова с допустимым расстоянием Левенштейна
            foreach ($allRecipes as $recipe) {
                $recipeWords = array_merge(
                    preg_split('/\s+/', $recipe->title, -1, PREG_SPLIT_NO_EMPTY),
                    preg_split('/[,\s]+/', $recipe->ingredients, -1, PREG_SPLIT_NO_EMPTY)
                );
                
                $recipeWords = array_unique($recipeWords);
                $similarity = 0;
                
                foreach ($filteredTerms as $term) {
                    $termLen = mb_strlen($term);
                    $bestMatch = 0;
                    
                    foreach ($recipeWords as $word) {
                        // Пропускаем слишком короткие слова и стоп-слова
                        if (mb_strlen($word) < 3 || $this->isStopWord($word)) {
                            continue;
                        }
                        
                        // Вычисляем расстояние Левенштейна
                        $distance = $this->levenshtein_utf8($term, $word);
                        
                        // Рассчитываем % сходства (0-100)
                        $maxLen = max($termLen, mb_strlen($word));
                        $wordSimilarity = 100 - (($distance / $maxLen) * 100);
                        
                        // Учитываем только хорошие совпадения
                        if ($wordSimilarity > 70) {
                            $bestMatch = max($bestMatch, $wordSimilarity);
                        }
                    }
                    
                    $similarity += $bestMatch;
                }
                
                // Средняя релевантность по всем словам запроса
                if (count($filteredTerms) > 0) {
                    $similarity = $similarity / count($filteredTerms);
                    
                    // Если сходство выше 60%, добавляем в результат
                    if ($similarity >= 60) {
                        $fuzzyMatches[$recipe->id] = $similarity;
                    }
                }
            }
            
            // Добавляем нечеткие совпадения в результаты
            if (!empty($fuzzyMatches)) {
                $fuzzyIds = array_keys($fuzzyMatches);
                
                // Объединяем нечеткие результаты с обычными
                $recipesQuery->orWhere(function($q) use ($fuzzyIds) {
                    $q->whereIn('id', $fuzzyIds)
                      ->where('is_published', true);
                });
                
                // Добавляем поле fuzzy_relevance
                $fuzzyRelevanceCase = "CASE ";
                foreach ($fuzzyMatches as $id => $similarity) {
                    $fuzzyRelevanceCase .= "WHEN id = $id THEN $similarity ";
                }
                $fuzzyRelevanceCase .= "ELSE 0 END as fuzzy_relevance";
                
                $recipesQuery->addSelect(DB::raw($fuzzyRelevanceCase));
            }
        }
        
        return $recipesQuery;
    }
    
    /**
     * Сохранить поисковый запрос в истории
     */
    public function saveSearchQuery($query)
    {
        if (empty($query) || mb_strlen($query) < 3) {
            return;
        }
        
        if (auth()->check()) {
            SearchHistory::create([
                'user_id' => auth()->id(),
                'query' => $query,
                'results_count' => 0 // Будет обновлено позже
            ]);
        }
    }
    
    /**
     * Обновить количество результатов для последнего поиска
     */
    public function updateSearchResultsCount($query, $count)
    {
        if (!auth()->check()) {
            return;
        }
        
        SearchHistory::where('user_id', auth()->id())
            ->where('query', $query)
            ->latest()
            ->first()
            ->update(['results_count' => $count]);
    }
    
    /**
     * Получить популярные поисковые запросы
     */
    public function getPopularSearchTerms($limit = 10)
    {
        return SearchHistory::select('query', DB::raw('count(*) as count'))
            ->groupBy('query')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get()
            ->pluck('query')
            ->toArray();
    }
    
    /**
     * Получить подсказки для автодополнения
     */
    public function getAutocompleteSuggestions($query, $limit = 10)
    {
        if (empty($query) || mb_strlen($query) < 2) {
            return [];
        }
        
        // Подсказки из названий рецептов
        $recipeTitles = Recipe::where('is_published', true)
            ->where('title', 'like', '%' . $query . '%')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->pluck('title')
            ->toArray();
        
        // Подсказки из категорий
        $categories = Category::where('name', 'like', '%' . $query . '%')
            ->limit($limit / 2)
            ->get()
            ->map(function($category) {
                return [
                    'text' => $category->name,
                    'type' => 'category',
                    'url' => route('categories.show', $category->slug)
                ];
            });
        
        // Подсказки из ингредиентов
        $ingredients = Recipe::where('is_published', true)
            ->where('ingredients', 'like', '%' . $query . '%')
            ->limit($limit)
            ->get()
            ->flatMap(function($recipe) {
                return preg_split('/[,\n]+/', $recipe->ingredients);
            })
            ->filter(function($ingredient) use ($query) {
                return Str::contains(strtolower($ingredient), strtolower($query));
            })
            ->unique()
            ->take($limit / 2)
            ->map(function($ingredient) {
                return [
                    'text' => trim($ingredient),
                    'type' => 'ingredient',
                    'url' => route('search', ['query' => trim($ingredient)])
                ];
            });
        
        // Преобразуем названия рецептов в формат для подсказок
        $titleSuggestions = collect($recipeTitles)
            ->map(function($title) {
                return [
                    'text' => $title,
                    'type' => 'recipe',
                    'url' => route('search', ['query' => $title])
                ];
            });
        
        // Объединяем все типы подсказок
        $suggestions = $titleSuggestions
            ->merge($categories)
            ->merge($ingredients)
            ->take($limit);
        
        return $suggestions->toArray();
    }
    
    /**
     * Проверить, является ли слово стоп-словом
     */
    private function isStopWord($word)
    {
        $stopWords = [
            'для', 'или', 'над', 'под', 'при', 'про', 'как', 'что', 'это', 'все', 'так',
            'его', 'мой', 'наш', 'ваш', 'кто', 'где', 'еще', 'уже', 'сам', 'мне', 'нам',
            'чем', 'тем', 'тот', 'том', 'вот', 'вам', 'нет', 'они', 'оно', 'она', 'мои'
        ];
        
        return in_array(mb_strtolower($word), $stopWords);
    }
}
