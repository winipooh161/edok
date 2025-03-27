@extends('layouts.app')

@section('content')
<!-- Главный баннер -->
<div class="home-banner py-5 bg-light animate-on-scroll">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4 animate-on-scroll">Кулинарные шедевры у вас дома</h1>
                <p class="lead mb-4 animate-on-scroll">Откройте для себя тысячи рецептов, которые легко и быстро готовить. От простых блюд до изысканных кулинарных шедевров!</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 animate-on-scroll">
                    <a href="{{ route('recipes.index') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                        <i class="fas fa-book-open me-1"></i> Все рецепты
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fas fa-tags me-1"></i> Категории
                    </a>
                </div>
                
                <form action="{{ route('search') }}" method="GET" class="mt-4 animate-on-scroll">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="query" placeholder="Что вы хотите приготовить?" aria-label="Поиск рецептов">
                        <button class="btn btn-primary" type="submit">Найти</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-none d-md-block animate-on-scroll">
                <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352" class="img-fluid rounded-3 shadow" alt="Кулинария">
            </div>
        </div>
    </div>
</div>

<!-- Расширенная форма поиска -->
<div class="search-container bg-white p-3 rounded shadow-sm my-4">
    <ul class="nav nav-tabs" id="searchTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="recipe-tab" data-bs-toggle="tab" data-bs-target="#recipe-search" 
                type="button" role="tab" aria-selected="true">
                <i class="fas fa-search me-1"></i> По названию
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ingredient-tab" data-bs-toggle="tab" data-bs-target="#ingredient-search" 
                type="button" role="tab" aria-selected="false">
                <i class="fas fa-carrot me-1"></i> По ингредиентам
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced-search" 
                type="button" role="tab" aria-selected="false">
                <i class="fas fa-sliders-h me-1"></i> Расширенный
            </button>
        </li>
    </ul>
    
    <div class="tab-content p-3" id="searchTabsContent">
        <!-- Поиск по названию -->
        <div class="tab-pane fade show active" id="recipe-search" role="tabpanel">
            <form action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" placeholder="Введите название блюда или ингредиенты...">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search me-1"></i> Найти
                    </button>
                </div>
                <div class="form-text mt-2">Подсказка: Чем точнее запрос, тем лучше результаты. Можно указать несколько ключевых слов.</div>
            </form>
        </div>
        
        <!-- Поиск по ингредиентам -->
        <div class="tab-pane fade" id="ingredient-search" role="tabpanel">
            <form action="{{ route('search') }}" method="GET" id="ingredients-search-form">
                <input type="hidden" name="search_type" value="ingredients">
                <div class="mb-3">
                    <div class="input-group mb-2">
                        <input type="text" id="home-ingredient-input" class="form-control" placeholder="Введите ингредиент и нажмите Enter...">
                        <button type="button" id="home-add-ingredient" class="btn btn-outline-secondary">
                            <i class="fas fa-plus"></i> Добавить
                        </button>
                    </div>
                    <div id="home-ingredients-list" class="mb-3"></div>
                    <div class="form-text">Добавьте ингредиенты, которые у вас есть, и мы найдем подходящие рецепты.</div>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit" id="ingredients-search-btn" disabled>
                        <i class="fas fa-utensils me-1"></i> Найти рецепты
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Расширенный поиск -->
        <div class="tab-pane fade" id="advanced-search" role="tabpanel">
            <form action="{{ route('search') }}" method="GET">
                <input type="hidden" name="search_type" value="advanced">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="home-query" class="form-label">Поисковый запрос</label>
                        <input type="text" class="form-control" id="home-query" name="query" placeholder="Необязательно">
                    </div>
                    <div class="col-md-6">
                        <label for="home-category" class="form-label">Категория</label>
                        <select name="category" id="home-category" class="form-select">
                            <option value="">Любая категория</option>
                            @foreach($popularCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="home-cooking_time" class="form-label">Время приготовления</label>
                        <select name="cooking_time" id="home-cooking_time" class="form-select">
                            <option value="">Любое время</option>
                            <option value="15">До 15 минут</option>
                            <option value="30">До 30 минут</option>
                            <option value="60">До 1 часа</option>
                            <option value="120">До 2 часов</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="home-sort" class="form-label">Сортировка</label>
                        <select name="sort" id="home-sort" class="form-select">
                            <option value="relevance">По релевантности</option>
                            <option value="latest">Новые первыми</option>
                            <option value="popular">Популярные</option>
                            <option value="cooking_time_asc">По времени (по возрастанию)</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="home-has_image" name="has_image" value="1">
                            <label class="form-check-label" for="home-has_image">Только с фото</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="fas fa-filter me-1"></i> Найти рецепты
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container my-5">
    <!-- Последние добавленные рецепты -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="animate-on-scroll">
                    <i class="fas fa-utensils text-primary me-2"></i> Новые рецепты
                </h2>
                <a href="{{ route('recipes.index') }}" class="btn btn-outline-primary animate-on-scroll">
                    <i class="fas fa-arrow-right me-1"></i> Все рецепты
                </a>
            </div>
            
            <div class="row">
                @foreach($latestRecipes as $recipe)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 animate-on-scroll" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        @if($recipe->image_url)
                        <img src="{{ $recipe->image_url }}" class="card-img-top recipe-image" alt="{{ $recipe->title }}">
                        @else
                        <div class="no-image">
                            <i class="fas fa-camera fa-2x"></i>
                            <span class="ms-2">Нет изображения</span>
                        </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $recipe->title }}</h5>
                            <p class="card-text">{{ Str::limit($recipe->description, 100) }}</p>
                            @if($recipe->cooking_time)
                            <p class="card-text recipe-time">
                                <i class="fas fa-clock"></i> {{ $recipe->cooking_time }} мин.
                            </p>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-1"></i> Подробнее
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Популярные категории -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="animate-on-scroll">
                    <i class="fas fa-tags text-primary me-2"></i> Популярные категории
                </h2>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary animate-on-scroll">
                    <i class="fas fa-arrow-right me-1"></i> Все категории
                </a>
            </div>
            
            <div class="row">
                @foreach($popularCategories as $category)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 category-card text-center animate-on-scroll" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="card-body">
                            <div class="category-icon mb-3">
                                <i class="fas fa-utensils fa-3x text-primary"></i>
                            </div>
                            <h3 class="card-title">{{ $category->name }}</h3>
                            <p class="text-muted">
                                <span class="animate-number" data-target="{{ $category->published_recipes_count }}">0</span> рецептов
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-right me-1"></i> Посмотреть
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Быстрые рецепты -->
    @if($quickRecipes->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card animate-on-scroll">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">
                        <i class="fas fa-bolt me-2"></i> Готовим быстро
                    </h2>
                    <p class="mb-0">Рецепты, которые можно приготовить за 30 минут или меньше</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($quickRecipes as $recipe)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm animate-on-scroll" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                @if($recipe->image_url)
                                <img src="{{ $recipe->image_url }}" class="card-img-top recipe-image" alt="{{ $recipe->title }}">
                                @else
                                <div class="no-image">
                                    <i class="fas fa-camera fa-2x"></i>
                                    <span class="ms-2">Нет изображения</span>
                                </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $recipe->title }}</h5>
                                    <p class="card-text recipe-time">
                                        <i class="fas fa-clock text-primary"></i> {{ $recipe->cooking_time }} мин.
                                    </p>
                                    <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-utensils me-1"></i> Приготовить
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- CTA блок -->
    <div class="row">
        <div class="col-12">
            <div class="p-5 text-center bg-light rounded-3 mb-4 animate-on-scroll">
                <h2><i class="fas fa-users me-2 text-primary"></i> Станьте частью нашего кулинарного сообщества!</h2>
                <p class="lead">Зарегистрируйтесь, чтобы сохранять любимые рецепты и делиться своими кулинарными шедеврами с другими.</p>
                @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-1"></i> Зарегистрироваться
                </a>
                @else
                <a href="{{ route('admin.recipes.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-1"></i> Добавить свой рецепт
                </a>
                @endguest
            </div>
        </div>
    </div>
</div>

<script>
// Функционал для поиска по ингредиентам на главной странице
document.addEventListener('DOMContentLoaded', function() {
    const addIngredientBtn = document.getElementById('home-add-ingredient');
    const ingredientInput = document.getElementById('home-ingredient-input');
    const ingredientsList = document.getElementById('home-ingredients-list');
    const searchBtn = document.getElementById('ingredients-search-btn');
    
    if (addIngredientBtn && ingredientInput && ingredientsList && searchBtn) {
        // Функция добавления ингредиента
        function addHomeIngredient() {
            const ingredient = ingredientInput.value.trim();
            if (ingredient) {
                // Проверяем, нет ли уже такого ингредиента
                const existingIngredients = Array.from(ingredientsList.querySelectorAll('input[type="hidden"]')).map(i => i.value);
                if (!existingIngredients.includes(ingredient)) {
                    // Создаем элемент
                    const ingredientItem = document.createElement('div');
                    ingredientItem.className = 'ingredient-item d-flex align-items-center justify-content-between mb-1 p-2 bg-light rounded';
                    ingredientItem.innerHTML = `
                        <span>${ingredient}</span>
                        <input type="hidden" name="ingredients[]" value="${ingredient}">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-home-ingredient">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    // Добавляем функцию удаления
                    const removeBtn = ingredientItem.querySelector('.remove-home-ingredient');
                    removeBtn.addEventListener('click', function() {
                        ingredientItem.remove();
                        updateSearchButtonState();
                    });
                    
                    // Добавляем в список
                    ingredientsList.appendChild(ingredientItem);
                    
                    // Очищаем поле ввода
                    ingredientInput.value = '';
                    
                    // Обновляем состояние кнопки поиска
                    updateSearchButtonState();
                } else {
                    alert('Этот ингредиент уже добавлен в список!');
                }
            }
        }
        
        // Функция обновления состояния кнопки поиска
        function updateSearchButtonState() {
            const hasIngredients = ingredientsList.querySelectorAll('input[type="hidden"]').length > 0;
            searchBtn.disabled = !hasIngredients;
        }
        
        // Обработчики событий
        addIngredientBtn.addEventListener('click', addHomeIngredient);
        
        ingredientInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addHomeIngredient();
            }
        });
        
        // Инициализация состояния кнопки
        updateSearchButtonState();
    }
});
</script>
@endsection
