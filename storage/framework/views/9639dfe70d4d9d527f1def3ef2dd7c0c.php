

<?php $__env->startSection('meta_tags'); ?>
    <title><?php echo e($seo->getRecipeTitle($recipe)); ?></title>
    <meta name="description" content="<?php echo e($seo->getRecipeDescription($recipe)); ?>">
    <meta name="keywords" content="<?php echo e($recipe->categories->pluck('name')->join(', ')); ?>, рецепт, кулинария">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo e(route('recipes.show', $recipe->slug)); ?>">
    <meta property="og:title" content="<?php echo e($recipe->title); ?>">
    <meta property="og:description" content="<?php echo e($seo->getRecipeDescription($recipe)); ?>">
    <meta property="og:image" content="<?php echo e($recipe->getImageUrl()); ?>">
    <meta property="article:published_time" content="<?php echo e($recipe->created_at->toIso8601String()); ?>">
    <meta property="article:modified_time" content="<?php echo e($recipe->updated_at->toIso8601String()); ?>">
    <meta property="article:author" content="<?php echo e($recipe->user ? $recipe->user->name : config('app.name')); ?>">
    <?php $__currentLoopData = $recipe->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <meta property="article:section" content="<?php echo e($category->name); ?>">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo e(route('recipes.show', $recipe->slug)); ?>">
    <meta name="twitter:title" content="<?php echo e($recipe->title); ?>">
    <meta name="twitter:description" content="<?php echo e($seo->getRecipeDescription($recipe)); ?>">
    <meta name="twitter:image" content="<?php echo e($recipe->getImageUrl()); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('schema_org'); ?>
    <script type="application/ld+json">
        <?php echo $seo->getRecipeSchema($recipe); ?>

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="recipe-title mb-3"><?php echo e($recipe->title); ?></h1>
            
            <!-- Быстрая навигация по рецепту -->
            <div class="quick-nav mb-4 d-flex justify-content-center">
                <div class="btn-group">
                    <a href="#ingredients" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-list-ul me-1"></i> Ингредиенты
                    </a>
                    <a href="#instructions" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-123 me-1"></i> Инструкции
                    </a>
                    <?php if($recipe->calories || $recipe->proteins || $recipe->fats || $recipe->carbs): ?>
                    <a href="#nutrition" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pie-chart me-1"></i> Пищевая ценность
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Слайдер с изображениями -->
            <?php
                $sliderImages = [];
                // Добавляем главное изображение
                if ($recipe->image_url) {
                    $sliderImages[] = $recipe->image_url;
                }
                // Добавляем дополнительные изображения для слайдера, если они есть
                if ($recipe->additional_data) {
                    $additionalData = json_decode($recipe->additional_data, true);
                    if (isset($additionalData['slider_images']) && is_array($additionalData['slider_images'])) {
                        $sliderImages = array_merge($sliderImages, $additionalData['slider_images']);
                    }
                }
            ?>

            <?php if(count($sliderImages) > 0): ?>
                <div class="swiper mySwiper mb-4 shadow">
                    <div class="swiper-wrapper">
                        <?php $__currentLoopData = $sliderImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide">
                                <img src="<?php echo e(asset($imageUrl)); ?>" class="d-block w-100 rounded" alt="<?php echo e($recipe->title); ?> - изображение <?php echo e($index + 1); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if(count($sliderImages) > 1): ?>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    <?php endif; ?>
                </div>
            <?php elseif($recipe->image_url): ?>
                <div class="mb-4">
                    <img src="<?php echo e(asset($recipe->image_url)); ?>" class="img-fluid rounded shadow" alt="<?php echo e($recipe->title); ?>">
                </div>
            <?php endif; ?>

            <div class="recipe-meta mb-4 p-3 bg-light rounded shadow-sm">
                <div class="row">
                    <?php if($recipe->cooking_time): ?>
                        <div class="col-4 text-center">
                            <div class="meta-icon"><i class="bi bi-clock"></i></div>
                            <div class="meta-value"><?php echo e($recipe->cooking_time); ?> мин</div>
                            <div class="meta-label">время</div>
                            <button class="btn btn-sm btn-outline-primary mt-2 start-timer-btn" data-cooking-time="<?php echo e($recipe->cooking_time); ?>">
                                <i class="bi bi-stopwatch"></i> Запустить таймер
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if($recipe->servings): ?>
                        <div class="col-4 text-center">
                            <div class="meta-icon"><i class="bi bi-people"></i></div>
                            <div class="meta-value"><span id="current-servings"><?php echo e($recipe->servings); ?></span></div>
                            <div class="meta-label">порций</div>
                        </div>
                    <?php endif; ?>
                    <?php if($recipe->calories): ?>
                        <div class="col-4 text-center">
                            <div class="meta-icon"><i class="bi bi-fire"></i></div>
                            <div class="meta-value"><?php echo e($recipe->calories); ?></div>
                            <div class="meta-label">ккал/100г</div>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Добавляем информацию об авторе -->
                <div class="recipe-author mb-3">
                    <span class="text-muted">
                        <i class="fas fa-user me-1"></i> 
                        Автор: <?php echo e($recipe->user ? $recipe->user->name : 'Не указан'); ?>

                    </span>
                </div>
            </div>

            <!-- Таймер приготовления (скрыт изначально) -->
            <div id="cooking-timer" class="recipe-timer mb-4 p-4 bg-primary text-white rounded shadow-sm d-none">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0"><i class="bi bi-stopwatch me-2"></i> Таймер приготовления</h5>
                    <button type="button" class="btn-close btn-close-white" id="close-timer" aria-label="Close"></button>
                </div>
                <div class="timer-display text-center my-3">
                    <div class="timer-value display-4 fw-bold" id="timer-countdown">00:00</div>
                    <div class="timer-status small" id="timer-status">Готово к запуску</div>
                </div>
                <div class="timer-controls d-flex justify-content-center">
                    <button class="btn btn-light me-2" id="start-pause-timer">
                        <i class="bi bi-play-fill"></i> Старт
                    </button>
                    <button class="btn btn-outline-light" id="reset-timer">
                        <i class="bi bi-arrow-counterclockwise"></i> Сброс
                    </button>
                </div>
            </div>

            <?php if($recipe->description): ?>
                <div class="recipe-description mb-4 p-4 bg-white rounded shadow-sm">
                    <h5 class="section-title">Описание</h5>
                    <p class="lead"><?php echo e($recipe->description); ?></p>
                </div>
            <?php endif; ?>

            <!-- Кнопки действий с рецептом -->
            <div class="recipe-actions mb-4 d-flex flex-wrap justify-content-center gap-2">
                <button class="btn btn-outline-primary add-to-favorites" data-recipe-id="<?php echo e($recipe->id); ?>">
                    <i class="bi bi-heart"></i> <span class="favorite-text">В избранное</span>
                </button>
                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#emailRecipeModal">
                    <i class="bi bi-envelope"></i> Отправить на email
                </button>
                <button class="btn btn-outline-success" onclick="window.print()">
                    <i class="bi bi-printer"></i> Распечатать
                </button>
                <button class="btn btn-outline-secondary" id="generation-shopping-list">
                    <i class="bi bi-cart"></i> Список покупок
                </button>
            </div>

            <!-- Калькулятор порций -->
            <?php if($recipe->servings): ?>
                <div class="servings-calculator mb-4 p-4 bg-white rounded shadow-sm">
                    <h5 class="section-title">Калькулятор порций</h5>
                    <p class="text-muted small mb-3">Укажите нужное количество порций, и мы автоматически пересчитаем количество ингредиентов</p>
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <button id="decrease-servings" class="btn btn-primary btn-sm me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-dash-lg"></i>
                        </button>
                        <div class="position-relative">
                            <input id="servings-input" type="number" min="1" max="100" value="<?php echo e($recipe->servings); ?>" class="form-control form-control-lg text-center border-primary" style="max-width: 100px;">
                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3">порц.</span>
                        </div>
                        <button id="increase-servings" class="btn btn-primary btn-sm ms-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <button id="reset-servings" class="btn btn-outline-secondary btn-sm ms-4 d-flex align-items-center">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Сбросить
                        </button>
                    </div>
                    <div class="text-center text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Рецепт рассчитан на <strong><?php echo e($recipe->servings); ?> <?php echo e(getServingsWord($recipe->servings)); ?></strong>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Ингредиенты -->
            <div id="ingredients" class="recipe-ingredients mb-4 p-4 bg-white rounded shadow-sm">
                <h5 class="section-title">Ингредиенты</h5>
                
                <?php
                    // Функция для проверки пустых значений с сохранением нуля
                    function isEmptyValue($value) {
                        return $value === null || $value === '' || $value === false;
                    }
                    
                    // Получаем данные ингредиентов из JSON
                    $ingredientsData = null;
                    $useLegacyFormat = true;
                    
                    if($recipe->additional_data) {
                        $additionalData = is_array($recipe->additional_data) ? 
                                          $recipe->additional_data : 
                                          json_decode($recipe->additional_data, true);
                                          
                        // Проверяем на новый формат (JSON 2.0)
                        if(isset($additionalData['ingredients_json'])) {
                            $ingredientsData = json_decode($additionalData['ingredients_json'], true);
                            $useLegacyFormat = false;
                        }
                        // Проверяем старый формат
                        else if(isset($additionalData['structured_ingredients'])) {
                            $ingredientsData = [
                                'version' => '1.0',
                                'ingredients' => $additionalData['structured_ingredients']
                            ];
                        }
                    }
                    
                    // Если данных нет, парсим на лету
                    if($ingredientsData === null && $recipe->ingredients) {
                        $parser = new \App\Services\IngredientParser();
                        $structuredData = $parser->parseToStructuredData($recipe->ingredients);
                        $ingredientsData = $structuredData;
                    }
                ?>
                
                <ul class="list-group list-group-flush ingredients-list" data-original-servings="<?php echo e($recipe->servings); ?>">
                    <?php if($ingredientsData && !$useLegacyFormat): ?>
                        
                        <?php $__currentLoopData = $ingredientsData['ingredients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($item['name']) && isset($item['items'])): ?>
                                
                                <li class="list-group-item ingredient-group-header">
                                    <h6 class="mb-2 fw-bold"><?php echo e($item['name']); ?></h6>
                                    <ul class="list-group list-group-flush ps-3">
                                        <?php $__currentLoopData = $item['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center ingredient-item py-3 border-dashed <?php if($ingredient['optional'] ?? false): ?> text-muted <?php endif; ?>" 
                                                data-name="<?php echo e($ingredient['name']); ?>" 
                                                data-quantity="<?php echo e($ingredient['quantity'] ?? ''); ?>" 
                                                data-unit="<?php echo e($ingredient['unit']); ?>">
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" class="ingredient-checkbox form-check-input me-2">
                                                    <span class="ingredient-name fw-medium">
                                                        <?php echo e($ingredient['name']); ?>

                                                        <?php if($ingredient['optional'] ?? false): ?>
                                                            <small class="text-muted">(по желанию)</small>
                                                        <?php endif; ?>
                                                        <?php if($ingredient['notes'] ?? false): ?>
                                                            <small class="text-muted d-block"><?php echo e($ingredient['notes']); ?></small>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <span class="ingredient-quantity badge bg-light text-dark p-2">
                                                    <?php if(!isEmptyValue($ingredient['quantity'] ?? null)): ?>
                                                        <span class="quantity-value" data-original="<?php echo e($ingredient['quantity']); ?>"><?php echo e($ingredient['quantity']); ?></span>
                                                        <span class="quantity-unit ms-1"><?php echo e($ingredient['unit']); ?></span>
                                                    <?php else: ?>
                                                        <span class="quantity-unit"><?php echo e($ingredient['unit']); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                
                                <li class="list-group-item d-flex justify-content-between align-items-center ingredient-item py-3 border-dashed <?php if($item['optional'] ?? false): ?> text-muted <?php endif; ?>" 
                                    data-name="<?php echo e($item['name']); ?>" 
                                    data-quantity="<?php echo e($item['quantity'] ?? ''); ?>" 
                                    data-unit="<?php echo e($item['unit']); ?>">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="ingredient-checkbox form-check-input me-2">
                                        <span class="ingredient-name fw-medium">
                                            <?php echo e($item['name']); ?>

                                            <?php if($item['optional'] ?? false): ?>
                                                <small class="text-muted">(по желанию)</small>
                                            <?php endif; ?>
                                            <?php if($item['notes'] ?? false): ?>
                                                <small class="text-muted d-block"><?php echo e($item['notes']); ?></small>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <span class="ingredient-quantity badge bg-light text-dark p-2">
                                        <?php if(!isEmptyValue($item['quantity'] ?? null)): ?>
                                            <span class="quantity-value" data-original="<?php echo e($item['quantity']); ?>"><?php echo e($item['quantity']); ?></span>
                                            <span class="quantity-unit ms-1"><?php echo e($item['unit']); ?></span>
                                        <?php else: ?>
                                            <span class="quantity-unit"><?php echo e($item['unit']); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php elseif($ingredientsData): ?>
                        
                        <?php $__currentLoopData = $ingredientsData['ingredients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center ingredient-item py-3 border-dashed" 
                                data-name="<?php echo e($ingredient['name']); ?>" 
                                data-quantity="<?php echo e($ingredient['quantity'] ?? ''); ?>" 
                                data-unit="<?php echo e($ingredient['unit']); ?>">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="ingredient-checkbox form-check-input me-2">
                                    <span class="ingredient-name fw-medium"><?php echo e($ingredient['name']); ?></span>
                                </div>
                                <span class="ingredient-quantity badge bg-light text-dark p-2">
                                    <?php if(!isEmptyValue($ingredient['quantity'] ?? null)): ?>
                                        <span class="quantity-value" data-original="<?php echo e($ingredient['quantity']); ?>"><?php echo e($ingredient['quantity']); ?></span>
                                        <span class="quantity-unit ms-1"><?php echo e($ingredient['unit']); ?></span>
                                    <?php else: ?>
                                        <span class="quantity-unit"><?php echo e($ingredient['unit']); ?></span>
                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        
                        <?php $__currentLoopData = explode("\n", $recipe->ingredients); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty(trim($line))): ?>
                                <li class="list-group-item py-2">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="ingredient-checkbox form-check-input me-2">
                                        <span><?php echo e(trim($line)); ?></span>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </ul>
                <div class="mt-3 text-center">
                    <button id="check-all-ingredients" class="btn btn-sm btn-outline-primary me-2">
                        <i class="bi bi-check-all"></i> Отметить все
                    </button>
                    <button id="uncheck-all-ingredients" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-lg"></i> Снять все отметки
                    </button>
                </div>
            </div>

            <!-- Инструкции -->
            <div id="instructions" class="recipe-instructions mb-4 p-4 bg-white rounded shadow-sm">
                <h5 class="section-title">Способ приготовления</h5>
                <div class="instructions">
                    <?php $__currentLoopData = explode("\n", $recipe->instructions); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="instruction-step mb-4">
                            <div class="step-number"><?php echo e($index + 1); ?></div>
                            <div class="step-content">
                                <p><?php echo e($step); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Пищевая ценность -->
            <?php if($recipe->calories || $recipe->proteins || $recipe->fats || $recipe->carbs): ?>
                <div id="nutrition" class="recipe-nutrition mb-4 p-4 bg-white rounded shadow-sm">
                    <h5 class="section-title">Пищевая ценность (на 100 г)</h5>
                    <div class="row">
                        <?php if($recipe->calories): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item">
                                    <div class="nutrition-icon">
                                        <i class="bi bi-fire"></i>
                                    </div>
                                    <div class="nutrition-value"><?php echo e($recipe->calories); ?></div>
                                    <div class="nutrition-label">ккал</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($recipe->proteins): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item">
                                    <div class="nutrition-icon">
                                        <i class="bi bi-egg-fried"></i>
                                    </div>
                                    <div class="nutrition-value"><?php echo e($recipe->proteins); ?></div>
                                    <div class="nutrition-label">белки, г</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($recipe->fats): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item">
                                    <div class="nutrition-icon">
                                        <i class="bi bi-droplet"></i>
                                    </div>
                                    <div class="nutrition-value"><?php echo e($recipe->fats); ?></div>
                                    <div class="nutrition-label">жиры, г</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($recipe->carbs): ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="nutrition-item">
                                    <div class="nutrition-icon">
                                        <i class="bi bi-pie-chart"></i>
                                    </div>
                                    <div class="nutrition-value"><?php echo e($recipe->carbs); ?></div>
                                    <div class="nutrition-label">углеводы, г</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($recipe->source_url): ?>
                <div class="text-muted small mb-4 source-link">
                    <i class="bi bi-link-45deg"></i> Источник: 
                    <a href="<?php echo e($recipe->source_url); ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo e(parse_url($recipe->source_url, PHP_URL_HOST)); ?>

                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Добавляем информацию об источнике после контента рецепта -->
            <?php if($recipe->source_url): ?>
            <div class="mt-4 p-3 bg-light rounded">
                <p class="mb-0 small">
                    <i class="fas fa-info-circle text-primary me-1"></i> 
                    Источник рецепта: <a href="<?php echo e($recipe->source_url); ?>" target="_blank" rel="nofollow"><?php echo e(parse_url($recipe->source_url, PHP_URL_HOST)); ?></a>
                    <br>
                    <span class="text-muted">
                        Если вы являетесь правообладателем и против использования вашего материала, 
                        <a href="<?php echo e(route('legal.dmca')); ?>" class="text-decoration-underline">свяжитесь с нами</a>.
                    </span>
                </p>
            </div>
            <?php endif; ?>

            <!-- Кнопка "Наверх" -->
            <div class="text-center mb-4">
                <button id="back-to-top" class="btn btn-primary rounded-circle">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Категории -->
            <?php if($recipe->categories->count() > 0): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-tags me-2"></i> Категории
                    </div>
                    <div class="card-body">
                        <div class="category-tags">
                            <?php $__currentLoopData = $recipe->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="badge bg-light text-dark text-decoration-none mb-2 me-2 p-2">
                                    <i class="bi bi-tag me-1"></i> <?php echo e($category->name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Поделиться рецептом -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-share me-2"></i> Поделиться рецептом
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-around">
                        <a href="https://wa.me/?text=<?php echo e(urlencode($recipe->title . ' - ' . request()->url())); ?>" target="_blank" class="btn btn-outline-success btn-sm" title="Поделиться в WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://t.me/share/url?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($recipe->title)); ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Поделиться в Telegram">
                            <i class="bi bi-telegram"></i>
                        </a>
                        <a href="https://vk.com/share.php?url=<?php echo e(urlencode(request()->url())); ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Поделиться ВКонтакте">
                            <i class="bi bi-vk"></i>
                        </a>
                        <button class="btn btn-outline-secondary btn-sm copy-link" data-url="<?php echo e(request()->url()); ?>" title="Копировать ссылку">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Похожие рецепты -->
            <?php if(isset($similarRecipes) && $similarRecipes->count() > 0): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-bookmarks me-2"></i> Похожие рецепты
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php $__currentLoopData = $similarRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarRecipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex align-items-center py-3">
                                    <?php if($similarRecipe->image_url): ?>
                                        <img src="<?php echo e(asset($similarRecipe->image_url)); ?>" alt="<?php echo e($similarRecipe->title); ?>" class="similar-recipe-img me-3 rounded" width="50" height="50">
                                    <?php else: ?>
                                        <div class="similar-recipe-placeholder me-3 rounded bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('recipes.show', $similarRecipe->slug)); ?>" class="text-decoration-none text-dark">
                                        <?php echo e($similarRecipe->title); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Распечатать рецепт -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <button class="btn btn-outline-dark w-100" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Распечатать рецепт
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для отправки рецепта на email -->
<div class="modal fade" id="emailRecipeModal" tabindex="-1" aria-labelledby="emailRecipeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailRecipeModalLabel">Отправить рецепт на email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="email-recipe-form">
                    <div class="mb-3">
                        <label for="recipient-email" class="form-label">Email получателя</label>
                        <input type="email" class="form-control" id="recipient-email" required>
                    </div>
                    <div class="mb-3">
                        <label for="email-message" class="form-label">Сообщение (необязательно)</label>
                        <textarea class="form-control" id="email-message" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="send-recipe-email">Отправить</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно со списком покупок -->
<div class="modal fade" id="shoppingListModal" tabindex="-1" aria-labelledby="shoppingListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shoppingListModalLabel">Список покупок</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group shopping-list">
                    <!-- Здесь будет список ингредиентов -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="copy-shopping-list">
                    <i class="bi bi-clipboard"></i> Копировать
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php
    /**
     * Получить правильное склонение слова "порция"
     */
    function getServingsWord($count) {
        $mod10 = $count % 10;
        $mod100 = $count % 100;
        
        if ($mod10 == 1 && $mod100 != 11) {
            return 'порцию';
        } elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 10 || $mod100 >= 20)) {
            return 'порции';
        } else {
            return 'порций';
        }
    }
    
    /**
     * Парсинг строки ингредиента для выделения названия, количества и единицы измерения
     */
    function parseIngredientLine($line) {
        $result = [
            'name' => trim($line),
            'quantity' => null,
            'unit' => 'по вкусу'
        ];
        
        // Улучшенный паттерн для обработки разных форматов записи ингредиентов
        // 1. Формат: "Название - 500 г" || "Название - 2 штуки"
        if (preg_match('/^(.*?)[\с—–-]+\с*(\д+(?:[.,]?\д+)?)\с*(г|кг|мл|л|шт\.?|ст\.л\.?|ч\.л\.?|стакан|пучок|зубчик|щепотка|банка|упаковка|пачка|чайные ложки|столовые ложки|штук[аи]?)\.?$/iu', $line, $matches)) {
            $result['name'] = trim($matches[1]);
            $result['quantity'] = str_replace(',', '.', $matches[2]);
            $result['unit'] = mb_strtolower(trim($matches[3]));
            
            // Приведение сложных единиц к стандартному виду
            if (stripос($result['unit'], 'чайн') !== false || stripос($result['unit'], 'ч.л') !== false) {
                $result['unit'] = 'ч.л.';
            } elseif (stripос($result['unit'], 'столов') !== false || stripос($result['unit'], 'ст.л') !== false) {
                $result['unit'] = 'ст.л.';
            } elseif (stripос($result['unit'], 'штук') !== false || $result['unit'] == 'шт') {
                $result['unit'] = 'шт.';
            } elseif (stripос($result['unit'], 'зубчик') !== false) {
                $result['unit'] = 'зубчик';
            }
        } 
        // 2. Формат: "Название - по вкусу"
        elseif (preg_match('/^(.*?)[\с—–-]+\с*(по\s+вкусу)$/iu', $line, $matches)) {
            $result['name'] = trim($matches[1]);
            $result['unit'] = 'по вкусу';
        }
        // 3. Формат: "Название - 1½ чайные ложки" (обработка дробей с символами)
        elseif (preg_match('/^(.*?)[\с—–-]+\с*(\д*[½¼¾⅓⅔]+)\с*(.*?)$/iu', $line, $matches)) {
            $result['name'] = trim($matches[1]);
            // Преобразование символов дробей в числовые значения
            $fractionMap = [
                '½' => 0.5,
                '¼' => 0.25,
                '¾' => 0.75,
                '⅓' => 0.33,
                '⅔' => 0.67
            ];
            $fractionStr = $matches[2];
            $numericPart = 0;
            
            // Извлекаем целое число, если оно есть
            if (preg_match('/^(\д+)/', $fractionStr, $numMatch)) {
                $numericPart = intval($numMatch[1]);
                $fractionStr = substr($fractionStr, strlen($numMatch[1]));
            }
            
            // Добавляем дробную часть
            foreach ($fractionMap as $symbol => $value) {
                if (strpos($fractionStr, $symbol) !== false) {
                    $numericPart += $value;
                    break;
                }
            }
            $result['quantity'] = $numericPart;
            $result['unit'] = mb_strtolower(trim($matches[3]));
            
            // Стандартизация единиц измерения
            if (stripос($result['unit'], 'чайн') !== false || stripос($result['unit'], 'ч.л') !== false) {
                $result['unit'] = 'ч.л.';
            } elseif (stripос($result['unit'], 'столов') !== false || stripос($result['unit'], 'ст.л') !== false) {
                $result['unit'] = 'ст.л.';
            }
        }
        // 4. Только число без единиц измерения
        elseif (preg_match('/^(.*?)[\с—–-]+\с*(\д+(?:[.,]\д+)?)$/iu', $line, $matches)) {
            $result['name'] = trim($matches[1]);
            $result['quantity'] = str_replace(',', '.', $matches[2]);
            $result['unit'] = 'шт.';
        }
        // 5. NEW: Формат с числом в начале строки "500 г Мука" (исправляем порядок распознавания)
        elseif (preg_match('/^(\д+(?:[.,]?\д+)?)\с*(г|кг|мл|л|шт\.?|ст\.л\.?|ч\.л\.?|стакан|пучок|зубчик|щепотка|банка|упаковка|пачка|чайные ложки|столовые ложки|штук[аи]?)\.?\с+(.*)$/iu', $line, $matches)) {
            $result['name'] = trim($matches[3]);
            $result['quantity'] = str_replace(',', '.', $matches[1]);
            $result['unit'] = mb_strtolower(trim($matches[2]));
            
            // Приведение сложных единиц к стандартному виду
            if (stripос($result['unit'], 'чайн') !== false || stripос($result['unit'], 'ч.л') !== false) {
                $result['unit'] = 'ч.л.';
            } elseif (stripос($result['unit'], 'столов') !== false || stripос($result['unit'], 'ст.л') !== false) {
                $result['unit'] = 'ст.л.';
            } elseif (stripос($result['unit'], 'штук') !== false || $result['unit'] == 'шт') {
                $result['unit'] = 'шт.';
            } elseif (stripос($result['unit'], 'зубчик') !== false) {
                $result['unit'] = 'зубчик';
            }
        }
        // 6. NEW: Формат с дробями в начале строки "1½ ч.л. Соль"
        elseif (preg_match('/^(\д*[½¼¾⅓⅔]+)\с*(.*?)\с+(.*)$/iu', $line, $matches)) {
            $result['name'] = trim($matches[3]);
            // Преобразование символов дробей в числовые значения
            $fractionMap = [
                '½' => 0.5,
                '¼' => 0.25,
                '¾' => 0.75,
                '⅓' => 0.33,
                '⅔' => 0.67
            ];
            $fractionStr = $matches[1];
            $numericPart = 0;
            
            // Извлекаем целое число, если оно есть
            if (preg_match('/^(\д+)/', $fractionStr, $numMatch)) {
                $numericPart = intval($numMatch[1]);
                $fractionStr = substr($fractionStr, strlen($numMatch[1]));
            }
            
            // Добавляем дробную часть
            foreach ($fractionMap as $symbol => $value) {
                if (strpos($fractionStr, $symbol) !== false) {
                    $numericPart += $value;
                    break;
                }
            }
            $result['quantity'] = $numericPart;
            $result['unit'] = mb_strtolower(trim($matches[2]));
            
            // Стандартизация единиц измерения
            if (stripос($result['unit'], 'чайн') !== false || stripос($result['unit'], 'ч.л') !== false) {
                $result['unit'] = 'ч.л.';
            } elseif (stripос($result['unit'], 'столов') !== false || stripос($result['unit'], 'ст.л') !== false) {
                $result['unit'] = 'ст.л.';
            }
        }
        
        return $result;
    }
?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация нового слайдера
        const recipeSlider = new Swiper(".mySwiper", {
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        // Функция для калькуляции ингредиентов по порциям
        const servingsInput = document.getElementById('servings-input');
        const decreaseBtn = document.getElementById('decrease-servings');
        const increaseBtn = document.getElementById('increase-servings');
        const resetBtn = document.getElementById('reset-servings');
        const ingredientsList = document.querySelector('.ingredients-list');
        const currentServingsDisplay = document.getElementById('current-servings');
        
        if (servingsInput и ingredientsList) {
            // Функция для обновления количества
            function updateIngredients() {
                const newServings = parseInt(servingsInput.value);
                if (isNaN(newServings) || newServings < 1) return;
                
                // Визуальный эффект при изменении
                ingredientItems.forEach(item => {
                    const quantityEl = item.querySelector('.quantity-value');
                    // Применяем эффект только к ингредиентам с числовыми количествами
                    if (quantityEl и !isNaN(parseFloat(quantityEl.textContent))) {
                        item.classList.add('highlight');
                        setTimeout(() => {
                            item.classList.remove('highlight');
                        }, 300);
                    }
                });
                
                // Обновляем отображаемое количество порций
                if (currentServingsDisplay) {
                    currentServingsDisplay.textContent = newServings;
                    
                    // Обновляем описательный текст с правильным склонением
                    const servingWordElement = currentServingsDisplay.nextElementSibling;
                    if (servingWordElement) {
                        const word = getServingsWord(newServings);
                        servingWordElement.textContent = word;
                    }
                }
                
                // Коэффициент пересчета
                const ratio = newServings / originalServings;
                
                // Обновляем все ингредиенты
                originalValues.forEach(item => {
                    if (item.value) {
                        // Округляем до 2 знаков после запятой
                        const newValue = (item.value * ratio).toFixed(2);
                        
                        // Убираем лишние нули и точку в конце
                        let displayValue = newValue;
                        if (displayValue.endsWith('.00')) {
                            displayValue = displayValue.slice(0, -3);
                        } else if (displayValue.endsWith('0')) {
                            displayValue = displayValue.slice(0, -1);
                            if (displayValue.endsWith('.0')) {
                                displayValue = displayValue.slice(0, -2);
                            }
                        }
                        item.element.textContent = displayValue;
                    }
                });
            }
            
            // Функция для получения правильного склонения слова "порция"
            function getServingsWord(count) {
                const mod10 = count % 10;
                const mod100 = count % 100;
                
                if (mod10 === 1 и mod100 !== 11) {
                    return 'порцию';
                } else if (mod10 >= 2 и мод10 <= 4 и (mod100 < 10 || mod100 >= 20)) {
                    return 'порции';
                } else {
                    return 'порций';
                }
            }
            
            // Обработчики событий
            if (servingsInput) {
                servingsInput.addEventListener('change', updateIngredients);
                servingsInput.addEventListener('input', function() {
                    // Ограничиваем минимальным значением 1
                    if (parseInt(this.value) < 1 || isNaN(parseInt(this.value))) {
                        this.value = 1;
                    }
                    // Ограничиваем максимальным значением 100
                    if (parseInt(this.value) > 100) {
                        this.value = 100;
                    }
                });
            }
            
            if (decreaseBtn) {
                decreaseBtn.addEventListener('click', () => {
                    if (parseInt(servingsInput.value) > 1) {
                        servingsInput.value = parseInt(servingsInput.value) - 1;
                        updateIngredients();
                    }
                });
            }
            
            if (increaseBtn) {
                increaseBtn.addEventListener('click', () => {
                    if (parseInt(servingsInput.value) < 100) {
                        servingsInput.value = parseInt(servingsInput.value) + 1;
                        updateIngredients();
                    }
                });
            }
            
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    servingsInput.value = originalServings;
                    updateIngredients();
                });
            }
        }
        
        // Функционал копирования ссылки
        const copyLinkBtn = document.querySelector('.copy-link');
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const url = this.dataset.url;
                navigator.clipboard.writeText(url).then(() => {
                    // Временно меняем иконку на галочку
                    const icon = this.querySelector('i');
                    icon.classList.remove('bi-clipboard');
                    icon.classList.add('bi-check-lg');
                    
                    // Показываем всплывающее сообщение
                    showToast('Ссылка скопирована!', 'success');
                    
                    // Возвращаем иконку через 3 секунды
                    setTimeout(() => {
                        icon.classList.remove('bi-check-lg');
                        icon.classList.add('bi-clipboard');
                    }, 3000);
                });
            });
        }
        
        // Функция для показа всплывающих уведомлений
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = '5';
            toast.innerHTML = `
                <div class="toast show align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-check-circle me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Удаляем toast через 3 секунды
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
        
        // Функционал добавления в избранное
        const favoriteBtn = document.querySelector('.add-to-favorites');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
                const recipeId = this.dataset.recipeId;
                
                // Эмуляция добавления в избранное (без запроса к серверу)
                const isFavorite = this.classList.contains('active');
                
                if (!isFavorite) {
                    this.classList.add('active');
                    this.classList.replace('btn-outline-primary', 'btn-primary');
                    this.querySelector('.favorite-text').textContent = 'В избранном';
                    this.querySelector('i').classList.replace('bi-heart', 'bi-heart-fill');
                    
                    // Показываем уведомление
                    showToast('Рецепт добавлен в избранное!', 'success');
                } else {
                    this.classList.remove('active');
                    this.classList.replace('btn-primary', 'btn-outline-primary');
                    this.querySelector('.favorite-text').textContent = 'В избранное';
                    this.querySelector('i').classList.replace('bi-heart-fill', 'bi-heart');
                    
                    // Показываем уведомление
                    showToast('Рецепт удален из избранного', 'info');
                }
            });
        }
        
        // Функционал отправки рецепта на email
        const sendEmailBtn = document.getElementById('send-recipe-email');
        if (sendEmailBtn) {
            sendEmailBtn.addEventListener('click', function() {
                const email = document.getElementById('recipient-email').value;
                const message = document.getElementById('email-message').value;
                
                if (!email) {
                    showToast('Пожалуйста, укажите email получателя', 'danger');
                    return;
                }
                
                // Эмуляция отправки (без запроса к серверу)
                sendEmailBtn.disabled = true;
                sendEmailBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Отправка...';
                
                setTimeout(() => {
                    // Закрываем модальное окно отправки
                    const modal = bootstrap.Modal.getInstance(document.getElementById('emailRecipeModal'));
                    modal.hide();
                    
                    // Сбрасываем состояние кнопки
                    sendEmailBtn.disabled = false;
                    sendEmailBtn.innerHTML = 'Отправить';
                    
                    // Очищаем форму
                    document.getElementById('recipient-email').value = '';
                    document.getElementById('email-message').value = '';
                    
                    // Показываем уведомление
                    showToast('Рецепт отправлен на указанный email!', 'success');
                }, 1500);
            });
        }
        
        // Функционал для чекбоксов ингредиентов
        const ingredientCheckboxes = document.querySelectorAll('.ingredient-checkbox');
        const checkAllBtn = document.getElementById('check-all-ingredients');
        const uncheckAllBtn = document.getElementById('uncheck-all-ingredients');
        
        ingredientCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const ingredientName = this.closest('li').querySelector('.ingredient-name');
                if (this.checked) {
                    ingredientName.style.textDecoration = 'line-through';
                    ingredientName.style.opacity = '0.6';
                } else {
                    ingredientName.style.textDecoration = '';
                    ingredientName.style.opacity = '';
                }
            });
        });
        
        if (checkAllBtn) {
            checkAllBtn.addEventListener('click', function() {
                ingredientCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });
        }
        
        if (uncheckAllBtn) {
            uncheckAllBtn.addEventListener('click', function() {
                ingredientCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });
        }
        
        // Функционал таймера приготовления
        const startTimerBtns = document.querySelectorAll('.start-timer-btn');
        const cookingTimer = document.getElementById('cooking-timer');
        const closeTimerBtn = document.getElementById('close-timer');
        const startPauseBtn = document.getElementById('start-pause-timer');
        const resetTimerBtn = document.getElementById('reset-timer');
        const timerCountdown = document.getElementById('timer-countdown');
        const timerStatus = document.getElementById('timer-status');
        
        let timerInterval = null;
        let cookingTime = 0;
        let timeLeft = 0;
        let timerRunning = false;
        
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
        
        function updateTimerDisplay() {
            timerCountdown.textContent = formatTime(timeLeft);
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerRunning = false;
                timerStatus.textContent = 'Время вышло!';
                startPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i> Старт';
                
                // Звуковое уведомление
                const audio = new Audio('/audio/notification.mp3');
                audio.play();
                
                // Показываем уведомление
                showToast('Таймер завершен! Блюдо готово!', 'success');
            }
        }
        
        function startTimer() {
            timerRunning = true;
            timerStatus.textContent = 'Готовим...';
            startPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> Пауза';
            
            timerInterval = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    updateTimerDisplay();
                } else {
                    clearInterval(timerInterval);
                    timerRunning = false;
                }
            }, 1000);
        }
        
        function pauseTimer() {
            timerRunning = false;
            timerStatus.textContent = 'Пауза';
            startPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i> Старт';
            clearInterval(timerInterval);
        }
        
        function resetTimer() {
            clearInterval(timerInterval);
            timerRunning = false;
            timeLeft = cookingTime * 60;
            updateTimerDisplay();
            timerStatus.textContent = 'Готово к запуску';
            startPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i> Старт';
        }
        
        startTimerBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                cookingTime = parseInt(this.dataset.cookingTime) || 0;
                timeLeft = cookingTime * 60;
                updateTimerDisplay();
                
                // Показываем таймер
                cookingTimer.classList.remove('d-none');
                cookingTimer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Сбрасываем состояние таймера
                resetTimer();
            });
        });
        
        if (closeTimerBtn) {
            closeTimerBtn.addEventListener('click', function() {
                cookingTimer.classList.add('d-none');
                clearInterval(timerInterval);
            });
        }
        
        if (startPauseBtn) {
            startPauseBtn.addEventListener('click', function() {
                if (timerRunning) {
                    pauseTimer();
                } else {
                    startTimer();
                }
            });
        }
        
        if (resetTimerBtn) {
            resetTimerBtn.addEventListener('click', resetTimer);
        }
        
        // Функционал генерации списка покупок
        const generateShoppingListBtn = document.getElementById('generation-shopping-list');
        const shoppingListContainer = document.querySelector('.shopping-list');
        const copyShoppingListBtn = document.getElementById('copy-shopping-list');
        
        if (generateShoppingListBtn и shoppingListContainer) {
            generateShoppingListBtn.addEventListener('click', function() {
                // Очищаем контейнер
                shoppingListContainer.innerHTML = '';
                
                // Собираем все ингредиенты
                const ingredientItems = document.querySelectorAll('.ingredient-item');
                
                ingredientItems.forEach(item => {
                    const ingredientName = item.dataset.name;
                    const quantity = item.dataset.quantity;
                    const unit = item.dataset.unit;
                    
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    
                    // Создаем текст ингредиента
                    const ingredientText = document.createElement('span');
                    ingredientText.textContent = ingredientName;
                    
                    // Создаем бейдж с количеством
                    const quantityBadge = document.createElement('span');
                    quantityBadge.className = 'badge bg-light text-dark';
                    
                    if (quantity и quantity !== 'null' и quantity !== '') {
                        quantityBadge.textContent = `${quantity} ${unit}`;
                    } else {
                        quantityBadge.textContent = unit;
                    }
                    
                    // Добавляем элементы в список
                    listItem.appendChild(ingredientText);
                    listItem.appendChild(quantityBadge);
                    shoppingListContainer.appendChild(listItem);
                });
                
                // Открываем модальное окно
                const modal = new bootstrap.Modal(document.getElementById('shoppingListModal'));
                modal.show();
            });
        }
        
        if (copyShoppingListBtn) {
            copyShoppingListBtn.addEventListener('click', function() {
                const shoppingItems = document.querySelectorAll('.shopping-list .list-group-item');
                let textToCopy = '';
                
                shoppingItems.forEach(item => {
                    const name = item.querySelector('span:first-child').textContent;
                    const quantity = item.querySelector('span:last-child').textContent;
                    textToCopy += `- ${name}: ${quantity}\n`;
                });
                
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const icon = this.querySelector('i');
                    icon.classList.remove('bi-clipboard');
                    icon.classList.add('bi-check-lg');
                    
                    setTimeout(() => {
                        icon.classList.remove('bi-check-lg');
                        icon.classList.add('bi-clipboard');
                    }, 3000);
                    
                    showToast('Список покупок скопирован!', 'success');
                });
            });
        }
        
        // Кнопка "Наверх"
        const backToTopBtn = document.getElementById('back-to-top');
        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 500) {
                    backToTopBtn.classList.add('visible');
                } else {
                    backToTopBtn.classList.remove('visible');
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



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\recipes\show.blade.php ENDPATH**/ ?>