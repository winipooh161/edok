

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Категории рецептов</h1>
            <p class="lead text-muted">Выберите категорию, чтобы найти вдохновляющие идеи для приготовления.</p>
        </div>
    </div>
    
    <!-- Популярные категории -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i> Популярные категории</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php $__currentLoopData = $popularCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3 col-sm-6">
                                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="text-decoration-none">
                                    <div class="card h-100 popular-category-card border-0 shadow-sm">
                                        <div class="category-image-container rounded-top <?php echo e($category->getColorClass()); ?>">
                                            <img src="<?php echo e($category->getImageUrl()); ?>" alt="<?php echo e($category->name); ?>" class="card-img-top category-img">
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title mb-2"><?php echo e($category->name); ?></h5>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-book me-1"></i> <?php echo e($category->recipes_count); ?> <?php echo e(trans_choice('рецепт|рецепта|рецептов', $category->recipes_count)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Алфавитный указатель -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-th-list me-2"></i> Все категории</h4>
                </div>
                <div class="card-body">
                    <div class="alphabetical-index mb-4">
                        <p>Быстрый переход:</p>
                        <div class="d-flex flex-wrap alphabet-links">
                            <?php $__currentLoopData = $categoriesByLetter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="#letter-<?php echo e($letter); ?>" class="letter-link me-2 mb-2 btn btn-sm btn-outline-primary"><?php echo e($letter); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    
                    <div class="categories-list mt-4">
                        <?php $__currentLoopData = $categoriesByLetter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="letter-section mb-4" id="letter-<?php echo e($letter); ?>">
                                <h3 class="letter-heading border-bottom pb-2 mb-3"><?php echo e($letter); ?></h3>
                                <div class="row g-3">
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 col-sm-6">
                                            <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="category-link">
                                                <div class="d-flex align-items-center p-2 rounded hover-shadow">
                                                    <div class="category-icon me-3 rounded-circle <?php echo e($category->getColorClass()); ?> text-white">
                                                        <i class="fas fa-utensils"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0"><?php echo e($category->name); ?></h5>
                                                        <small class="text-muted"><?php echo e($category->recipes_count); ?> <?php echo e(trans_choice('рецепт|рецепта|рецептов', $category->recipes_count)); ?></small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Случайные рецепты для вдохновения -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Вдохновение дня</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <?php $__currentLoopData = $featuredRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="card h-100 featured-recipe-card border-0 shadow-sm">
                                    <div class="position-relative">
                                        <img src="<?php echo e($recipe->getImageUrl()); ?>" class="card-img-top featured-recipe-img" alt="<?php echo e($recipe->title); ?>">
                                        <?php if($recipe->cooking_time): ?>
                                            <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                                                <i class="far fa-clock me-1"></i> <?php echo e($recipe->cooking_time); ?> мин
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo e($recipe->title); ?></h5>
                                        <?php if(!$recipe->categories->isEmpty()): ?>
                                            <div class="mb-2">
                                                <?php $__currentLoopData = $recipe->categories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="badge bg-light text-dark text-decoration-none me-1">
                                                        <?php echo e($category->name); ?>

                                                    </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-eye me-1"></i> Посмотреть рецепт
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Стили для страницы категорий */
    .category-img {
        height: 150px;
        object-fit: cover;
        opacity: 0.8;
    }
    
    .category-image-container {
        height: 150px;
        overflow: hidden;
        position: relative;
    }
    
    .popular-category-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .popular-category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .category-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
    }
    
    .letter-heading {
        color: #6c757d;
        font-weight: 500;
    }
    
    .category-link {
        color: inherit;
        text-decoration: none;
    }
    
    .hover-shadow {
        transition: background-color 0.3s, box-shadow 0.3s;
    }
    
    .hover-shadow:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .featured-recipe-img {
        height: 180px;
        object-fit: cover;
    }
    
    .featured-recipe-card {
        transition: transform 0.3s;
    }
    
    .featured-recipe-card:hover {
        transform: translateY(-5px);
    }
    
    .letter-link {
        text-decoration: none;
        min-width: 36px;
        text-align: center;
    }
    
    /* Плавная прокрутка до секций */
    html {
        scroll-behavior: smooth;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\categories\index.blade.php ENDPATH**/ ?>