

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="animate-on-scroll"><i class="fas fa-spider text-primary me-2"></i> Парсер рецептов</h1>
            <p class="animate-on-scroll">Импортируйте рецепты с других сайтов, указав URL страницы с рецептом.</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('admin.parser.collectLinksForm')); ?>" class="btn btn-outline-info me-2">
                <i class="fas fa-search"></i> Сбор ссылок с категории
            </a>
            <a href="<?php echo e(route('admin.parser.batch')); ?>" class="btn btn-outline-success me-2">
                <i class="fas fa-list-ol"></i> Пакетный парсинг
            </a>
            <a href="<?php echo e(route('admin.recipes.index')); ?>" class="btn btn-secondary animate-on-scroll">
                <i class="fas fa-arrow-left me-1"></i> Назад к рецептам
            </a>
        </div>
    </div>
    
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show animate-on-scroll" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <div class="card animate-on-scroll">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-link me-2"></i> Ввод URL для импорта
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.parser.parse')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label for="url" class="form-label">URL страницы с рецептом</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                        <input type="url" class="form-control <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="url" name="url" 
                               placeholder="https://example.com/recipe/..." required value="<?php echo e(old('url')); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cloud-download-alt me-1"></i> Импортировать
                        </button>
                    </div>
                    <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">Вставьте URL страницы с рецептом, который хотите импортировать.</div>
                </div>
                
                <div class="mb-3">
                    <label for="categories" class="form-label">Выберите категории (опционально)</label>
                    <select multiple class="form-select" id="categories" name="categories[]">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Удерживайте Ctrl (Cmd на Mac) для выбора нескольких категорий</div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card mt-4 animate-on-scroll">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle me-2"></i> Информация по использованию
        </div>
        <div class="card-body">
            <h5>Как это работает?</h5>
            <ol>
                <li>Вставьте URL страницы с рецептом в поле ввода выше.</li>
                <li>Выберите категории, в которые хотите добавить рецепт (опционально).</li>
                <li>Нажмите кнопку "Импортировать".</li>
                <li>Система попытается извлечь данные рецепта с указанной страницы.</li>
                <li>Вы сможете просмотреть и отредактировать извлеченные данные перед сохранением.</li>
            </ol>
            
            <h5 class="mt-4">Поддерживаемые форматы данных</h5>
            <p>Парсер может обрабатывать следующие типы данных:</p>
            <ul>
                <li><strong>Структурированные данные JSON-LD</strong> (наиболее точный метод)</li>
                <li><strong>Микроданные Schema.org</strong> (хорошая точность)</li>
                <li><strong>Обычные HTML-страницы</strong> (точность зависит от структуры страницы)</li>
            </ul>
            
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> <strong>Обратите внимание:</strong> 
                Парсер может извлекать не все данные корректно, особенно при нестандартной структуре страницы. 
                Всегда проверяйте и корректируйте результаты перед сохранением.
            </div>
            
            <h5 class="mt-4">Популярные кулинарные сайты с хорошей поддержкой</h5>
            <div class="row">
                <div class="col-md-6">
                    <ul>
                        <li>Едим Дома</li>
                        <li>Поваренок</li>
                        <li>Allrecipes</li>
                        <li>Food Network</li>
                        <li>Bon Appétit</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>Eda.ru</li>
                        <li>Готовим.ру</li>
                        <li>Вкусно и просто</li>
                        <li>BBC Good Food</li>
                        <li>New York Times Cooking</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\admin\parser\index.blade.php ENDPATH**/ ?>