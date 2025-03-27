@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Создание нового рецепта</h1>
            <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">Назад к списку</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <!-- Добавим информацию о том, кто будет автором -->
            <div class="alert alert-info mb-4">
                <p class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> 
                    Рецепт будет создан от имени {{ auth()->user()->name }} 
                    @if(auth()->user()->isAdmin())
                        <span class="badge bg-danger">Администратор</span>
                    @else
                        <span class="badge bg-primary">Пользователь</span>
                    @endif
                </p>
            </div>
            
            <form action="{{ route('admin.recipes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Название рецепта*</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="categories" class="form-label">Категории</label>
                            <select multiple class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Удерживайте Ctrl (Cmd на Mac) для выбора нескольких категорий</small>
                            @error('categories')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="ingredients" class="form-label">Ингредиенты*</label>
                            <textarea class="form-control @error('ingredients') is-invalid @enderror" id="ingredients" name="ingredients" rows="6" placeholder="Введите каждый ингредиент с новой строки" required>{{ old('ingredients') }}</textarea>
                            <small class="form-text text-muted">Каждый ингредиент с новой строки</small>
                            @error('ingredients')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Инструкции по приготовлению*</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" id="instructions" name="instructions" rows="8" placeholder="Введите каждый шаг с новой строки" required>{{ old('instructions') }}</textarea>
                            <small class="form-text text-muted">Каждый шаг с новой строки</small>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение блюда</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <small class="form-text text-muted">Рекомендуемый размер: 1200x800 пикселей</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="cooking_time" class="form-label">Время приготовления (в минутах)</label>
                            <input type="number" class="form-control @error('cooking_time') is-invalid @enderror" id="cooking_time" name="cooking_time" min="1" value="{{ old('cooking_time') }}">
                            @error('cooking_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="source_url" class="form-label">Ссылка на источник</label>
                            <input type="url" class="form-control @error('source_url') is-invalid @enderror" id="source_url" name="source_url" value="{{ old('source_url') }}">
                            @error('source_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" checked>
                            <label class="form-check-label" for="is_published">Опубликовать сразу</label>
                        </div>

                        <!-- Блок для энергетической ценности -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Энергетическая ценность (на 100г)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label for="calories" class="form-label">Калории (ккал)</label>
                                        <input type="number" class="form-control @error('calories') is-invalid @enderror" id="calories" name="calories" value="{{ old('calories') }}" min="0">
                                        @error('calories')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="proteins" class="form-label">Белки (г)</label>
                                        <input type="number" class="form-control @error('proteins') is-invalid @enderror" id="proteins" name="proteins" value="{{ old('proteins') }}" min="0" step="0.1">
                                        @error('proteins')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="fats" class="form-label">Жиры (г)</label>
                                        <input type="number" class="form-control @error('fats') is-invalid @enderror" id="fats" name="fats" value="{{ old('fats') }}" min="0" step="0.1">
                                        @error('fats')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="carbs" class="form-label">Углеводы (г)</label>
                                        <input type="number" class="form-control @error('carbs') is-invalid @enderror" id="carbs" name="carbs" value="{{ old('carbs') }}" min="0" step="0.1">
                                        @error('carbs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Добавляем чекбокс с согласием перед кнопкой сохранения -->
                <div class="form-group mb-4">
                    <div class="form-check">
                        <input class="form-check-input @error('terms_agreement') is-invalid @enderror" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                        <label class="form-check-label" for="terms_agreement">
                            Публикуя рецепт, я подтверждаю, что имею права на размещение всех материалов и согласен с <a href="{{ route('legal.terms') }}" target="_blank">пользовательским соглашением</a>.
                        </label>
                        @error('terms_agreement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Создать рецепт</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
