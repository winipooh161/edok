@extends('layouts.app')

@section('meta_tags')
    <title>{{ $seo->getCategoryTitle($category) }}</title>
    <meta name="description" content="{{ $seo->getCategoryDescription($category) }}">
    <meta property="og:title" content="{{ $category->name }} - рецепты">
    <meta property="og:description" content="{{ $seo->getCategoryDescription($category) }}">
    <meta property="og:url" content="{{ route('categories.show', $category->slug) }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <link rel="canonical" href="{{ route('categories.show', $category->slug) }}" />
@endsection

@section('schema_org')
    <script type="application/ld+json">
        {!! $schemaData !!}
    </script>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Основное содержимое -->
        <div class="col-lg-8">
            <!-- Хедер категории -->
            <div class="category-header mb-4 p-4 rounded shadow-sm position-relative">
                <div class="category-bg-overlay {{ $category->getColorClass() }}"></div>
                <div class="position-relative">
                    <h1 class="display-5 fw-bold text-white mb-3">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="lead text-white mb-0">{{ $category->description }}</p>
                    @else
                        <p class="lead text-white mb-0">
                            Рецепты из категории "{{ $category->name }}" - {{ $recipes->total() }} {{ trans_choice('рецепт|рецепта|рецептов', $recipes->total()) }}
                        </p>
                    @endif
                </div>
            </div>
            
            <!-- Форма поиска внутри категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('categories.show', $category->slug) }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Поиск внутри категории</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" value="{{ $search ?? '' }}" placeholder="Название или ингредиент...">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="cooking_time" class="form-label">Время приготовления</label>
                            <select name="cooking_time" id="cooking_time" class="form-select" onchange="this.form.submit()">
                                <option value="">Любое время</option>
                                <option value="30" {{ ($cookingTime ?? '') == '30' ? 'selected' : '' }}>До 30 минут</option>
                                <option value="60" {{ ($cookingTime ?? '') == '60' ? 'selected' : '' }}>До 1 часа</option>
                                <option value="120" {{ ($cookingTime ?? '') == '120' ? 'selected' : '' }}>До 2 часов</option>
                                <option value="121" {{ ($cookingTime ?? '') == '121' ? 'selected' : '' }}>Более 2 часов</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Сортировать по</label>
                            <select name="sort" id="sort_by" class="form-select" onchange="this.form.submit()">
                                <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>Новые</option>
                                <option value="popular" {{ ($sort ?? '') == 'popular' ? 'selected' : '' }}>Популярные</option>
                                <option value="cooking_time_asc" {{ ($sort ?? '') == 'cooking_time_asc' ? 'selected' : '' }}>Время (по возрастанию)</option>
                                <option value="cooking_time_desc" {{ ($sort ?? '') == 'cooking_time_desc' ? 'selected' : '' }}>Время (по убыванию)</option>
                                @if(!empty($search))
                                <option value="relevance" {{ ($sort ?? '') == 'relevance' ? 'selected' : '' }}>Релевантности</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-1">
                            <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Информация о поиске -->
            @if(!empty($search))
                <div class="alert alert-info">
                    <i class="fas fa-search me-2"></i> Результаты поиска по запросу: <strong>"{{ $search }}"</strong>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark">Найдено: {{ $recipes->total() }} {{ trans_choice('рецепт|рецепта|рецептов', $recipes->total()) }}</span>
                        @if(isset($searchTerms))
                            <span class="badge bg-light text-dark">Искали: {{ implode(', ', $searchTerms) }}</span>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Список рецептов -->
            <div class="recipes-list mb-4">
                @if($recipes->count() > 0)
                    <div class="row g-4">
                        @foreach($recipes as $recipe)
                            <div class="col-md-6">
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
                                        <h5 class="card-title mb-2">
                                            @if(!empty($search))
                                                {!! preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->title) !!}
                                            @else
                                                {{ $recipe->title }}
                                            @endif
                                        </h5>
                                        
                                        @if($recipe->description)
                                            <p class="card-text mb-3 text-muted">
                                                @if(!empty($search))
                                                    {!! Str::limit(preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->description), 100) !!}
                                                @else
                                                    {{ Str::limit($recipe->description, 100) }}
                                                @endif
                                            </p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-light text-dark">
                                                    <i class="far fa-eye me-1"></i> {{ $recipe->views }}
                                                </span>
                                                
                                                @if($recipe->user)
                                                    <span class="badge bg-light text-dark ms-1">
                                                        <i class="far fa-user me-1"></i> {{ $recipe->user->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0 pb-3">
                                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-primary w-100">
                                            <i class="fas fa-book-open me-1"></i> Смотреть рецепт
                                        </a>
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
                        <i class="fas fa-info-circle me-2"></i> В данной категории пока нет рецептов, соответствующих вашему запросу.
                        @if(!empty($search))
                            <div class="mt-3">
                                <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-undo me-1"></i> Сбросить фильтры поиска
                                </a>
                                <a href="{{ route('search', ['query' => $search]) }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-search me-1"></i> Искать везде
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Боковая панель -->
        <div class="col-lg-4">
            <!-- Популярные рецепты категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i> Популярные рецепты</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($popularRecipes as $recipe)
                            <li class="list-group-item d-flex align-items-center p-3">
                                <img src="{{ $recipe->getImageUrl() }}" class="rounded me-3" alt="{{ $recipe->title }}" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="text-decoration-none text-dark">
                                            {{ $recipe->title }}
                                        </a>
                                    </h6>
                                    <div class="d-flex">
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="far fa-eye me-1"></i> {{ $recipe->views }}
                                        </span>
                                        @if($recipe->cooking_time)
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i> {{ $recipe->cooking_time }} мин
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Другие категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Другие категории</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($otherCategories as $otherCategory)
                            <div class="col-6">
                                <a href="{{ route('categories.show', $otherCategory->slug) }}" class="btn btn-outline-secondary btn-sm d-block mb-2">
                                    {{ $otherCategory->name }} <span class="badge bg-secondary ms-1">{{ $otherCategory->recipes_count }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Быстрые фильтры -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Быстрые фильтры</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('categories.show', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-list me-2"></i> Все рецепты</span>
                            <span class="badge bg-primary rounded-pill">{{ $category->recipes->where('is_published', true)->count() }}</span>
                        </a>
                        <a href="{{ route('categories.show', ['slug' => $category->slug, 'cooking_time' => 30]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bolt me-2"></i> Быстрые (до 30 мин)</span>
                            <span class="badge bg-success rounded-pill">{{ $category->recipes->where('is_published', true)->where('cooking_time', '<=', 30)->count() }}</span>
                        </a>
                        <a href="{{ route('categories.show', ['slug' => $category->slug, 'sort' => 'popular']) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-fire me-2"></i> Самые популярные
                        </a>
                        <a href="{{ route('categories.show', ['slug' => $category->slug, 'sort' => 'latest']) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-clock me-2"></i> Новые рецепты
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Стили для страницы категории */
    .category-header {
        position: relative;
        overflow: hidden;
    }
    
    .category-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.9;
        z-index: 0;
    }
    
    .recipe-img {
        height: 200px;
        object-fit: cover;
    }
    
    .recipe-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
