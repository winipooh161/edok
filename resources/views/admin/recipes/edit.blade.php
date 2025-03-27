@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h1><i class="fas fa-edit text-primary me-2"></i> Редактирование рецепта</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.recipes.index') }}">Список рецептов</a></li>
                    <li class="breadcrumb-item active">Редактирование рецепта</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-6 text-end">
            <div class="d-flex justify-content-end">
                <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-outline-primary me-2" target="_blank">
                    <i class="fas fa-eye me-1"></i> Просмотр
                </a>
                <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Назад к списку
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" id="recipe-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        <i class="fas fa-info-circle me-1"></i> Основная информация
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="false">
                        <i class="fas fa-carrot me-1"></i> Ингредиенты
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="instructions-tab" data-bs-toggle="tab" data-bs-target="#instructions" type="button" role="tab" aria-controls="instructions" aria-selected="false">
                        <i class="fas fa-list-ol me-1"></i> Инструкции
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" type="button" role="tab" aria-controls="additional" aria-selected="false">
                        <i class="fas fa-cog me-1"></i> Дополнительно
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="tab-content" id="recipe-tabs-content">
                    <!-- Основная информация -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Название рецепта <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $recipe->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Описание рецепта</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $recipe->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Краткое описание блюда, его особенности и интересные факты</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="categories" class="form-label">Категории</label>
                                    <select class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Фото блюда</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($recipe->image_url)
                                        <div class="mt-3 text-center">
                                            <img src="{{ $recipe->getImageUrl() }}" alt="{{ $recipe->title }}" class="img-thumbnail" style="max-height: 200px;">
                                            <div class="form-text">Текущее изображение</div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" {{ old('is_published', $recipe->is_published) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">Опубликовать рецепт</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ингредиенты -->
                    <div class="tab-pane fade" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
                        <div class="mb-3">
                            <label for="ingredients" class="form-label">Ингредиенты <span class="text-danger">*</span></label>
                            <div class="card shadow-sm mb-2">
                                <div class="card-header bg-light small">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    Каждый ингредиент с новой строки в формате: <strong>название - количество единица измерения</strong> (напр. "Мука - 300 г")
                                </div>
                            </div>
                            <textarea class="form-control @error('ingredients') is-invalid @enderror" id="ingredients" name="ingredients" rows="12" required>{{ old('ingredients', $recipe->ingredients) }}</textarea>
                            @error('ingredients')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Инструкции -->
                    <div class="tab-pane fade" id="instructions" role="tabpanel" aria-labelledby="instructions-tab">
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Инструкции по приготовлению <span class="text-danger">*</span></label>
                            <div class="card shadow-sm mb-2">
                                <div class="card-header bg-light small">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    Каждый шаг с новой строки. Можно использовать формат "Шаг 1: Описание"
                                </div>
                            </div>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" id="instructions" name="instructions" rows="12" required>{{ old('instructions', $recipe->instructions) }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Дополнительная информация -->
                    <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cooking_time" class="form-label">Время приготовления (минуты)</label>
                                    <input type="number" class="form-control @error('cooking_time') is-invalid @enderror" id="cooking_time" name="cooking_time" value="{{ old('cooking_time', $recipe->cooking_time) }}" min="1">
                                    @error('cooking_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="servings" class="form-label">Количество порций</label>
                                    <input type="number" class="form-control @error('servings') is-invalid @enderror" id="servings" name="servings" value="{{ old('servings', $recipe->servings) }}" min="1">
                                    @error('servings')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="calories" class="form-label">Калорийность (ккал на 100г)</label>
                                    <input type="number" class="form-control @error('calories') is-invalid @enderror" id="calories" name="calories" value="{{ old('calories', $recipe->calories) }}" min="0">
                                    @error('calories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="proteins" class="form-label">Белки (г)</label>
                                            <input type="number" class="form-control @error('proteins') is-invalid @enderror" id="proteins" name="proteins" value="{{ old('proteins', $recipe->proteins) }}" min="0" step="0.1">
                                            @error('proteins')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="fats" class="form-label">Жиры (г)</label>
                                            <input type="number" class="form-control @error('fats') is-invalid @enderror" id="fats" name="fats" value="{{ old('fats', $recipe->fats) }}" min="0" step="0.1">
                                            @error('fats')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="carbs" class="form-label">Углеводы (г)</label>
                                            <input type="number" class="form-control @error('carbs') is-invalid @enderror" id="carbs" name="carbs" value="{{ old('carbs', $recipe->carbs) }}" min="0" step="0.1">
                                            @error('carbs')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="source_url" class="form-label">Источник рецепта (URL)</label>
                            <input type="url" class="form-control @error('source_url') is-invalid @enderror" id="source_url" name="source_url" value="{{ old('source_url', $recipe->source_url) }}">
                            @error('source_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Отмена
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Сохранить изменения
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Активация первой вкладки при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация выбора категорий
        if (typeof(bootstrap.Tab) !== 'undefined') {
            var triggerTabList = [].slice.call(document.querySelectorAll('#recipe-tabs a'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        }
        
        // Подсветка синтаксиса для текстовых областей
        const highlightAreas = ['ingredients', 'instructions'];
        highlightAreas.forEach(area => {
            const textarea = document.getElementById(area);
            if (textarea) {
                textarea.addEventListener('input', function() {
                    // Можно добавить дополнительную логику форматирования
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
                // Инициализация высоты
                textarea.dispatchEvent(new Event('input'));
            }
        });
    });
</script>
@endpush
