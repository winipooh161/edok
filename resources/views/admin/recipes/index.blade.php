@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1>Управление рецептами</h1>
            @if(auth()->user()->isAdmin())
            <p class="text-muted">Вы просматриваете список всех рецептов (администраторский доступ)</p>
            @else
            <p class="text-muted">Вы просматриваете список ваших рецептов</p>
            @endif
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.recipes.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Добавить рецепт
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.parser.index') }}" class="btn btn-primary ms-2">
                <i class="fas fa-spider me-1"></i> Парсер рецептов
            </a>
            @endif
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">Фильтры</div>
        <div class="card-body">
            <form action="{{ route('admin.recipes.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Поиск по названию</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label for="category_id" class="form-label">Категория</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Добавляем фильтр по пользователю только для админов -->
                @if(auth()->user()->isAdmin() && isset($users))
                <div class="col-md-4">
                    <label for="user_id" class="form-label">Автор</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">Все авторы</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->role == 'admin' ? '(Admin)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                    <a href="{{ route('admin.recipes.index') }}" class="btn btn-secondary">Сбросить</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Список рецептов</h5>
            <span class="badge bg-secondary">{{ $recipes->total() }} рецептов</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Автор</th>
                            <th>Категории</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipes as $recipe)
                            <tr>
                                <td>{{ $recipe->id }}</td>
                                <td>
                                    @if($recipe->image_url)
                                        <img src="{{ asset($recipe->image_url) }}" alt="{{ $recipe->title }}" class="img-thumbnail" style="max-width: 80px; max-height: 60px;">
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('recipes.show', $recipe->slug) }}" target="_blank">
                                        {{ $recipe->title }}
                                    </a>
                                </td>
                                <td>{{ $recipe->user ? $recipe->user->name : 'Не указан' }}</td>
                                <td>
                                    @foreach($recipe->categories as $category)
                                        <span class="badge bg-secondary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($recipe->is_published)
                                        <span class="badge bg-success">Опубликован</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Не опубликован</span>
                                    @endif
                                </td>
                                <td>{{ $recipe->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    @if($recipe->isOwnedBy(auth()->user()))
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.recipes.edit', $recipe->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот рецепт?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                        <span class="badge bg-secondary">Нет доступа</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Рецепты не найдены
                                    </div>
                                    <a href="{{ route('admin.recipes.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Добавить рецепт
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $recipes->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
