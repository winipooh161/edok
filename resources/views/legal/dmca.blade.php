@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Форма для правообладателей</h1>
                </div>
                <div class="card-body">
                    <p>Если вы обнаружили на нашем сайте материалы, которые нарушают ваши авторские права, пожалуйста, заполните эту форму для подачи жалобы.</p>
                    
                    <div class="alert alert-info mb-4">
                        <p class="mb-0">Мы рассматриваем все обращения правообладателей и стремимся оперативно реагировать на запросы об удалении контента. Материалы, нарушающие авторские права, будут удалены в кратчайшие сроки.</p>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('legal.dmca.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Ваше имя или наименование организации *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Ваш email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_url" class="form-label">URL страницы с нарушающим материалом *</label>
                            <input type="url" class="form-control @error('content_url') is-invalid @enderror" id="content_url" name="content_url" value="{{ old('content_url') }}" required>
                            @error('content_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Укажите полный URL страницы, на которой размещен контент, нарушающий ваши права</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="original_url" class="form-label">URL оригинального материала (если имеется)</label>
                            <input type="url" class="form-control @error('original_url') is-invalid @enderror" id="original_url" name="original_url" value="{{ old('original_url') }}">
                            @error('original_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Если возможно, укажите ссылку на оригинальный контент, чтобы мы могли подтвердить ваши права</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание проблемы *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Пожалуйста, опишите нарушение ваших прав и укажите, какой именно контент (текст, изображение, видео) нарушает ваши авторские права</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('confirmation') is-invalid @enderror" type="checkbox" id="confirmation" name="confirmation" required>
                                <label class="form-check-label" for="confirmation">
                                    Я подтверждаю, что являюсь правообладателем или уполномоченным представителем правообладателя, и имею все основания для подачи этой жалобы *
                                </label>
                                @error('confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Отправить жалобу</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0">* - Обязательные поля для заполнения</p>
                </div>
            </div>
            
            <div class="mt-4">
                <p>Также вы можете связаться с нами по электронной почте: <a href="mailto:{{ config('app.contact_email', 'contact@example.com') }}">{{ config('app.contact_email', 'contact@example.com') }}</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
