@component('mail::message')
# Уведомление о нарушении авторских прав

Получена новая жалоба о нарушении авторских прав на сайте.

**Информация о заявителе:**
- **Имя/организация:** {{ $name }}
- **Email:** {{ $email }}

**Детали жалобы:**
- **URL проблемного контента:** {{ $content_url }}
@if($original_url)
- **URL оригинального материала:** {{ $original_url }}
@endif

**Описание проблемы:**
{{ $description }}

@component('mail::button', ['url' => $content_url])
Просмотреть контент
@endcomponent

Пожалуйста, проверьте информацию и примите необходимые меры.

С уважением,<br>
{{ config('app.name') }}
@endcomponent
