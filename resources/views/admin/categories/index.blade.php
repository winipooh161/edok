@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h1>Управление категориями</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Добавить категорию</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Slug</th>
                            <th>Рецептов</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->recipes_count }}</td>
                            <td>{{ $category->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-sm btn-info" target="_blank">Просмотр</a>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
