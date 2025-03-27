@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Главный баннер с расширенным поиском -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="homepage-header p-4 p-md-5 mb-4 text-white rounded bg-dark position-relative overflow-hidden">
                <!-- Фоновое изображение с наложением -->
                <div class="banner-background" style="background-image: url('{{ asset('images/banner-bg.jpg') }}');"></div>
                
                <div class="row position-relative">
                    <div class="col-lg-7">
                        <h1 class="display-4 fw-bold">Кулинарная книга</h1>
                        <p class="lead my-3">Найдите идеальный рецепт для вашего следующего кулинарного шедевра</p>
                        
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
                                    @if(Route::has('search'))
                                    <form action="{{ route('search') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="query" placeholder="Введите название блюда...">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search me-1"></i> Найти
                                            </button>
                                        </div>
                                    </form>
                                    @else
                                    <form action="{{ route('recipes.index') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="Введите название блюда...">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search me-1"></i> Найти
                                            </button>
                                        </div>
                                    </form>
                                    @endif
                                </div>
                                
                                <!-- Поиск по ингредиентам -->
                                <div class="tab-pane fade" id="ingredient-search" role="tabpanel">
                                    @if(Route::has('search'))
                                    <form action="{{ route('search') }}" method="GET">
                                        <input type="hidden" name="search_type" value="ingredients">
                                        <div class="mb-3">
                                            <div class="ingredient-tags input-group">
                                                <input type="text" id="ingredient-input" class="form-control" placeholder="Введите ингредиент и нажмите Enter...">
                                                <button type="button" id="add-ingredient" class="btn btn-outline-secondary">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="selected-ingredients" class="mb-3"></div>
                                        <div id="ingredient-chips" class="mb-3"></div>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-utensils me-1"></i> Найти рецепты
                                        </button>
                                    </form>
                                    @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Поиск по ингредиентам скоро будет доступен!
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Расширенный поиск -->
                                <div class="tab-pane fade" id="advanced-search" role="tabpanel">
                                    @if(Route::has('search'))
                                    <form action="{{ route('search') }}" method="GET">
                                        <input type="hidden" name="search_type" value="advanced">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="category" class="form-label">Категория</label>
                                                <select name="category" id="category" class="form-select">
                                                    <option value="">Любая категория</option>
                                                    @foreach($popularCategories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cooking_time" class="form-label">Время приготовления</label>
                                                <select name="cooking_time" id="cooking_time" class="form-select">
                                                    <option value="">Любое время</option>
                                                    <option value="15">До 15 минут</option>
                                                    <option value="30">До 30 минут</option>
                                                    <option value="60">До 1 часа</option>
                                                    <option value="120">До 2 часов</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="has_image" name="has_image" value="1">
                                                    <label class="form-check-label" for="has_image">Только с фото</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100" type="submit">
                                                    <i class="fas fa-filter me-1"></i> Применить фильтры
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Расширенный поиск скоро будет доступен!
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="popular-searches mt-2">
                            <small class="text-light">Популярные запросы:</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @if(Route::has('search'))
                                <a href="{{ route('search', ['query' => 'завтрак']) }}" class="badge bg-light text-dark text-decoration-none">завтрак</a>
                                <a href="{{ route('search', ['query' => 'десерт']) }}" class="badge bg-light text-dark text-decoration-none">десерт</a>
                                <a href="{{ route('search', ['query' => 'суп']) }}" class="badge bg-light text-dark text-decoration-none">суп</a>
                                <a href="{{ route('search', ['query' => 'салат']) }}" class="badge bg-light text-dark text-decoration-none">салат</a>
                                <a href="{{ route('search', ['query' => 'ужин']) }}" class="badge bg-light text-dark text-decoration-none">ужин</a>
                                @else
                                <a href="{{ route('recipes.index', ['search' => 'завтрак']) }}" class="badge bg-light text-dark text-decoration-none">завтрак</a>
                                <a href="{{ route('recipes.index', ['search' => 'десерт']) }}" class="badge bg-light text-dark text-decoration-none">десерт</a>
                                <a href="{{ route('recipes.index', ['search' => 'суп']) }}" class="badge bg-light text-dark text-decoration-none">суп</a>
                                <a href="{{ route('recipes.index', ['search' => 'салат']) }}" class="badge bg-light text-dark text-decoration-none">салат</a>
                                <a href="{{ route('recipes.index', ['search' => 'ужин']) }}" class="badge bg-light text-dark text-decoration-none">ужин</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Быстрый доступ к популярным категориям -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="category-carousel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="m-0"><i class="fas fa-th-large text-primary me-2"></i> Выберите категорию</h4>
                    @if(Route::has('categories.index'))
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-primary">Все категории</a>
                    @endif
                </div>
                <div class="row g-2 categories-grid">
                    @foreach($popularCategories->take(8) as $category)
                    <div class="col-6 col-md-3 col-lg-2">
                        @if(Route::has('categories.show'))
                        <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                        @else
                        <a href="{{ route('recipes.index', ['category_id' => $category->id]) }}" class="text-decoration-none">
                        @endif
                            <div class="card h-100 category-card text-center">
                                <div class="card-body">
                                    <div class="category-icon mb-2">
                                        <i class="fas fa-utensils text-primary"></i>
                                    </div>
                                    <h6 class="card-title m-0">{{ $category->name }}</h6>
                                    <span class="badge bg-light text-dark">{{ $category->recipes_count }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Секция "Последние рецепты" -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-clock text-primary me-2"></i> Новые рецепты</h2>
                <a href="{{ route('recipes.index', ['sort' => 'latest']) }}" class="btn btn-outline-primary">
                    Все новинки <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($latestRecipes as $recipe)
                <div class="col">
                    <div class="card h-100 recipe-card hover-shadow">
                        <div class="position-relative">
                            <img src="{{ $recipe->getImageUrl() }}" class="card-img-top recipe-img" alt="{{ $recipe->title }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror='';">
                            @if($recipe->cooking_time)
                            <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                <i class="far fa-clock me-1"></i> {{ $recipe->cooking_time }} мин
                            </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $recipe->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($recipe->description ?? 'Вкусный рецепт', 100) }}</p>
                            
                            <div class="recipe-meta d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($recipe->calories)
                                    <span class="badge bg-warning text-dark me-1"><i class="fas fa-fire"></i> {{ $recipe->calories }} ккал</span>
                                    @endif
                                    
                                    @if(!$recipe->categories->isEmpty())
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-tag"></i> {{ $recipe->categories->first()->name }}
                                    </span>
                                    @endif
                                </div>
                                
                                <div class="recipe-difficulty">
                                    @php
                                        $difficulty = 'Легкий';
                                        $difficultyClass = 'text-success';
                                        if ($recipe->cooking_time > 60) {
                                            $difficulty = 'Сложный';
                                            $difficultyClass = 'text-danger';
                                        } elseif ($recipe->cooking_time > 30) {
                                            $difficulty = 'Средний';
                                            $difficultyClass = 'text-warning';
                                        }
                                    @endphp
                                    <small class="{{ $difficultyClass }}">
                                        <i class="fas fa-circle me-1"></i> {{ $difficulty }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-grid">
                                <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-primary">
                                    <i class="fas fa-book-open me-1"></i> Смотреть рецепт
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Секция "Быстрые рецепты" -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="quick-recipes-section p-4 rounded shadow-sm bg-light">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-bolt text-warning me-2"></i> Быстрые рецепты</h2>
                    <a href="{{ route('recipes.index', ['cooking_time' => 30]) }}" class="btn btn-warning">
                        <i class="fas fa-stopwatch me-1"></i> Все быстрые рецепты
                    </a>
                </div>
                
                <div class="row">
                    @php
                        $quickRecipes = $latestRecipes->where('cooking_time', '<=', 30)->take(4);
                    @endphp
                    
                    @forelse($quickRecipes as $recipe)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-0 shadow-sm quick-recipe-card">
                            <div class="position-relative">
                                <img src="{{ $recipe->getImageUrl() }}" class="card-img-top quick-recipe-img" alt="{{ $recipe->title }}" onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror='';">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-bolt me-1"></i> {{ $recipe->cooking_time }} мин
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $recipe->title }}</h5>
                                <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-outline-warning w-100 mt-2">
                                    <i class="fas fa-utensils me-1"></i> Приготовить
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Быстрые рецепты появятся здесь совсем скоро!
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Блок - Рецепты на все случаи -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4"><i class="fas fa-calendar-day text-primary me-2"></i> Рецепты на все случаи</h2>
            
            <div class="row g-4">
                <!-- Карточка "Завтрак" -->
                <div class="col-md-4">
                    <div class="meal-type-card position-relative overflow-hidden rounded shadow">
                        <img src="{{ asset('images/breakfast.jpg') }}" alt="Завтрак" class="w-100 meal-type-img" onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror='';">
                        <div class="meal-type-overlay d-flex align-items-end">
                            <div class="w-100 p-3">
                                <h3 class="text-white mb-2">Завтрак</h3>
                                <p class="text-white mb-3">Начните день с вкусного и полезного завтрака</p>
                                @if(Route::has('search'))
                                <a href="{{ route('search', ['query' => 'завтрак']) }}" class="btn btn-light">
                                @else
                                <a href="{{ route('recipes.index', ['search' => 'завтрак']) }}" class="btn btn-light">
                                @endif
                                    <i class="fas fa-coffee me-1"></i> Идеи для завтрака
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Карточка "Обед" -->
                <div class="col-md-4">
                    <div class="meal-type-card position-relative overflow-hidden rounded shadow">
                        <img src="{{ asset('images/lunch.jpg') }}" alt="Обед" class="w-100 meal-type-img" onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror='';">
                        <div class="meal-type-overlay d-flex align-items-end">
                            <div class="w-100 p-3">
                                <h3 class="text-white mb-2">Обед</h3>
                                <p class="text-white mb-3">Сытные и разнообразные блюда для обеденного перерыва</p>
                                @if(Route::has('search'))
                                <a href="{{ route('search', ['query' => 'обед']) }}" class="btn btn-light">
                                @else
                                <a href="{{ route('recipes.index', ['search' => 'обед']) }}" class="btn btn-light">
                                @endif
                                    <i class="fas fa-hamburger me-1"></i> Обеденные рецепты
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Карточка "Ужин" -->
                <div class="col-md-4">
                    <div class="meal-type-card position-relative overflow-hidden rounded shadow">
                        <img src="{{ asset('images/dinner.jpg') }}" alt="Ужин" class="w-100 meal-type-img" onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror='';">
                        <div class="meal-type-overlay d-flex align-items-end">
                            <div class="w-100 p-3">
                                <h3 class="text-white mb-2">Ужин</h3>
                                <p class="text-white mb-3">Вечерние блюда для всей семьи</p>
                                @if(Route::has('search'))
                                <a href="{{ route('search', ['query' => 'ужин']) }}" class="btn btn-light">
                                @else
                                <a href="{{ route('recipes.index', ['search' => 'ужин']) }}" class="btn btn-light">
                                @endif
                                    <i class="fas fa-utensils me-1"></i> Рецепты для ужина
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Секция "Популярные категории" -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tags text-primary me-2"></i> Популярные категории</h2>
                @if(Route::has('categories.index'))
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                    Все категории <i class="fas fa-arrow-right ms-1"></i>
                </a>
                @endif
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($popularCategories->take(6) as $category)
                <div class="col">
                    @if(Route::has('categories.show'))
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                    @else
                    <a href="{{ route('recipes.index', ['category_id' => $category->id]) }}" class="text-decoration-none">
                    @endif
                        <div class="card h-100 category-card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="category-icon mb-3">
                                    <span class="category-icon-circle bg-light d-inline-block rounded-circle">
                                        <i class="fas fa-utensils text-primary"></i>
                                    </span>
                                </div>
                                <h3 class="card-title">{{ $category->name }}</h3>
                                <p class="card-text text-muted">
                                    {{ $category->recipes_count }} {{ trans_choice('рецепт|рецепта|рецептов', $category->recipes_count) }}
                                </p>
                                <div class="category-view-btn">
                                    <span class="btn btn-sm btn-primary px-4">
                                        <i class="fas fa-eye me-1"></i> Посмотреть
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Секция "Советы и рекомендации" -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="cooking-tips bg-light p-4 rounded shadow-sm">
                <h2 class="mb-4"><i class="fas fa-lightbulb text-warning me-2"></i> Кулинарные советы</h2>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-temperature-high text-danger me-2"></i> Готовка на плите</h5>
                                <p class="card-text">Не спешите с высокой температурой. Часто медленное приготовление на среднем огне дает лучший результат и равномерное прожаривание.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-mortar-pestle text-success me-2"></i> Специи и травы</h5>
                                <p class="card-text">Добавляйте сухие травы в начале готовки, а свежие - в конце, чтобы сохранить их аромат и полезные свойства.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-blender text-primary me-2"></i> Подготовка продуктов</h5>
                                <p class="card-text">Заранее подготовьте и нарежьте все ингредиенты перед началом готовки, чтобы не тратить время в процессе.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('recipes.index') }}" class="btn btn-warning">
                        <i class="fas fa-book me-1"></i> Больше советов
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Секция "Присоединяйтесь к нам" -->
    <div class="row">
        <div class="col-12">
            <div class="join-us-section text-center bg-primary text-white p-5 rounded shadow">
                <h2 class="mb-3"><i class="fas fa-users me-2"></i> Присоединяйтесь к нашему сообществу</h2>
                <p class="lead mb-4">Делитесь своими рецептами, получайте отзывы и вдохновляйтесь новыми идеями!</p>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    @guest
                        @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 me-md-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Войти
                        </a>
                        @endif
                        
                        @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-user-plus me-1"></i> Зарегистрироваться
                        </a>
                        @endif
                    @else
                        @if(Route::has('admin.recipes.create'))
                        <a href="{{ route('admin.recipes.create') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-plus-circle me-1"></i> Добавить свой рецепт
                        </a>
                        @elseif(Route::has('recipes.create'))
                        <a href="{{ route('recipes.create') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-plus-circle me-1"></i> Добавить свой рецепт
                        </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS для новых элементов страницы -->
<style>
    /* Стили для главного баннера */
    .homepage-header {
        position: relative;
        z-index: 1;
    }
    
    .banner-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0.5;
        z-index: -1;
    }
    
    /* Карточки рецептов */
    .recipe-img {
        height: 200px;
        object-fit: cover;
    }
    
    .recipe-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .recipe-difficulty i {
        font-size: 8px;
    }
    
    /* Карточки категорий */
    .category-icon-circle {
        width: 60px;
        height: 60px;
        line-height: 60px;
    }
    
    .category-icon-circle i {
        font-size: 24px;
    }
    
    .category-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .category-view-btn {
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .category-card:hover .category-view-btn {
        opacity: 1;
    }
    
    /* Карточки быстрых рецептов */
    .quick-recipe-img {
        height: 150px;
        object-fit: cover;
    }
    
    .quick-recipe-card {
        transition: transform 0.3s;
    }
    
    .quick-recipe-card:hover {
        transform: translateY(-5px);
    }
    
    /* Типы приемов пищи */
    .meal-type-img {
        height: 200px;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .meal-type-card:hover .meal-type-img {
        transform: scale(1.1);
    }
    
    .meal-type-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.1) 100%);
        transition: background 0.3s;
    }
    
    .meal-type-card:hover .meal-type-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 100%);
    }
    
    /* Ингредиенты */
    #ingredient-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .ingredient-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background-color: #e9ecef;
        border-radius: 16px;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .remove-ingredient {
        margin-left: 5px;
        cursor: pointer;
        color: #dc3545;
    }
</style>

<!-- JavaScript для интерактивных элементов с проверкой существования DOM элементов -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функционал для добавления ингредиентов в поиск
    const ingredientInput = document.getElementById('ingredient-input');
    const addIngredientBtn = document.getElementById('add-ingredient');
    const ingredientChips = document.getElementById('ingredient-chips');
    const selectedIngredientsContainer = document.getElementById('selected-ingredients');
    
    if (ingredientInput && addIngredientBtn && ingredientChips && selectedIngredientsContainer) {
        let selectedIngredients = [];
        
        // Добавление ингредиента
        function addIngredient() {
            const ingredient = ingredientInput.value.trim();
            if (ingredient && !selectedIngredients.includes(ingredient)) {
                selectedIngredients.push(ingredient);
                updateIngredientChips();
                updateHiddenFields();
                ingredientInput.value = '';
            }
        }
        
        // Обновление отображения выбранных ингредиентов
        function updateIngredientChips() {
            ingredientChips.innerHTML = '';
            
            selectedIngredients.forEach((ingredient, index) => {
                const chip = document.createElement('div');
                chip.className = 'ingredient-chip';
                chip.innerHTML = `
                    ${ingredient}
                    <span class="remove-ingredient" data-index="${index}">
                        <i class="fas fa-times-circle"></i>
                    </span>
                `;
                ingredientChips.appendChild(chip);
            });
            
            // Обработчики для кнопок удаления
            document.querySelectorAll('.remove-ingredient').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    selectedIngredients.splice(index, 1);
                    updateIngredientChips();
                    updateHiddenFields();
                });
            });
        }
        
        // Обновление скрытых полей формы
        function updateHiddenFields() {
            selectedIngredientsContainer.innerHTML = '';
            
            selectedIngredients.forEach(ingredient => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ingredients[]';
                input.value = ingredient;
                selectedIngredientsContainer.appendChild(input);
            });
        }
        
        // Добавление ингредиента по клику на кнопку
        addIngredientBtn.addEventListener('click', addIngredient);
        
        // Добавление ингредиента по нажатию Enter
        ingredientInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addIngredient();
            }
        });
    }
    
    // Реализация поведения для любых страниц, где могут отсутствовать необходимые элементы
    const searchTabs = document.getElementById('searchTabs');
    if (searchTabs) {
        const tabButtons = searchTabs.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Убираем класс active у всех кнопок
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-selected', 'false');
                    document.querySelector(btn.dataset.bsTarget).classList.remove('show', 'active');
                });
                
                // Добавляем класс active на кликнутую кнопку
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                document.querySelector(this.dataset.bsTarget).classList.add('show', 'active');
            });
        });
    }

    // Настройка всех изображений категорий и типов блюд
    document.querySelectorAll('.meal-type-img, .recipe-img, .quick-recipe-img').forEach(img => {
        if (!img.complete || img.naturalHeight === 0) {
            img.src = '/images/placeholder.jpg'; 
            // Если путь к placeholder.jpg неверный, добавьте ему событие onerror
            img.onerror = function() {
                this.src = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22286%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20286%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18bd4d69fff%20text%20%7B%20fill%3A%23999%3Bfont-weight%3Anormal%3Bfont-family%3Avar(--bs-font-sans-serif)%2C%20monospace%3Bfont-size%3A14pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18bd4d69fff%22%3E%3Crect%20width%3D%22286%22%20height%3D%22180%22%20fill%3D%22%23373940%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22108.5390625%22%20y%3D%2296.3%22%3E286x180%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';
                this.onerror = null;
            };
        }
    });
});
</script>
@endsection
