

<?php $__env->startSection('meta_tags'); ?>
    <title><?php echo e($seo->getRecipeTitle($recipe)); ?></title>
    <meta name="description" content="<?php echo e($seo->getRecipeDescription($recipe)); ?>">
    <meta property="og:title" content="<?php echo e($recipe->title); ?>">
    <meta property="og:description" content="<?php echo e($seo->getRecipeDescription($recipe)); ?>">
    <meta property="og:image" content="<?php echo e(asset($recipe->image_url)); ?>">
    <meta property="og:url" content="<?php echo e(route('recipes.show', $recipe->slug)); ?>">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('schema_org'); ?>
    <script type="application/ld+json">
        <?php echo $seo->getRecipeSchema($recipe); ?>

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <!-- Хлебные крошки -->
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Главная</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('recipes.index')); ?>">Рецепты</a></li>
                    <?php if($recipe->categories->isNotEmpty()): ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('categories.show', $recipe->categories->first()->slug)); ?>"><?php echo e($recipe->categories->first()->name); ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($recipe->title); ?></li>
                </ol>
            </nav>
        </div>

        <!-- Основной контент рецепта -->
        <div class="col-lg-8">
            <!-- Заголовок и мета-информация -->
            <div class="mb-4">
                <h1 class="display-5 fw-bold"><?php echo e($recipe->title); ?></h1>
                <div class="d-flex flex-wrap align-items-center text-muted mb-3">
                    <span class="me-3">
                        <i class="far fa-calendar-alt me-1"></i> <?php echo e($recipe->created_at->format('d.m.Y')); ?>

                    </span>
                    <span class="me-3">
                        <i class="far fa-eye me-1"></i> <?php echo e($recipe->views); ?> просмотров
                    </span>
                    <?php if($recipe->cooking_time): ?>
                        <span class="me-3">
                            <i class="far fa-clock me-1"></i> <?php echo e($recipe->cooking_time); ?> мин
                        </span>
                    <?php endif; ?>
                    <?php if($recipe->servings): ?>
                        <span class="me-3">
                            <i class="fas fa-utensils me-1"></i> <?php echo e($recipe->servings); ?> <?php echo e(trans_choice('порция|порции|порций', $recipe->servings)); ?>

                        </span>
                    <?php endif; ?>
                    <?php if($recipe->user): ?>
                        <span>
                            <i class="far fa-user me-1"></i> Автор: 
                            <a href="<?php echo e(route('profile.show', $recipe->user->id)); ?>" class="text-decoration-none">
                                <?php echo e($recipe->user->name); ?>

                            </a>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Категории -->
                <?php if($recipe->categories->isNotEmpty()): ?>
                    <div class="mb-3">
                        <?php $__currentLoopData = $recipe->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="badge bg-secondary text-decoration-none me-1">
                                <?php echo e($category->name); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Слайдер с изображениями рецепта -->
            <div class="recipe-slider-container mb-4">
                <div class="swiper recipe-swiper">
                    <div class="swiper-wrapper">
                        <!-- Основное изображение -->
                        <div class="swiper-slide">
                            <img src="<?php echo e($recipe->getImageUrl()); ?>" class="img-fluid rounded shadow w-100" alt="<?php echo e($recipe->title); ?>">
                        </div>
                        
                        <!-- Дополнительные изображения из additional_data -->
                        <?php
                            $additionalImages = [];
                            if (!empty($recipe->additional_data)) {
                                $data = is_array($recipe->additional_data) ? 
                                        $recipe->additional_data : 
                                        json_decode($recipe->additional_data, true);
                                
                                // Проверяем различные форматы хранения изображений
                                if (isset($data['slider_images']) && is_array($data['slider_images'])) {
                                    $additionalImages = array_merge($additionalImages, $data['slider_images']);
                                }
                                
                                if (isset($data['saved_images']) && is_array($data['saved_images'])) {
                                    foreach($data['saved_images'] as $img) {
                                        if (isset($img['saved_path'])) {
                                            $additionalImages[] = $img['saved_path'];
                                        }
                                    }
                                }
                                
                                if (isset($data['step_images']) && is_array($data['step_images'])) {
                                    foreach($data['step_images'] as $stepNum => $img) {
                                        $additionalImages[] = $img;
                                    }
                                }
                            }
                            // Удаляем дубликаты
                            $additionalImages = array_unique($additionalImages);
                        ?>
                        
                        <?php $__currentLoopData = $additionalImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide">
                                <img src="<?php echo e(asset($image)); ?>" class="img-fluid rounded shadow w-100" alt="<?php echo e($recipe->title); ?> - изображение <?php echo e($loop->iteration + 1); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Добавляем навигацию слайдера -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            
            <!-- CSS для слайдера -->
            <style>
                .recipe-slider-container {
                    position: relative;
                }
                .recipe-swiper {
                    width: 100%;
                    border-radius: 0.25rem;
                    overflow: hidden;
                }
                .swiper-slide {
                    height: auto;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .swiper-slide img {
                    max-height: 500px;
                    object-fit: cover;
                }
                .swiper-button-next, .swiper-button-prev {
                    color: #fff;
                    background: rgba(0,0,0,0.3);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    --swiper-navigation-size: 20px;
                }
                .swiper-pagination-bullet-active {
                    background: #007bff;
                }
            </style>
            
            <!-- JavaScript для инициализации слайдера -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Проверяем, загружен ли Swiper
                    if (typeof Swiper !== 'undefined') {
                        initRecipeSwiper();
                    } else {
                        // Загружаем Swiper динамически, если он не доступен
                        loadSwiper();
                    }
                    
                    function loadSwiper() {
                        // Загружаем CSS
                        const swiperCss = document.createElement('link');
                        swiperCss.rel = 'stylesheet';
                        swiperCss.href = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css';
                        document.head.appendChild(swiperCss);
                        
                        // Загружаем JavaScript
                        const swiperJs = document.createElement('script');
                        swiperJs.src = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js';
                        swiperJs.onload = initRecipeSwiper;
                        document.body.appendChild(swiperJs);
                    }
                    
                    function initRecipeSwiper() {
                        new Swiper('.recipe-swiper', {
                            slidesPerView: 1,
                            spaceBetween: 10,
                            loop: <?php echo e(count($additionalImages) > 0 ? 'true' : 'false'); ?>,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                            },
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            effect: 'fade',
                            fadeEffect: {
                                crossFade: true
                            },
                            lazy: true,
                        });
                    }
                });
            </script>
            
            <!-- Описание -->
            <?php if($recipe->description): ?>
                <div class="recipe-description mb-4">
                    <h2 class="h5 border-bottom pb-2 mb-3">Описание</h2>
                    <p class="lead"><?php echo e($recipe->description); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Питательность -->
            <?php if($recipe->calories || $recipe->proteins || $recipe->fats || $recipe->carbs): ?>
                <div class="nutrition-facts mb-4">
                    <h2 class="h5 border-bottom pb-2 mb-3">Пищевая ценность (на 100г)</h2>
                    <div class="row text-center">
                        <?php if($recipe->calories): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item p-2 h-100 rounded border">
                                    <div class="fw-bold text-primary mb-1">Калории</div>
                                    <div class="h5 mb-0"><?php echo e($recipe->calories); ?> ккал</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($recipe->proteins): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item p-2 h-100 rounded border">
                                    <div class="fw-bold text-primary mb-1">Белки</div>
                                    <div class="h5 mb-0"><?php echo e($recipe->proteins); ?> г</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($recipe->fats): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item p-2 h-100 rounded border">
                                    <div class="fw-bold text-primary mb-1">Жиры</div>
                                    <div class="h5 mb-0"><?php echo e($recipe->fats); ?> г</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($recipe->carbs): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item p-2 h-100 rounded border">
                                    <div class="fw-bold text-primary mb-1">Углеводы</div>
                                    <div class="h5 mb-0"><?php echo e($recipe->carbs); ?> г</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Навигация по разделам -->
            <div class="recipe-navigation mb-4">
                <div class="list-group list-group-horizontal overflow-auto">
                    <a href="#ingredients" class="list-group-item list-group-item-action text-center">
                        <i class="fas fa-shopping-basket me-2"></i> Ингредиенты
                    </a>
                    <a href="#instructions" class="list-group-item list-group-item-action text-center">
                        <i class="fas fa-list-ol me-2"></i> Инструкции
                    </a>
                    <a href="#notes" class="list-group-item list-group-item-action text-center">
                        <i class="fas fa-sticky-note me-2"></i> Заметки
                    </a>
                    <a href="#related" class="list-group-item list-group-item-action text-center">
                        <i class="fas fa-utensils me-2"></i> Похожие рецепты
                    </a>
                </div>
            </div>
            
            <!-- Ингредиенты -->
            <div class="recipe-ingredients mb-4" id="ingredients">
                <h2 class="h4 border-bottom pb-2 mb-3">
                    <i class="fas fa-shopping-basket me-2 text-primary"></i> Ингредиенты
                </h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-sm btn-outline-primary" id="toggle-checkboxes">
                                <i class="fas fa-tasks me-1"></i> Отметить купленные
                            </button>
                        </div>
                        <div class="ingredients-list">
                            <?php if($recipe->structured_ingredients): ?>
                                <?php $__currentLoopData = $recipe->structured_ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(isset($group['name']) && isset($group['items'])): ?>
                                        <!-- Это группа ингредиентов -->
                                        <h5 class="ingredient-group-title mb-2"><?php echo e($group['name']); ?></h5>
                                        <ul class="list-group list-group-flush mb-3">
                                            <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="list-group-item ingredient-item d-flex align-items-center border-0 py-2">
                                                    <div class="ingredient-checkbox me-2 d-none">
                                                        <input type="checkbox" class="form-check-input" id="ingredient-<?php echo e($loop->parent->index); ?>-<?php echo e($loop->index); ?>">
                                                    </div>
                                                    <label class="ingredient-label mb-0" for="ingredient-<?php echo e($loop->parent->index); ?>-<?php echo e($loop->index); ?>">
                                                        <?php if(isset($ingredient['quantity']) && $ingredient['quantity']): ?>
                                                            <span class="fw-bold"><?php echo e($ingredient['quantity']); ?> <?php echo e($ingredient['unit'] ?? ''); ?></span>
                                                        <?php endif; ?>
                                                        <?php echo e($ingredient['name']); ?>

                                                        <?php if(isset($ingredient['optional']) && $ingredient['optional']): ?>
                                                            <span class="text-muted">(по желанию)</span>
                                                        <?php endif; ?>
                                                        <?php if(isset($ingredient['notes']) && $ingredient['notes']): ?>
                                                            <span class="text-muted"> - <?php echo e($ingredient['notes']); ?></span>
                                                        <?php endif; ?>
                                                    </label>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php else: ?>
                                        <!-- Это одиночный ингредиент -->
                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item ingredient-item d-flex align-items-center border-0 py-2">
                                                <div class="ingredient-checkbox me-2 d-none">
                                                    <input type="checkbox" class="form-check-input" id="ingredient-single-<?php echo e($loop->index); ?>">
                                                </div>
                                                <label class="ingredient-label mb-0" for="ingredient-single-<?php echo e($loop->index); ?>">
                                                    <?php if(isset($group['quantity']) && $group['quantity']): ?>
                                                        <span class="fw-bold"><?php echo e($group['quantity']); ?> <?php echo e($group['unit'] ?? ''); ?></span>
                                                    <?php endif; ?>
                                                    <?php echo e($group['name'] ?? 'Ингредиент'); ?>

                                                    <?php if(isset($group['optional']) && $group['optional']): ?>
                                                        <span class="text-muted">(по желанию)</span>
                                                    <?php endif; ?>
                                                    <?php if(isset($group['notes']) && $group['notes']): ?>
                                                        <span class="text-muted"> - <?php echo e($group['notes']); ?></span>
                                                    <?php endif; ?>
                                                </label>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php $__currentLoopData = $recipe->getIngredientsArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty(trim($ingredient))): ?>
                                            <li class="list-group-item ingredient-item d-flex align-items-center border-0 py-2">
                                                <div class="ingredient-checkbox me-2 d-none">
                                                    <input type="checkbox" class="form-check-input" id="ingredient-<?php echo e($index); ?>">
                                                </div>
                                                <label class="ingredient-label mb-0" for="ingredient-<?php echo e($index); ?>">
                                                    <?php echo e($ingredient); ?>

                                                </label>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Инструкции -->
            <div class="recipe-instructions mb-4" id="instructions">
                <h2 class="h4 border-bottom pb-2 mb-3">
                    <i class="fas fa-list-ol me-2 text-primary"></i> Пошаговые инструкции
                </h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <ol class="instructions-list">
                            <?php
                                $instructionsArray = preg_split('/\r\n|\r|\n/', $recipe->instructions);
                            ?>
                            
                            <?php $__currentLoopData = $instructionsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instruction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty(trim($instruction))): ?>
                                    <li class="mb-3">
                                        <p><?php echo e($instruction); ?></p>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    </div>
                </div>
            </div>
            
            <!-- Заметки и советы -->
            <div class="recipe-notes mb-4" id="notes">
                <h2 class="h4 border-bottom pb-2 mb-3">
                    <i class="fas fa-sticky-note me-2 text-primary"></i> Заметки и советы
                </h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <?php if(isset($recipe->additional_data['notes']) && !empty($recipe->additional_data['notes'])): ?>
                            <div class="notes-content">
                                <?php echo e($recipe->additional_data['notes']); ?>

                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i> Для этого рецепта пока нет дополнительных заметок или советов.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Кнопки действий -->
            <div class="recipe-actions mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary">
                        <i class="far fa-bookmark me-1"></i> Сохранить
                    </button>
                    <button class="btn btn-outline-success">
                        <i class="fas fa-print me-1"></i> Распечатать
                    </button>
                    <button class="btn btn-outline-info">
                        <i class="fas fa-share-alt me-1"></i> Поделиться
                    </button>
                    <button class="btn btn-outline-warning" id="scaleRecipe">
                        <i class="fas fa-balance-scale me-1"></i> Изменить порции
                    </button>
                </div>
            </div>
            
            <!-- Похожие рецепты -->
            <div class="related-recipes mb-4" id="related">
                <h2 class="h4 border-bottom pb-2 mb-3">
                    <i class="fas fa-utensils me-2 text-primary"></i> Похожие рецепты
                </h2>
                
                <?php if($relatedRecipes->count() > 0): ?>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php $__currentLoopData = $relatedRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedRecipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="<?php echo e($relatedRecipe->getImageUrl()); ?>" class="card-img-top related-recipe-img" alt="<?php echo e($relatedRecipe->title); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo e($relatedRecipe->title); ?></h5>
                                        <?php if($relatedRecipe->cooking_time): ?>
                                            <p class="card-text text-muted">
                                                <i class="far fa-clock me-1"></i> <?php echo e($relatedRecipe->cooking_time); ?> мин
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-transparent border-0">
                                        <a href="<?php echo e(route('recipes.show', $relatedRecipe->slug)); ?>" class="btn btn-primary w-100">
                                            <i class="fas fa-eye me-1"></i> Посмотреть
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Похожие рецепты не найдены.
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Комментарии -->
            <div class="recipe-comments">
                <h2 class="h4 border-bottom pb-2 mb-3">
                    <i class="fas fa-comments me-2 text-primary"></i> Комментарии
                </h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Показ существующих комментариев -->
                        <?php if($recipe->comments && $recipe->comments->count() > 0): ?>
                            <div class="comments-list mb-4">
                                <?php $__currentLoopData = $recipe->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="comment mb-3 pb-3 border-bottom">
                                        <div class="d-flex align-items-center mb-2">
                                            <strong class="me-2"><?php echo e($comment->user->name); ?></strong>
                                            <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                                        </div>
                                        <p class="mb-0"><?php echo e($comment->content); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i> Будьте первым, кто оставит комментарий к этому рецепту!
                            </div>
                        <?php endif; ?>
                        
                        <!-- Форма добавления комментария -->
                        <?php if(auth()->guard()->check()): ?>
                            <form action="<?php echo e(route('comments.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="recipe_id" value="<?php echo e($recipe->id); ?>">
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Ваш комментарий</label>
                                    <textarea class="form-control <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="comment" name="comment" rows="3" required></textarea>
                                    <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="far fa-paper-plane me-1"></i> Отправить
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-lock me-2"></i> Для добавления комментариев необходимо <a href="<?php echo e(route('login')); ?>">войти</a> или <a href="<?php echo e(route('register')); ?>">зарегистрироваться</a>.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Боковая панель -->
        <div class="col-lg-4">
            <!-- Блок приготовления и порций -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 h5">Информация о рецепте</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="far fa-clock me-2 text-primary"></i> Время приготовления:</span>
                            <span class="fw-bold"><?php echo e($recipe->cooking_time ?? 'Не указано'); ?> мин</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-utensils me-2 text-primary"></i> Порций:</span>
                            <span class="fw-bold"><?php echo e($recipe->servings ?? 'Не указано'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-fire me-2 text-primary"></i> Калорийность:</span>
                            <span class="fw-bold"><?php echo e($recipe->calories ?? 'Не указано'); ?> ккал</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-eye me-2 text-primary"></i> Просмотров:</span>
                            <span class="fw-bold"><?php echo e($recipe->views); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Блок энергетической ценности - если есть данные о питательности -->
            <?php if($recipe->calories || $recipe->proteins || $recipe->fats || $recipe->carbs): ?>
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0 h5">Энергетическая ценность (на порцию)</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if($recipe->calories): ?>
                        <div class="col-6 mb-3">
                            <div class="nutrition-item p-3 h-100 rounded border text-center">
                                <div class="fw-bold text-success">
                                    <i class="fas fa-fire me-1"></i> Калории
                                </div>
                                <div class="h4 mb-0 mt-2"><?php echo e($recipe->calories); ?> ккал</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($recipe->proteins): ?>
                        <div class="col-6 mb-3">
                            <div class="nutrition-item p-3 h-100 rounded border text-center">
                                <div class="fw-bold text-success">
                                    <i class="fas fa-drumstick-bite me-1"></i> Белки
                                </div>
                                <div class="h4 mb-0 mt-2"><?php echo e($recipe->proteins); ?> г</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($recipe->fats): ?>
                        <div class="col-6 mb-3">
                            <div class="nutrition-item p-3 h-100 rounded border text-center">
                                <div class="fw-bold text-success">
                                    <i class="fas fa-cheese me-1"></i> Жиры
                                </div>
                                <div class="h4 mb-0 mt-2"><?php echo e($recipe->fats); ?> г</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($recipe->carbs): ?>
                        <div class="col-6 mb-3">
                            <div class="nutrition-item p-3 h-100 rounded border text-center">
                                <div class="fw-bold text-success">
                                    <i class="fas fa-bread-slice me-1"></i> Углеводы
                                </div>
                                <div class="h4 mb-0 mt-2"><?php echo e($recipe->carbs); ?> г</div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($recipe->servings): ?>
                    <div class="alert alert-info mb-0 mt-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Приведены значения в расчете на порцию. Рецепт рассчитан на <?php echo e($recipe->servings); ?> <?php echo e(trans_choice('порция|порции|порций', $recipe->servings)); ?>.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Автор рецепта -->
            <?php if($recipe->user): ?>
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h3 class="mb-0 h5">Автор рецепта</h3>
                    </div>
                    <div class="card-body text-center">
                        <?php if($recipe->user->avatar): ?>
                            <img src="<?php echo e(asset('storage/'.$recipe->user->avatar)); ?>" alt="<?php echo e($recipe->user->name); ?>" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center text-white bg-primary mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                <?php echo e(strtoupper(substr($recipe->user->name, 0, 1))); ?>

                            </div>
                        <?php endif; ?>
                        <h5 class="mb-1"><?php echo e($recipe->user->name); ?></h5>
                        <?php if($recipe->user->isAdmin()): ?>
                            <span class="badge bg-danger mb-2">Администратор</span>
                        <?php endif; ?>
                        <p class="text-muted small mb-3"><?php echo e($recipe->user->bio ?: 'Автор рецептов'); ?></p>
                        <a href="<?php echo e(route('profile.show', $recipe->user->id)); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-1"></i> Профиль автора
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Поиск по сайту -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="mb-0 h5">Поиск рецептов</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('search')); ?>" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Название или ингредиент..." name="query">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Категории -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="mb-0 h5">Категории</h3>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 g-2">
                        <?php $__currentLoopData = $recipe->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col">
                                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="btn btn-outline-secondary btn-sm w-100">
                                    <?php echo e($category->name); ?>

                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Теги (заглушка) -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="mb-0 h5">Теги</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="badge bg-light text-dark text-decoration-none p-2">рецепт</a>
                        <a href="#" class="badge bg-light text-dark text-decoration-none p-2">домашняя кухня</a>
                        <a href="#" class="badge bg-light text-dark text-decoration-none p-2">просто</a>
                        <a href="#" class="badge bg-light text-dark text-decoration-none p-2">вкусно</a>
                        <a href="#" class="badge bg-light text-dark text-decoration-none p-2">быстро</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Стили для страницы рецепта */
    .recipe-image-container {
        max-height: 500px;
        overflow: hidden;
    }
    
    .recipe-image-container img {
        width: 100%;
        object-fit: cover;
    }
    
    .related-recipe-img {
        height: 180px;
        object-fit: cover;
    }
    
    .ingredient-item {
        transition: background-color 0.3s;
    }
    
    .ingredient-item:hover {
        background-color: #f8f9fa;
    }
    
    .ingredient-label {
        cursor: pointer;
        flex: 1;
    }
    
    .ingredient-label.checked {
        text-decoration: line-through;
        color: #6c757d;
    }
    
    .nutrition-item {
        background-color: #f8f9fa;
        transition: transform 0.3s;
    }
    
    .nutrition-item:hover {
        transform: translateY(-5px);
    }
    
    .list-group-horizontal {
        flex-wrap: nowrap;
    }
    
    .list-group-horizontal .list-group-item {
        white-space: nowrap;
    }
    
    .ingredient-group-title {
        background-color: #f8f9fa;
        padding: 5px 10px;
        border-radius: 4px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Переключение отображения чекбоксов для ингредиентов
        const toggleCheckboxesBtn = document.getElementById('toggle-checkboxes');
        const ingredientCheckboxes = document.querySelectorAll('.ingredient-checkbox');
        const ingredientLabels = document.querySelectorAll('.ingredient-label');
        
        if (toggleCheckboxesBtn) {
            toggleCheckboxesBtn.addEventListener('click', function() {
                ingredientCheckboxes.forEach(checkbox => {
                    checkbox.classList.toggle('d-none');
                });
                
                // Меняем текст кнопки
                if (ingredientCheckboxes[0].classList.contains('d-none')) {
                    toggleCheckboxesBtn.innerHTML = '<i class="fas fa-tasks me-1"></i> Отметить купленные';
                } else {
                    toggleCheckboxesBtn.innerHTML = '<i class="fas fa-times me-1"></i> Скрыть чекбоксы';
                }
            });
        }
        
        // Обработчик для чекбоксов ингредиентов
        document.querySelectorAll('.ingredient-item input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('.ingredient-item').querySelector('.ingredient-label');
                if (this.checked) {
                    label.classList.add('checked');
                } else {
                    label.classList.remove('checked');
                }
            });
        });
        
        // Изменение порций (заглушка)
        const scaleRecipeBtn = document.getElementById('scaleRecipe');
        if (scaleRecipeBtn) {
            scaleRecipeBtn.addEventListener('click', function() {
                const servings = prompt('Введите количество порций:', '<?php echo e($recipe->servings ?? 4); ?>');
                if (servings && !isNaN(servings)) {
                    alert('Функция изменения количества порций находится в разработке. Выбрано порций: ' + servings);
                }
            });
        }
        
        // Кнопка прокрутки вверх
        const backToTopBtn = document.getElementById('back-to-top');
        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.style.display = 'flex';
                    backToTopBtn.style.alignItems = 'center';
                    backToTopBtn.style.justifyContent = 'center';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            });

            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Плавная прокрутка к разделам по якорям
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/recipes/show.blade.php ENDPATH**/ ?>