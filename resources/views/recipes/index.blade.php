@extends('layouts.app')

@section('meta_tags')
    <title>{{ isset($search) ? "Поиск: $search" : "Все рецепты" }} | {{ config('app.name') }}</title>
    @if(isset($search))
        <meta name="description" content="Результаты поиска по запросу '{{ $search }}'. Найдено {{ $recipes->total() }} рецептов с пошаговыми инструкциями и фото.">
    @else
        <meta name="description" content="Каталог кулинарных рецептов с подробными инструкциями, списком ингредиентов и пошаговыми фото. Найдите идеальный рецепт для любого случая!">
    @endif
    <link rel="canonical" href="{{ route('recipes.index', request()->query()) }}" />
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Боковая панель с фильтрами -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Фильтры</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('search') }}" method="GET" id="search-form">
                        <!-- Поиск по ключевым словам -->
                        <div class="mb-3">
                            <label for="query" class="form-label">Ключевые слова</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="query" name="query" value="{{ $search ?? '' }}" placeholder="Что ищем?">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="form-text">Введите название блюда или ингредиент</div>
                        </div>
                        
                        <!-- Тип поиска -->
                        <div class="mb-3">
                            <label class="form-label">Тип поиска</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="search_type" id="search_type_title" value="title" {{ ($searchType ?? 'title') == 'title' ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_type_title">
                                    По названию и описанию
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="search_type" id="search_type_ingredients" value="ingredients" {{ ($searchType ?? '') == 'ingredients' ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_type_ingredients">
                                    По ингредиентам
                                </label>
                            </div>
                        </div>
                        
                        <!-- Ингредиенты (появляется при выборе соответствующего типа поиска) -->
                        <div class="mb-3 ingredients-container" style="{{ ($searchType ?? '') != 'ingredients' ? 'display: none;' : '' }}">
                            <label class="form-label">Список ингредиентов</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="ingredient-input" placeholder="Введите ингредиент">
                                <button class="btn btn-outline-secondary" type="button" id="add-ingredient">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="ingredients-list" class="mb-2">
                                @if(!empty($ingredients))
                                    @foreach($ingredients as $ingredient)
                                        <div class="ingredient-item d-flex align-items-center justify-content-between mb-1 p-2 bg-light rounded">
                                            <span>{{ $ingredient }}</span>
                                            <input type="hidden" name="ingredients[]" value="{{ $ingredient }}">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-ingredient">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-text">Добавьте один или несколько ингредиентов</div>
                        </div>
                        
                        <!-- Фильтр по категории -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Категория</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($selectedCategory ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->recipes_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Фильтр по времени приготовления -->
                        <div class="mb-3">
                            <label for="cooking_time" class="form-label">Время приготовления</label>
                            <select class="form-select" id="cooking_time" name="cooking_time">
                                <option value="">Любое время</option>
                                <option value="15" {{ ($selectedCookingTime ?? '') == 15 ? 'selected' : '' }}>До 15 минут</option>
                                <option value="30" {{ ($selectedCookingTime ?? '') == 30 ? 'selected' : '' }}>До 30 минут</option>
                                <option value="60" {{ ($selectedCookingTime ?? '') == 60 ? 'selected' : '' }}>До 1 часа</option>
                                <option value="120" {{ ($selectedCookingTime ?? '') == 120 ? 'selected' : '' }}>До 2 часов</option>
                            </select>
                        </div>
                        
                        <!-- Только с фото -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="has_image" name="has_image" value="1" {{ request()->has('has_image') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_image">Только с фото</label>
                        </div>
                        
                        <!-- Кнопки действий -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Применить фильтры
                            </button>
                            <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Сбросить все фильтры
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Популярные теги для быстрого поиска -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Популярные теги</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('search', ['query' => 'завтрак']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">завтрак</a>
                        <a href="{{ route('search', ['query' => 'десерт']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">десерт</a>
                        <a href="{{ route('search', ['query' => 'быстрый ужин']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">быстрый ужин</a>
                        <a href="{{ route('search', ['query' => 'суп']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">суп</a>
                        <a href="{{ route('search', ['query' => 'курица']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">курица</a>
                        <a href="{{ route('search', ['query' => 'вегетарианское']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">вегетарианское</a>
                        <a href="{{ route('search', ['query' => 'без глютена']) }}" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">без глютена</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Основной контент - список рецептов -->
        <div class="col-lg-9">
            <!-- Заголовок и информация о поиске -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    @if(!empty($search))
                        <h2>Результаты поиска: "{{ $search }}"</h2>
                        <p class="text-muted">
                            Найдено {{ $recipes->total() }} {{ trans_choice('рецепт|рецепта|рецептов', $recipes->total()) }}
                            @if(!empty($searchTerms))
                                <span class="small">(искали: {{ implode(', ', $searchTerms) }})</span>
                            @endif
                        </p>
                    @elseif(!empty($selectedCategory))
                        <h2>Рецепты в категории</h2>
                    @else
                        <h2>Все рецепты</h2>
                    @endif
                </div>
                
                <!-- Сортировка результатов -->
                <div class="d-flex align-items-center">
                    <label for="sort" class="form-label me-2 mb-0">Сортировка:</label>
                    <select class="form-select form-select-sm" id="sort" name="sort">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>По релевантности</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Новые первыми</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Популярные</option>
                        <option value="cooking_time_asc" {{ request('sort') == 'cooking_time_asc' ? 'selected' : '' }}>По времени (возр.)</option>
                        <option value="cooking_time_desc" {{ request('sort') == 'cooking_time_desc' ? 'selected' : '' }}>По времени (убыв.)</option>
                    </select>
                </div>
            </div>
            
            <!-- Результаты поиска -->
            @if($recipes->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($recipes as $recipe)
                        <div class="col">
                            <div class="card h-100 recipe-card border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="{{ $recipe->getImageUrl() }}" class="card-img-top recipe-img" alt="{{ $recipe->title }}">
                                    
                                    @if($recipe->cooking_time)
                                        <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                            <i class="far fa-clock me-1"></i> {{ $recipe->cooking_time }} мин
                                        </span>
                                    @endif
                                    
                                    @if(isset($recipe->relevance_percent) && $recipe->relevance_percent > 0)
                                        <div class="position-absolute top-0 start-0 badge bg-{{ $recipe->relevance_percent > 75 ? 'success' : ($recipe->relevance_percent > 50 ? 'info' : 'secondary') }} m-2">
                                            {{ $recipe->relevance_percent }}% совпадение
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="text-decoration-none text-dark">
                                            @if(!empty($search))
                                                {!! preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->title) !!}
                                            @else
                                                {{ $recipe->title }}
                                            @endif
                                        </a>
                                    </h5>
                                    
                                    @if($recipe->user)
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-user me-1"></i> {{ $recipe->user->name }}
                                        </p>
                                    @endif
                                    
                                    <p class="card-text">
                                        @if(!empty($search) && !empty($recipe->description))
                                            {!! Str::limit(preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->description), 100) !!}
                                        @else
                                            {{ Str::limit($recipe->description, 100) }}
                                        @endif
                                    </p>
                                    
                                    @if(!$recipe->categories->isEmpty())
                                        <div class="mb-2">
                                            @foreach($recipe->categories->take(3) as $category)
                                                <a href="{{ route('categories.show', $category->slug) }}" class="badge bg-light text-dark text-decoration-none me-1">
                                                    {{ $category->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-eye me-1"></i> {{ $recipe->views }}
                                            </span>
                                        </div>
                                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-book-open me-1"></i> Смотреть рецепт
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Пагинация -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $recipes->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> По вашему запросу ничего не найдено.
                    @if(!empty($search))
                        <p class="mt-2 mb-0">Попробуйте изменить поисковый запрос или уменьшить количество фильтров.</p>
                    @endif
                </div>
                
                <!-- Предложения, если ничего не найдено -->
                <div class="mt-4">
                    <h4>Возможно, вас заинтересует:</h4>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                                    <h5>Популярные рецепты</h5>
                                    <p class="text-muted">Посмотрите наши самые популярные рецепты</p>
                                    <a href="{{ route('recipes.index', ['sort' => 'popular']) }}" class="btn btn-sm btn-outline-primary">
                                        Показать
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-3x text-success mb-3"></i>
                                    <h5>Быстрые рецепты</h5>
                                    <p class="text-muted">Рецепты, которые можно приготовить быстро</p>
                                    <a href="{{ route('recipes.index', ['cooking_time' => 30]) }}" class="btn btn-sm btn-outline-success">
                                        Показать
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-random fa-3x text-warning mb-3"></i>
                                    <h5>Случайный рецепт</h5>
                                    <p class="text-muted">Не знаете, что приготовить? Попробуйте что-то новое!</p>
                                    <a href="{{ route('recipes.index', ['random' => 1]) }}" class="btn btn-sm btn-outline-warning">
                                        Удивите меня
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .recipe-img {
        height: 200px;
        object-fit: cover;
    }
    
    .recipe-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .ingredient-item {
        transition: all 0.3s ease;
    }
    
    .ingredient-item:hover {
        background-color: #f1f1f1 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Переключение отображения контейнера ингредиентов в зависимости от выбранного типа поиска
        const searchTypeRadios = document.querySelectorAll('input[name="search_type"]');
        const ingredientsContainer = document.querySelector('.ingredients-container');
        
        searchTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'ingredients') {
                    ingredientsContainer.style.display = 'block';
                } else {
                    ingredientsContainer.style.display = 'none';
                }
            });
        });
        
        // Добавление ингредиентов в список
        const addIngredientBtn = document.getElementById('add-ingredient');
        const ingredientInput = document.getElementById('ingredient-input');
        const ingredientsList = document.getElementById('ingredients-list');
        
        if (addIngredientBtn && ingredientInput && ingredientsList) {
            addIngredientBtn.addEventListener('click', function() {
                addIngredient();
            });
            
            ingredientInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addIngredient();
                }
            });
            
            function addIngredient() {
                const ingredient = ingredientInput.value.trim();
                if (ingredient) {
                    // Проверяем, нет ли уже такого ингредиента в списке
                    const existingIngredients = Array.from(ingredientsList.querySelectorAll('input[type="hidden"]')).map(input => input.value);
                    if (!existingIngredients.includes(ingredient)) {
                        // Создаем элемент для отображения ингредиента
                        const ingredientItem = document.createElement('div');
                        ingredientItem.className = 'ingredient-item d-flex align-items-center justify-content-between mb-1 p-2 bg-light rounded';
                        ingredientItem.innerHTML = `
                            <span>${ingredient}</span>
                            <input type="hidden" name="ingredients[]" value="${ingredient}">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-ingredient">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        
                        // Добавляем функцию удаления
                        const removeBtn = ingredientItem.querySelector('.remove-ingredient');
                        removeBtn.addEventListener('click', function() {
                            ingredientItem.remove();
                        });
                        
                        // Добавляем в список
                        ingredientsList.appendChild(ingredientItem);
                        
                        // Очищаем поле ввода
                        ingredientInput.value = '';
                    } else {
                        alert('Этот ингредиент уже добавлен в список!');
                    }
                }
            }
            
            // Добавляем обработчики для существующих кнопок удаления
            document.querySelectorAll('.remove-ingredient').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.ingredient-item').remove();
                });
            });
        }
        
        // Обработка изменения сортировки
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const form = document.getElementById('search-form');
                const sortInput = document.createElement('input');
                sortInput.type = 'hidden';
                sortInput.name = 'sort';
                sortInput.value = this.value;
                form.appendChild(sortInput);
                form.submit();
            });
        }
    });
</script>
@endsection
