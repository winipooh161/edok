

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Пользовательское соглашение</h1>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>1. Общие положения</h5>
                        <p>1.1. Настоящее Пользовательское соглашение (далее — «Соглашение») регламентирует отношения между администрацией сайта "Я едок" (далее — «Администрация»), и любым физическим лицом (далее — «Пользователь»), использующим сервисы и материалы, размещенные на сайте <?php echo e(config('app.url')); ?> (далее — «Сайт»).</p>
                        <p>1.2. Используя Сайт, Вы подтверждаете, что ознакомились с условиями настоящего Соглашения и принимаете их.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>2. Публикация контента</h5>
                        <p>2.1. Публикуя рецепты, фотографии, комментарии и другие материалы на Сайте, Пользователь гарантирует, что:</p>
                        <ul>
                            <li>Материалы не нарушают авторские права третьих лиц;</li>
                            <li>Пользователь является автором публикуемого контента или имеет разрешение автора на его публикацию;</li>
                            <li>Публикуемые материалы не содержат незаконных, оскорбительных или вредоносных элементов;</li>
                            <li>Пользователь получил все необходимые согласия от лиц, изображенных на фотографиях;</li>
                            <li>Все ингредиенты и методы приготовления, указанные в рецептах, безопасны для использования при соблюдении стандартных мер предосторожности.</li>
                        </ul>
                        <p>2.2. Публикуя материалы на Сайте, Пользователь предоставляет Администрации неисключительную, безвозмездную, бессрочную лицензию на использование, воспроизведение, изменение, адаптацию, публикацию и распространение этих материалов в рамках функционирования Сайта.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>3. Ответственность пользователей</h5>
                        <p>3.1. Пользователь несет полную ответственность за содержание публикуемых материалов и их соответствие законодательству РФ.</p>
                        <p>3.2. Администрация имеет право удалить любой материал без предварительного уведомления, если он:</p>
                        <ul>
                            <li>Нарушает авторские права;</li>
                            <li>Содержит незаконную информацию;</li>
                            <li>Является оскорбительным, недостоверным или вводящим в заблуждение;</li>
                            <li>Нарушает настоящее Соглашение.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h5>4. Права администрации</h5>
                        <p>4.1. Администрация имеет право:</p>
                        <ul>
                            <li>Изменять и дополнять настоящее Соглашение без предварительного уведомления Пользователей;</li>
                            <li>Модерировать, редактировать или удалять материалы Пользователей;</li>
                            <li>Ограничивать или блокировать доступ Пользователя к Сайту в случае нарушения настоящего Соглашения;</li>
                            <li>Использовать материалы, размещенные на Сайте, для продвижения Сайта в социальных сетях и других каналах.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h5>5. Отказ от гарантий</h5>
                        <p>5.1. Администрация не гарантирует, что:</p>
                        <ul>
                            <li>Сайт будет соответствовать требованиям и ожиданиям Пользователя;</li>
                            <li>Сервисы будут предоставляться непрерывно, быстро, надежно и без ошибок;</li>
                            <li>Результаты, которые могут быть получены с использованием Сайта, будут точными и надежными;</li>
                            <li>Все рецепты и советы, опубликованные на Сайте, будут безопасными и вкусными при их приготовлении.</li>
                        </ul>
                        <p>5.2. Пользователь понимает и соглашается, что использует материалы и сервисы Сайта на свой страх и риск.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>6. Заключительные положения</h5>
                        <p>6.1. Настоящее Соглашение вступает в силу с момента начала использования Пользователем Сайта.</p>
                        <p>6.2. Администрация оставляет за собой право по своему усмотрению изменять или дополнять настоящее Соглашение в любое время. Такие изменения вступают в силу с момента их публикации на Сайте.</p>
                        <p>6.3. Вопросы, не урегулированные настоящим Соглашением, регулируются действующим законодательством Российской Федерации.</p>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-0">Последнее обновление: <?php echo e(date('d.m.Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\legal\terms.blade.php ENDPATH**/ ?>