@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Результаты пакетного парсинга</h3>
                        <div>
                            <a href="{{ route('admin.parser.batch') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-sync-alt"></i> Запустить новый пакетный парсинг
                            </a>
                            <a href="{{ route('admin.recipes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list"></i> К списку рецептов
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h4>Обработка завершена</h4>
                    <div class="mb-4">
                        <p>Успешно обработано: <strong>{{ isset($processed) ? count($processed) : 0 }}</strong></p>
                        <p>Не удалось обработать: <strong>{{ isset($failed) ? count($failed) : 0 }}</strong></p>
                    </div>

                    @if(isset($processed) && count($processed) > 0)
                        <h5>Успешно созданные рецепты:</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>URL</th>
                                        <th>Название рецепта</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processed as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ $item['url'] }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 300px;">
                                                    {{ $item['url'] }}
                                                </a>
                                            </td>
                                            <td>{{ $item['title'] }}</td>
                                            <td>
                                                <a href="{{ route('admin.recipes.edit', $item['id']) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Редактировать
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if(isset($failed) && count($failed) > 0)
                        <h5 class="mt-4">Не удалось обработать следующие URL:</h5>
                        <div class="table-responsive">
                            <table class="table table-danger">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>URL</th>
                                        <th>Ошибка</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($failed as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ $item['url'] }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 300px;">
                                                    {{ $item['url'] }}
                                                </a>
                                            </td>
                                            <td>{{ $item['error'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            @if(isset($total_urls) || isset($duplicate_urls))
            <div class="card mb-4 mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Результаты проверки URL</h5>
                </div>
                <div class="card-body">
                    @if(isset($message))
                        <div class="alert alert-info">
                            {{ $message }}
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <h6>Статистика:</h6>
                        <ul>
                            @if(isset($total_urls))
                                <li>Уникальных URL для обработки: <strong>{{ $total_urls }}</strong></li>
                            @endif
                            @if(isset($duplicate_urls) && is_array($duplicate_urls) && count($duplicate_urls) > 0)
                                <li>Пропущено дубликатов: <strong>{{ count($duplicate_urls) }}</strong></li>
                            @endif
                        </ul>
                    </div>
                    
                    @if(isset($duplicate_urls) && is_array($duplicate_urls) && count($duplicate_urls) > 0)
                        <div class="mb-3">
                            <h6>Список пропущенных URL (уже есть в базе):</h6>
                            <div class="border p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <ol class="mb-0">
                                    @foreach($duplicate_urls as $url)
                                        <li><a href="{{ $url }}" target="_blank">{{ $url }}</a></li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Кнопки для продолжения или отмены процесса -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.parser.batch') }}" class="btn btn-secondary">Вернуться</a>
                        @if(isset($total_urls) && $total_urls > 0)
                            <a href="{{ route('admin.parser.processBatch') }}" class="btn btn-primary">
                                Начать обработку {{ $total_urls }} URL
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
