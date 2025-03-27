

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Сбор ссылок с категории</h3>
                        <div>
                            <a href="<?php echo e(route('admin.parser.batch')); ?>" class="btn btn-outline-primary me-2">
                                <i class="fas fa-list-ol"></i> К пакетному парсингу
                            </a>
                            <a href="<?php echo e(route('admin.parser.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Назад к парсеру
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.parser.collectLinks')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label for="category_url">URL страницы категории</label>
                            <div class="input-group">
                                <input 
                                    type="url" 
                                    class="form-control <?php $__errorArgs = ['category_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="category_url" 
                                    name="category_url" 
                                    placeholder="https://example.com/category/desserts"
                                    value="<?php echo e(old('category_url')); ?>"
                                    required
                                >
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-bolt"></i> Быстрый выбор
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button type="button" class="dropdown-item" data-preset="edaruMain">Eda.ru - Главная</button></li>
                                    <li><button type="button" class="dropdown-item" data-preset="edaruBreakfast">Eda.ru - Завтраки</button></li>
                                    <li><button type="button" class="dropdown-item" data-preset="edaruDinner">Eda.ru - Ужины</button></li>
                                    <li><button type="button" class="dropdown-item" data-preset="edaruDessert">Eda.ru - Десерты</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button type="button" class="dropdown-item" data-preset="povarenok">Поваренок.ру</button></li>
                                    <li><button type="button" class="dropdown-item" data-preset="gotovim">Готовим.ру</button></li>
                                </ul>
                            </div>
                            <?php $__errorArgs = ['category_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Укажите URL страницы с категорией рецептов.</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="link_selector">Селектор для ссылок на рецепты</label>
                            <input 
                                type="text" 
                                class="form-control <?php $__errorArgs = ['link_selector'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="link_selector" 
                                name="link_selector" 
                                placeholder=".recipe-card a, .recipe-list .recipe-link"
                                value="<?php echo e(old('link_selector', '')); ?>"
                            >
                            <?php $__errorArgs = ['link_selector'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Укажите CSS-селектор для элементов, содержащих ссылки на рецепты.<br>
                                <strong>Примечание:</strong> Для eda.ru селектор не требуется, система автоматически найдет все ссылки с "/recepty/".
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pagination_selector">Селектор для пагинации (необязательно)</label>
                                    <input 
                                        type="text" 
                                        class="form-control <?php $__errorArgs = ['pagination_selector'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="pagination_selector" 
                                        name="pagination_selector" 
                                        placeholder=".pagination a, .pages-nav .page-link"
                                        value="<?php echo e(old('pagination_selector', '.emotion-1vzb8pn a')); ?>"
                                    >
                                    <?php $__errorArgs = ['pagination_selector'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">
                                        Укажите CSS-селектор для ссылок пагинации.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_pages">Максимальное количество страниц для обработки</label>
                                    <input 
                                        type="number" 
                                        class="form-control <?php $__errorArgs = ['max_pages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="max_pages" 
                                        name="max_pages" 
                                        min="1" 
                                        max="100"
                                        value="<?php echo e(old('max_pages', 5)); ?>"
                                    >
                                    <?php $__errorArgs = ['max_pages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="desired_links">Желаемое количество ссылок</label>
                                    <input 
                                        type="number" 
                                        class="form-control <?php $__errorArgs = ['desired_links'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="desired_links" 
                                        name="desired_links" 
                                        min="1" 
                                        max="500"
                                        value="<?php echo e(old('desired_links', 100)); ?>"
                                    >
                                    <?php $__errorArgs = ['desired_links'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">
                                        Укажите желаемое количество ссылок для сбора (до 500). Система будет собирать ссылки пока не найдет указанное количество уникальных ссылок, 
                                        отсутствующих в вашей базе.
                                        <span class="text-success"><i class="fas fa-info-circle"></i> Улучшенный режим: продолжает поиск пока не найдет все уникальные URL.</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="scroll_mode">Режим прокрутки</label>
                                    <select 
                                        class="form-select <?php $__errorArgs = ['scroll_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="scroll_mode" 
                                        name="scroll_mode"
                                    >
                                        <option value="disabled" <?php echo e(old('scroll_mode') == 'disabled' ? 'selected' : ''); ?>>Без прокрутки (стандартно)</option>
                                        <option value="enabled" <?php echo e(old('scroll_mode', 'enabled') == 'enabled' ? 'selected' : ''); ?>>С прокруткой (расширенный поиск)</option>
                                    </select>
                                    <?php $__errorArgs = ['scroll_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">
                                        Включите для сайтов с "бесконечной прокруткой" (например, eda.ru). <br>
                                        <span class="text-success"><i class="fas fa-info-circle"></i> Улучшенный режим: имитирует прокрутку страниц, сортировок и подкатегорий для сбора большего количества уникальных ссылок.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="collect_images" name="collect_images" value="1" <?php echo e(old('collect_images') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="collect_images">
                                Собирать изображения рецептов
                            </label>
                            <div class="form-text">
                                Включите для сбора изображений рецептов со страниц (например, ссылки вида https://eda.ru/images/...)
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Собрать ссылки
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Инструкция по использованию:</h5>
                            <ol>
                                <li>Укажите URL страницы с категорией рецептов (например, "Десерты" или "Супы")</li>
                                <li>Укажите CSS-селектор или XPath для элементов, содержащих ссылки на рецепты</li>
                                <li>Для динамических сайтов включите режим прокрутки и укажите желаемое количество ссылок</li>
                                <li>Установите значение "Желаемое количество ссылок" на 500, если хотите собрать максимальное количество уникальных ссылок</li>
                                <li>Сбор прекратится только когда найдет указанное количество ссылок или исчерпает все доступные страницы</li>
                                <li>При необходимости включите сбор изображений</li>
                                <li>Нажмите кнопку "Собрать ссылки" и дождитесь результатов</li>
                            </ol>
                        </div>
                        
                        <div class="mt-3">
                            <h5>Примеры селекторов для популярных сайтов:</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Сайт</th>
                                            <th>Селектор для рецептов</th>
                                            <th>Селектор для пагинации</th>
                                            <th>Режим прокрутки</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>eda.ru</td>
                                            <td><code>.emotion-1j5xcrd a, .emotion-18hxz5k, .emotion-13pp0tv, a[href*='/recepty/'][href*='-']</code></td>
                                            <td><code>.emotion-1vzb8pn a</code></td>
                                            <td>С прокруткой</td>
                                        </tr>
                                        <tr>
                                            <td>povarenok.ru</td>
                                            <td><code>.article-list .img-link</code></td>
                                            <td><code>.pagination_list a</code></td>
                                            <td>Без прокрутки</td>
                                        </tr>
                                        <tr>
                                            <td>gotovim.ru</td>
                                            <td><code>.recipe_l a.rec_title</code></td>
                                            <td><code>.pager a</code></td>
                                            <td>Без прокрутки</td>
                                        </tr>
                                        <tr>
                                            <td>allrecipes.com</td>
                                            <td><code>.card__titleLink</code></td>
                                            <td><code>.pagination__page</code></td>
                                            <td>С прокруткой</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обработка быстрого выбора сайта
        const presetButtons = document.querySelectorAll('[data-preset]');
        const linkSelectorInput = document.getElementById('link_selector');
        const scrollModeSelect = document.getElementById('scroll_mode');
        const collectImagesCheckbox = document.getElementById('collect_images');
        const categoryUrlInput = document.getElementById('category_url');
        
        // Функция для проверки, является ли URL ссылкой на eda.ru
        function isEdaRu(url) {
            return url.indexOf('eda.ru') !== -1;
        }
        
        // Обновление состояния поля селектора в зависимости от URL
        function updateSelectorState() {
            const categoryUrl = categoryUrlInput.value;
            if (isEdaRu(categoryUrl)) {
                linkSelectorInput.value = 'auto';  // Устанавливаем значение вместо пустой строки
                linkSelectorInput.style.backgroundColor = '#e9ecef';  // Визуально показываем, что поле неактивно
                document.querySelector('label[for="link_selector"]').innerHTML = 'Селектор для ссылок на рецепты (для eda.ru определяется автоматически)';
                
                // Устанавливаем режим прокрутки по умолчанию для eda.ru
                if (scrollModeSelect) {
                    scrollModeSelect.value = 'enabled';
                }
                
                // Включаем сбор изображений для eda.ru
                if (collectImagesCheckbox) {
                    collectImagesCheckbox.checked = true;
                }
            } else {
                linkSelectorInput.style.backgroundColor = '';
                linkSelectorInput.value = linkSelectorInput.value === 'auto' ? '' : linkSelectorInput.value;
                document.querySelector('label[for="link_selector"]').innerHTML = 'Селектор для ссылок на рецепты';
            }
        }
        
        // Слушаем изменения в поле URL
        categoryUrlInput.addEventListener('input', updateSelectorState);
        
        // Слушаем изменения режима прокрутки
        if (scrollModeSelect) {
            scrollModeSelect.addEventListener('change', function() {
                // Можно добавить любую дополнительную логику при смене режима прокрутки
                console.log('Режим прокрутки изменен на: ' + this.value);
            });
        }
        
        presetButtons.forEach(button => {
            button.addEventListener('click', function() {
                const preset = this.getAttribute('data-preset');
                
                // Настройки для разных пресетов
                const presetSettings = {
                    edaruMain: {
                        url: 'https://eda.ru',
                        linkSelector: '',
                        paginationSelector: '.emotion-1vzb8pn a',
                        scrollMode: 'enabled',
                        collectImages: true
                    },
                    edaruBreakfast: {
                        url: 'https://eda.ru/recepty/zavtraki',
                        linkSelector: '',
                        paginationSelector: '.emotion-1vzb8pn a',
                        scrollMode: 'enabled',
                        collectImages: true
                    },
                    edaruDinner: {
                        url: 'https://eda.ru/recepty/osnovnye-blyuda',
                        linkSelector: '',
                        paginationSelector: '.emotion-1vzb8pn a',
                        scrollMode: 'enabled',
                        collectImages: true
                    },
                    edaruDessert: {
                        url: 'https://eda.ru/recepty/vypechka-deserty',
                        linkSelector: '',
                        paginationSelector: '.emotion-1vzb8pn a',
                        scrollMode: 'enabled',
                        collectImages: true
                    },
                    povarenok: {
                        url: 'https://www.povarenok.ru/recipes/',
                        linkSelector: '.article-list .img-link',
                        paginationSelector: '.pagination_list a',
                        scrollMode: 'disabled',
                        collectImages: true
                    },
                    gotovim: {
                        url: 'https://gotovim.ru/recepts/',
                        linkSelector: '.recipe_l a.rec_title',
                        paginationSelector: '.pager a',
                        scrollMode: 'disabled',
                        collectImages: false
                    }
                };
                
                // Заполняем поля формы
                const settings = presetSettings[preset];
                if (settings) {
                    categoryUrlInput.value = settings.url;
                    linkSelectorInput.value = settings.linkSelector;
                    document.getElementById('pagination_selector').value = settings.paginationSelector;
                    
                    // Устанавливаем режим прокрутки (явно задаем значение)
                    if (scrollModeSelect) {
                        scrollModeSelect.value = settings.scrollMode;
                    }
                    
                    // Устанавливаем сбор изображений
                    if (collectImagesCheckbox) {
                        collectImagesCheckbox.checked = settings.collectImages;
                    }
                    
                    // Обновляем состояние селектора
                    updateSelectorState();
                }
            });
        });
        
        // Инициализируем состояние при загрузке страницы
        updateSelectorState();
        
        // Дополнительно: проверка и восстановление состояния формы из localStorage или сессии
        const savedScrollMode = localStorage.getItem('parser_scroll_mode');
        if (savedScrollMode && scrollModeSelect) {
            scrollModeSelect.value = savedScrollMode;
        }
        
        // Сохраняем выбранный режим прокрутки при изменении
        if (scrollModeSelect) {
            scrollModeSelect.addEventListener('change', function() {
                localStorage.setItem('parser_scroll_mode', this.value);
            });
        }
        
        // Сохраняем значения формы перед отправкой
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (scrollModeSelect) {
                    localStorage.setItem('parser_scroll_mode', scrollModeSelect.value);
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/admin/parser/collect_links.blade.php ENDPATH**/ ?>