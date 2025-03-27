

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Поиск рецептов</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('search')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="query" class="form-label">Поисковый запрос</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="query" name="query" value="<?php echo e(request('query')); ?>" placeholder="Введите название рецепта или ингредиент...">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="category" class="form-label">Категория</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Все категории</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Найти
                        </button>
                    </div>
                </div>
                
                <div class="collapse mt-3" id="advancedSearch">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="cooking_time" class="form-label">Время приготовления</label>
                            <select class="form-select" id="cooking_time" name="cooking_time">
                                <option value="">Любое время</option>
                                <option value="15" <?php echo e(request('cooking_time') == '15' ? 'selected' : ''); ?>>До 15 минут</option>
                                <option value="30" <?php echo e(request('cooking_time') == '30' ? 'selected' : ''); ?>>До 30 минут</option>
                                <option value="60" <?php echo e(request('cooking_time') == '60' ? 'selected' : ''); ?>>До 1 часа</option>
                                <option value="120" <?php echo e(request('cooking_time') == '120' ? 'selected' : ''); ?>>До 2 часов</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="calories" class="form-label">Калорийность</label>
                            <select class="form-select" id="calories" name="calories">
                                <option value="">Любая калорийность</option>
                                <option value="300" <?php echo e(request('calories') == '300' ? 'selected' : ''); ?>>До 300 ккал</option>
                                <option value="500" <?php echo e(request('calories') == '500' ? 'selected' : ''); ?>>До 500 ккал</option>
                                <option value="800" <?php echo e(request('calories') == '800' ? 'selected' : ''); ?>>До 800 ккал</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Сортировка</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Сначала новые</option>
                                <option value="cooking_time_asc" <?php echo e(request('sort') == 'cooking_time_asc' ? 'selected' : ''); ?>>По времени (по возрастанию)</option>
                                <option value="cooking_time_desc" <?php echo e(request('sort') == 'cooking_time_desc' ? 'selected' : ''); ?>>По времени (по убыванию)</option>
                                <option value="calories_asc" <?php echo e(request('sort') == 'calories_asc' ? 'selected' : ''); ?>>По калориям (по возрастанию)</option>
                                <option value="calories_desc" <?php echo e(request('sort') == 'calories_desc' ? 'selected' : ''); ?>>По калориям (по убыванию)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="<?php echo e(route('search')); ?>" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-undo me-1"></i> Сбросить фильтры
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-2">
                    <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                        <span class="when-closed"><i class="fas fa-chevron-down me-1"></i> Расширенный поиск</span>
                        <span class="when-opened"><i class="fas fa-chevron-up me-1"></i> Скрыть расширенный поиск</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if(request()->has('query') || request()->has('category')): ?>
        <h2 class="mb-3">Результаты поиска</h2>
        
        <?php if($recipes->total() > 0): ?>
            <p class="text-muted mb-4">Найдено <?php echo e($recipes->total()); ?> <?php echo e(trans_choice('рецепт|рецепта|рецептов', $recipes->total())); ?></p>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col">
                        <div class="card h-100 recipe-card">
                            <img src="<?php echo e($recipe->getImageUrl()); ?>" class="card-img-top" alt="<?php echo e($recipe->title); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($recipe->title); ?></h5>
                                <p class="card-text"><?php echo e(Str::limit($recipe->description, 100)); ?></p>
                                
                                <div class="recipe-meta">
                                    <?php if($recipe->cooking_time): ?>
                                        <span class="badge bg-info"><i class="far fa-clock"></i> <?php echo e($recipe->cooking_time); ?> мин</span>
                                    <?php endif; ?>
                                    
                                    <?php if($recipe->calories): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-fire"></i> <?php echo e($recipe->calories); ?> ккал</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($recipes->withQueryString()->links()); ?>

            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> По вашему запросу ничего не найдено. Попробуйте изменить параметры поиска.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="card bg-light">
            <div class="card-body text-center py-5">
                <h3 class="mb-3">Введите поисковый запрос</h3>
                <p class="mb-0 text-muted">Для поиска рецептов введите название или ингредиент и нажмите кнопку "Найти"</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var advancedSearch = document.getElementById('advancedSearch');
        var whenClosed = document.querySelector('.when-closed');
        var whenOpened = document.querySelector('.when-opened');
        
        // Скрывать/показывать соответствующий текст в кнопке
        advancedSearch.addEventListener('hidden.bs.collapse', function () {
            whenClosed.style.display = 'inline';
            whenOpened.style.display = 'none';
        });
        
        advancedSearch.addEventListener('shown.bs.collapse', function () {
            whenClosed.style.display = 'none';
            whenOpened.style.display = 'inline';
        });
        
        // Инициализация
        if (advancedSearch.classList.contains('show')) {
            whenClosed.style.display = 'none';
            whenOpened.style.display = 'inline';
        } else {
            whenClosed.style.display = 'inline';
            whenOpened.style.display = 'none';
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\search\index.blade.php ENDPATH**/ ?>