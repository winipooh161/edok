

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Результаты сбора ссылок</h3>
                        <div>
                            <a href="<?php echo e(route('admin.parser.batch')); ?>" class="btn btn-outline-primary me-2">
                                <i class="fas fa-sync-alt"></i> К пакетному парсингу
                            </a>
                            <a href="<?php echo e(route('admin.parser.collectLinksForm')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Назад к форме сбора
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Сбор ссылок выполнен</h5>
                                <p class="mb-0">Найдено ссылок на рецепты: <strong><?php echo e($totalLinks); ?></strong></p>
                                <?php if(session()->has('unique_links')): ?>
                                    <p class="mb-0">Из них уникальных (отсутствуют в базе): <strong><?php echo e(count(session('unique_links', []))); ?></strong></p>
                                <?php endif; ?>
                                <?php if(isset($totalImages)): ?>
                                <p class="mb-0">Найдено изображений: <strong><?php echo e($totalImages); ?></strong></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <button id="btnCopyAllLinks" class="btn btn-success">
                                    <i class="fas fa-copy"></i> Копировать все ссылки
                                </button>
                                <?php if(isset($collectedImages) && count($collectedImages) > 0): ?>
                                <button id="btnCopyAllImages" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-images"></i> Копировать ссылки изображений
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Информация о сборе:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>URL категории:</th>
                                    <td><a href="<?php echo e($categoryUrl); ?>" target="_blank"><?php echo e($categoryUrl); ?></a></td>
                                </tr>
                                <tr>
                                    <th>Обработано страниц:</th>
                                    <td><?php echo e(count($processedUrls)); ?></td>
                                </tr>
                                <tr>
                                    <th>Действия с результатами:</th>
                                    <td>
                                        <div class="btn-group">
                                            <button id="btnSendToBatch" class="btn btn-primary">
                                                <i class="fas fa-share"></i> Отправить ссылки в пакетный парсинг
                                            </button>
                                            <button id="btnExportTxt" class="btn btn-outline-dark">
                                                <i class="fas fa-file-export"></i> Экспорт в TXT
                                            </button>
                                            <button id="btnSortLinks" class="btn btn-outline-secondary">
                                                <i class="fas fa-sort-alpha-down"></i> Сортировать
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Собранные ссылки</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Статистика:</h6>
                                <ul>
                                    <li>Всего найдено ссылок: <strong><?php echo e(count($collectedLinks) + count(session('duplicate_links', []))); ?></strong></li>
                                    <li>Новых ссылок для обработки: <strong><?php echo e(count($collectedLinks) - count(array_intersect($collectedLinks, session('duplicate_links', [])))); ?></strong></li>
                                    <?php if(count(session('duplicate_links', [])) > 0): ?>
                                        <li>Пропущено ссылок (уже есть в базе): <strong><?php echo e(count(session('duplicate_links', []))); ?></strong></li>
                                    <?php endif; ?>
                                    <?php if(session()->has('unique_links')): ?>
                                        <li>Из них гарантированно уникальных (отсутствуют в базе): <strong><?php echo e(count(session('unique_links', []))); ?></strong></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            
                            <?php if(count(session('duplicate_links', [])) > 0): ?>
                                <div class="mb-4">
                                    <button class="btn btn-outline-secondary btn-sm mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#duplicateLinksCollapse">
                                        Показать пропущенные ссылки
                                    </button>
                                    <div class="collapse" id="duplicateLinksCollapse">
                                        <div class="card card-body bg-light">
                                            <h6>Список пропущенных URL (уже есть в базе):</h6>
                                            <div style="max-height: 200px; overflow-y: auto;">
                                                <ol class="mb-0">
                                                    <?php $__currentLoopData = session('duplicate_links', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><a href="<?php echo e($url); ?>" target="_blank"><?php echo e($url); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <h5>Собранные ссылки на рецепты:</h5>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text" id="linkSearch" class="form-control" placeholder="Фильтр ссылок...">
                                    <button class="btn btn-outline-secondary" type="button" id="btnClearSearch">
                                        <i class="fas fa-times"></i> Очистить
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="linksTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>URL рецепта</th>
                                            <th width="10%">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $collectedLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <a href="<?php echo e($link); ?>" target="_blank" class="link-item">
                                                    <?php echo e($link); ?>

                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary btn-copy-link" data-url="<?php echo e($link); ?>">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <a href="<?php echo e(route('admin.parser.index', ['url' => $link])); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($collectedImages) && count($collectedImages) > 0): ?>
                    <h5 class="mt-5">Собранные изображения:</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="imageSearch" class="form-control" placeholder="Поиск изображений...">
                                <button class="btn btn-outline-secondary" type="button" id="btnClearImageSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group">
                                <button id="btnToggleImages" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i> Показать/скрыть превью
                                </button>
                                <button id="btnCopyAllImg" class="btn btn-outline-secondary">
                                    <i class="fas fa-copy"></i> Копировать все URL
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="imagesTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Превью</th>
                                    <th>URL изображения</th>
                                    <th width="10%">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $collectedImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td class="image-preview-cell">
                                        <img src="<?php echo e($image); ?>" alt="Превью" class="img-thumbnail image-preview" style="max-height: 60px; display: none;">
                                        <span class="badge bg-secondary image-placeholder">Превью</span>
                                    </td>
                                    <td class="image-url"><?php echo e($image); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary btn-copy-image" data-url="<?php echo e($image); ?>">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <a href="<?php echo e($image); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($failedUrls)): ?>
                    <h5 class="mt-4">Ошибки при обработке:</h5>
                    <div class="table-responsive">
                        <table class="table table-danger">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>URL</th>
                                    <th>Причина ошибки</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $failedUrls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $failedUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($failedUrl['url']); ?></td>
                                    <td><?php echo e($failedUrl['error']); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('admin.parser.collectLinksForm')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Вернуться к форме сбора
                        </a>
                        <a href="<?php echo e(route('admin.parser.batch')); ?>" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Перейти к пакетному парсингу
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Копирование всех ссылок рецептов
        const btnCopyAllLinks = document.getElementById('btnCopyAllLinks');
        if (btnCopyAllLinks) {
            btnCopyAllLinks.addEventListener('click', function() {
                const links = Array.from(document.querySelectorAll('.link-item')).map(a => a.href);
                copyToClipboard(links.join('\n'));
                showToast('Все ссылки на рецепты скопированы в буфер обмена');
            });
        }
        
        // Копирование одной ссылки
        const btnCopyLinks = document.querySelectorAll('.btn-copy-link');
        btnCopyLinks.forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                copyToClipboard(url);
                showToast('Ссылка скопирована в буфер обмена');
            });
        });
        
        // Копирование всех изображений
        const btnCopyAllImages = document.getElementById('btnCopyAllImages');
        if (btnCopyAllImages) {
            btnCopyAllImages.addEventListener('click', function() {
                const images = Array.from(document.querySelectorAll('.image-url')).map(td => td.textContent);
                copyToClipboard(images.join('\n'));
                showToast('Все ссылки на изображения скопированы в буфер обмена');
            });
        }
        
        // Копирование одного изображения
        const btnCopyImages = document.querySelectorAll('.btn-copy-image');
        btnCopyImages.forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                copyToClipboard(url);
                showToast('Ссылка на изображение скопирована в буфер обмена');
            });
        });
        
        // Отправка ссылок в пакетный парсинг
        const btnSendToBatch = document.getElementById('btnSendToBatch');
        if (btnSendToBatch) {
            btnSendToBatch.addEventListener('click', function() {
                // Получаем уникальные ссылки из сессии, если они есть
                <?php if(session()->has('unique_links')): ?>
                    const uniqueLinks = <?php echo json_encode(session('unique_links')); ?>;
                    if (uniqueLinks && uniqueLinks.length > 0) {
                        localStorage.setItem('batchParseLinks', uniqueLinks.join('\n'));
                        showToast('Отправлено ' + uniqueLinks.length + ' уникальных ссылок в пакетный парсинг');
                    } else {
                        const links = Array.from(document.querySelectorAll('.link-item')).map(a => a.href);
                        localStorage.setItem('batchParseLinks', links.join('\n'));
                        showToast('Отправлено ' + links.length + ' ссылок в пакетный парсинг');
                    }
                <?php else: ?>
                    const links = Array.from(document.querySelectorAll('.link-item')).map(a => a.href);
                    localStorage.setItem('batchParseLinks', links.join('\n'));
                    showToast('Отправлено ' + links.length + ' ссылок в пакетный парсинг');
                <?php endif; ?>
                
                window.location.href = '<?php echo e(route("admin.parser.batch")); ?>';
            });
        }
        
        // Экспорт в TXT
        const btnExportTxt = document.getElementById('btnExportTxt');
        if (btnExportTxt) {
            btnExportTxt.addEventListener('click', function() {
                const links = Array.from(document.querySelectorAll('.link-item')).map(a => a.href);
                const blob = new Blob([links.join('\n')], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'recipe_links.txt';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            });
        }
        
        // Сортировка ссылок
        const btnSortLinks = document.getElementById('btnSortLinks');
        if (btnSortLinks) {
            btnSortLinks.addEventListener('click', function() {
                const table = document.getElementById('linksTable');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                rows.sort((a, b) => {
                    const textA = a.querySelector('.link-item').textContent.trim();
                    const textB = b.querySelector('.link-item').textContent.trim();
                    return textA.localeCompare(textB);
                });
                
                // Перенумеровать строки
                rows.forEach((row, index) => {
                    row.querySelector('td:first-child').textContent = index + 1;
                });
                
                // Очистить содержимое tbody и добавить отсортированные строки
                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }
                
                rows.forEach(row => tbody.appendChild(row));
                
                showToast('Ссылки отсортированы по алфавиту');
            });
        }
        
        // Фильтрация ссылок
        const linkSearch = document.getElementById('linkSearch');
        if (linkSearch) {
            linkSearch.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#linksTable tbody tr');
                
                rows.forEach(row => {
                    const linkText = row.querySelector('.link-item').textContent.toLowerCase();
                    if (linkText.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
        // Очистка поиска ссылок
        const btnClearSearch = document.getElementById('btnClearSearch');
        if (btnClearSearch) {
            btnClearSearch.addEventListener('click', function() {
                linkSearch.value = '';
                const rows = document.querySelectorAll('#linksTable tbody tr');
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Переключение видимости превью изображений
        const btnToggleImages = document.getElementById('btnToggleImages');
        if (btnToggleImages) {
            btnToggleImages.addEventListener('click', function() {
                const previews = document.querySelectorAll('.image-preview');
                const placeholders = document.querySelectorAll('.image-placeholder');
                
                previews.forEach(preview => {
                    preview.style.display = preview.style.display === 'none' ? 'block' : 'none';
                });
                
                placeholders.forEach(placeholder => {
                    placeholder.style.display = placeholder.style.display === 'none' ? 'inline-block' : 'none';
                });
            });
        }
        
        // Функция для копирования текста в буфер обмена
        function copyToClipboard(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }
        
        // Функция отображения уведомления
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = '9999'; 
            toast.innerHTML = `
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto"><i class="fas fa-check"></i> Успешно</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/admin/parser/collected_links.blade.php ENDPATH**/ ?>