@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Категории рецептов</h1>
            <p class="lead text-muted">Выберите категорию, чтобы найти вдохновляющие идеи для приготовления.</p>
        </div>
    </div>
    
    <!-- Популярные категории -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i> Популярные категории</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($popularCategories as $category)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                                    <div class="card h-100 popular-category-card border-0 shadow-sm">
                                       
                                        <div class="card-body text-center">
                                            <h5 class="card-title mb-2">{{ $category->name }}</h5>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-book me-1"></i> {{ $category->recipes_count }} {{ trans_choice('рецепт|рецепта|рецептов', $category->recipes_count) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Алфавитный указатель -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-th-list me-2"></i> Все категории</h4>
                </div>
                <div class="card-body">
                    <div class="alphabetical-index mb-4">
                        <p>Быстрый переход:</p>
                        <div class="d-flex flex-wrap alphabet-links">
                            @foreach($categoriesByLetter as $letter => $items)
                                <a href="#letter-{{ $letter }}" class="letter-link me-2 mb-2 btn btn-sm btn-outline-primary">{{ $letter }}</a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="categories-list mt-4">
                        @foreach($categoriesByLetter as $letter => $items)
                            <div class="letter-section mb-4" id="letter-{{ $letter }}">
                                <h3 class="letter-heading border-bottom pb-2 mb-3">{{ $letter }}</h3>
                                <div class="row g-3">
                                    @foreach($items as $category)
                                        <div class="col-md-4 col-sm-6">
                                            <a href="{{ route('categories.show', $category->slug) }}" class="category-link">
                                                <div class="d-flex align-items-center p-2 rounded hover-shadow">
                                                    <div class="category-icon me-3 rounded-circle {{ $category->getColorClass() }} text-white">
                                                        <i class="fas {{ $category->getIconClass() }}"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0">{{ $category->name }}</h5>
                                                        <small class="text-muted">{{ $category->recipes_count }} {{ trans_choice('рецепт|рецепта|рецептов', $category->recipes_count) }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Случайные рецепты для вдохновения -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Вдохновение дня</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($featuredRecipes as $recipe)
                            <div class="col-md-3 col-sm-6">
                                <div class="card h-100 featured-recipe-card border-0 shadow-sm">
                                    <div class="position-relative">
                                       
                                        @if($recipe->cooking_time)
                                            <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                                <i class="far fa-clock me-1"></i> {{ $recipe->cooking_time }} мин
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $recipe->title }}</h5>
                                        @if(!$recipe->categories->isEmpty())
                                            <div class="mb-2">
                                                @foreach($recipe->categories->take(3) as $category)
                                                    <a href="{{ route('categories.show', $category->slug) }}" class="badge bg-light text-dark text-decoration-none me-1">
                                                        {{ $category->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-eye me-1"></i> Посмотреть рецепт
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
</div>

<style>
    /* Стили для страницы категорий */
    .category-img {
        height: 150px;
        object-fit: cover;
        opacity: 0.8;
    }
    
    .category-image-container {
        height: 150px;
        overflow: hidden;
        position: relative;
    }
    
    .popular-category-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .popular-category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .category-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
    }
    
    .letter-heading {
        color: #6c757d;
        font-weight: 500;
    }
    
    .category-link {
        color: inherit;
        text-decoration: none;
    }
    
    .hover-shadow {
        transition: background-color 0.3s, box-shadow 0.3s;
    }
    
    .hover-shadow:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .featured-recipe-img {
        height: 180px;
        object-fit: cover;
    }
    
    .featured-recipe-card {
        transition: transform 0.3s;
    }
    
    .featured-recipe-card:hover {
        transform: translateY(-5px);
    }
    
    .letter-link {
        text-decoration: none;
        min-width: 36px;
        text-align: center;
    }
    
    /* Плавная прокрутка до секций */
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection
