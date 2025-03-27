

<?php $__env->startSection('meta_tags'); ?>
    <title><?php echo e($seo->getCategoryTitle($category)); ?></title>
    <meta name="description" content="<?php echo e($seo->getCategoryDescription($category)); ?>">
    <meta property="og:title" content="<?php echo e($category->name); ?> - рецепты">
    <meta property="og:description" content="<?php echo e($seo->getCategoryDescription($category)); ?>">
    <meta property="og:url" content="<?php echo e(route('categories.show', $category->slug)); ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <link rel="canonical" href="<?php echo e(route('categories.show', $category->slug)); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('schema_org'); ?>
    <script type="application/ld+json">
        <?php echo $schemaData; ?>

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <!-- Основное содержимое -->
        <div class="col-lg-8">
            <!-- Хедер категории -->
            <div class="category-header mb-4 p-4 rounded shadow-sm position-relative">
                <div class="category-bg-overlay <?php echo e($category->getColorClass()); ?>"></div>
                <div class="position-relative">
                    <h1 class="display-5 fw-bold text-white mb-3"><?php echo e($category->name); ?></h1>
                    <?php if($category->description): ?>
                        <p class="lead text-white mb-0"><?php echo e($category->description); ?></p>
                    <?php else: ?>
                        <p class="lead text-white mb-0">
                            Рецепты из категории "<?php echo e($category->name); ?>" - <?php echo e($recipes->total()); ?> <?php echo e(trans_choice('рецепт|рецепта|рецептов', $recipes->total())); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Форма поиска внутри категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?php echo e(route('categories.show', $category->slug)); ?>" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Поиск внутри категории</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" value="<?php echo e($search ?? ''); ?>" placeholder="Название или ингредиент...">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="cooking_time" class="form-label">Время приготовления</label>
                            <select name="cooking_time" id="cooking_time" class="form-select" onchange="this.form.submit()">
                                <option value="">Любое время</option>
                                <option value="30" <?php echo e(($cookingTime ?? '') == '30' ? 'selected' : ''); ?>>До 30 минут</option>
                                <option value="60" <?php echo e(($cookingTime ?? '') == '60' ? 'selected' : ''); ?>>До 1 часа</option>
                                <option value="120" <?php echo e(($cookingTime ?? '') == '120' ? 'selected' : ''); ?>>До 2 часов</option>
                                <option value="121" <?php echo e(($cookingTime ?? '') == '121' ? 'selected' : ''); ?>>Более 2 часов</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Сортировать по</label>
                            <select name="sort" id="sort_by" class="form-select" onchange="this.form.submit()">
                                <option value="latest" <?php echo e(($sort ?? '') == 'latest' ? 'selected' : ''); ?>>Новые</option>
                                <option value="popular" <?php echo e(($sort ?? '') == 'popular' ? 'selected' : ''); ?>>Популярные</option>
                                <option value="cooking_time_asc" <?php echo e(($sort ?? '') == 'cooking_time_asc' ? 'selected' : ''); ?>>Время (по возрастанию)</option>
                                <option value="cooking_time_desc" <?php echo e(($sort ?? '') == 'cooking_time_desc' ? 'selected' : ''); ?>>Время (по убыванию)</option>
                                <?php if(!empty($search)): ?>
                                <option value="relevance" <?php echo e(($sort ?? '') == 'relevance' ? 'selected' : ''); ?>>Релевантности</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-1">
                            <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Информация о поиске -->
            <?php if(!empty($search)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-search me-2"></i> Результаты поиска по запросу: <strong>"<?php echo e($search); ?>"</strong>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark">Найдено: <?php echo e($recipes->total()); ?> <?php echo e(trans_choice('рецепт|рецепта|рецептов', $recipes->total())); ?></span>
                        <?php if(isset($searchTerms)): ?>
                            <span class="badge bg-light text-dark">Искали: <?php echo e(implode(', ', $searchTerms)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Список рецептов -->
            <div class="recipes-list mb-4">
                <?php if($recipes->count() > 0): ?>
                    <div class="row g-4">
                        <?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="card h-100 recipe-card border-0 shadow-sm">
                                    <div class="position-relative">
                                        <img src="<?php echo e($recipe->getImageUrl()); ?>" class="card-img-top recipe-img" alt="<?php echo e($recipe->title); ?>">
                                        <?php if($recipe->cooking_time): ?>
                                            <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                                <i class="far fa-clock me-1"></i> <?php echo e($recipe->cooking_time); ?> мин
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($recipe->relevance_percent) && $recipe->relevance_percent > 0): ?>
                                            <div class="position-absolute top-0 start-0 badge bg-<?php echo e($recipe->relevance_percent > 75 ? 'success' : ($recipe->relevance_percent > 50 ? 'info' : 'secondary')); ?> m-2">
                                                <?php echo e($recipe->relevance_percent); ?>% совпадение
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">
                                            <?php if(!empty($search)): ?>
                                                <?php echo preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->title); ?>

                                            <?php else: ?>
                                                <?php echo e($recipe->title); ?>

                                            <?php endif; ?>
                                        </h5>
                                        
                                        <?php if($recipe->description): ?>
                                            <p class="card-text mb-3 text-muted">
                                                <?php if(!empty($search)): ?>
                                                    <?php echo Str::limit(preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->description), 100); ?>

                                                <?php else: ?>
                                                    <?php echo e(Str::limit($recipe->description, 100)); ?>

                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-light text-dark">
                                                    <i class="far fa-eye me-1"></i> <?php echo e($recipe->views); ?>

                                                </span>
                                                
                                                <?php if($recipe->user): ?>
                                                    <span class="badge bg-light text-dark ms-1">
                                                        <i class="far fa-user me-1"></i> <?php echo e($recipe->user->name); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0 pb-3">
                                        <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-primary w-100">
                                            <i class="fas fa-book-open me-1"></i> Смотреть рецепт
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="mt-4 d-flex justify-content-center">
                        <?php echo e($recipes->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> В данной категории пока нет рецептов, соответствующих вашему запросу.
                        <?php if(!empty($search)): ?>
                            <div class="mt-3">
                                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-undo me-1"></i> Сбросить фильтры поиска
                                </a>
                                <a href="<?php echo e(route('search', ['query' => $search])); ?>" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-search me-1"></i> Искать везде
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Боковая панель -->
        <div class="col-lg-4">
            <!-- Популярные рецепты категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i> Популярные рецепты</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $popularRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex align-items-center p-3">
                                <img src="<?php echo e($recipe->getImageUrl()); ?>" class="rounded me-3" alt="<?php echo e($recipe->title); ?>" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="text-decoration-none text-dark">
                                            <?php echo e($recipe->title); ?>

                                        </a>
                                    </h6>
                                    <div class="d-flex">
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="far fa-eye me-1"></i> <?php echo e($recipe->views); ?>

                                        </span>
                                        <?php if($recipe->cooking_time): ?>
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i> <?php echo e($recipe->cooking_time); ?> мин
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            
            <!-- Другие категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Другие категории</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php $__currentLoopData = $otherCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $otherCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6">
                                <a href="<?php echo e(route('categories.show', $otherCategory->slug)); ?>" class="btn btn-outline-secondary btn-sm d-block mb-2">
                                    <?php echo e($otherCategory->name); ?> <span class="badge bg-secondary ms-1"><?php echo e($otherCategory->recipes_count); ?></span>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Быстрые фильтры -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Быстрые фильтры</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-list me-2"></i> Все рецепты</span>
                            <span class="badge bg-primary rounded-pill"><?php echo e($category->recipes->where('is_published', true)->count()); ?></span>
                        </a>
                        <a href="<?php echo e(route('categories.show', ['slug' => $category->slug, 'cooking_time' => 30])); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bolt me-2"></i> Быстрые (до 30 мин)</span>
                            <span class="badge bg-success rounded-pill"><?php echo e($category->recipes->where('is_published', true)->where('cooking_time', '<=', 30)->count()); ?></span>
                        </a>
                        <a href="<?php echo e(route('categories.show', ['slug' => $category->slug, 'sort' => 'popular'])); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-fire me-2"></i> Самые популярные
                        </a>
                        <a href="<?php echo e(route('categories.show', ['slug' => $category->slug, 'sort' => 'latest'])); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-clock me-2"></i> Новые рецепты
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Стили для страницы категории */
    .category-header {
        position: relative;
        overflow: hidden;
    }
    
    .category-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.9;
        z-index: 0;
    }
    
    .recipe-img {
        height: 200px;
        object-fit: cover;
    }
    
    .recipe-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/categories/show.blade.php ENDPATH**/ ?>