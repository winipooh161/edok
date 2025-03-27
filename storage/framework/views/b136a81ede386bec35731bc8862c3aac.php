

<?php $__env->startSection('meta_tags'); ?>
    <title><?php echo e(isset($search) ? "Поиск: $search" : "Все рецепты"); ?> | <?php echo e(config('app.name')); ?></title>
    <?php if(isset($search)): ?>
        <meta name="description" content="Результаты поиска по запросу '<?php echo e($search); ?>'. Найдено <?php echo e($recipes->total()); ?> рецептов с пошаговыми инструкциями и фото.">
    <?php else: ?>
        <meta name="description" content="Каталог кулинарных рецептов с подробными инструкциями, списком ингредиентов и пошаговыми фото. Найдите идеальный рецепт для любого случая!">
    <?php endif; ?>
    <link rel="canonical" href="<?php echo e(route('recipes.index', request()->query())); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <!-- Боковая панель с фильтрами -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Фильтры</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('search')); ?>" method="GET" id="search-form">
                        <!-- Поиск по ключевым словам -->
                        <div class="mb-3">
                            <label for="query" class="form-label">Ключевые слова</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="query" name="query" value="<?php echo e($search ?? ''); ?>" placeholder="Что ищем?">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="form-text">Введите название блюда или ингредиент</div>
                        </div>
                        
                        <!-- Тип поиска -->
                        <div class="mb-3">
                            <label class="form-label">Тип поиска</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="search_type" id="search_type_title" value="title" <?php echo e(($searchType ?? 'title') == 'title' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="search_type_title">
                                    По названию и описанию
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="search_type" id="search_type_ingredients" value="ingredients" <?php echo e(($searchType ?? '') == 'ingredients' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="search_type_ingredients">
                                    По ингредиентам
                                </label>
                            </div>
                        </div>
                        
                        <!-- Ингредиенты (появляется при выборе соответствующего типа поиска) -->
                        <div class="mb-3 ingredients-container" style="<?php echo e(($searchType ?? '') != 'ingredients' ? 'display: none;' : ''); ?>">
                            <label class="form-label">Список ингредиентов</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="ingredient-input" placeholder="Введите ингредиент">
                                <button class="btn btn-outline-secondary" type="button" id="add-ingredient">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="ingredients-list" class="mb-2">
                                <?php if(!empty($ingredients)): ?>
                                    <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="ingredient-item d-flex align-items-center justify-content-between mb-1 p-2 bg-light rounded">
                                            <span><?php echo e($ingredient); ?></span>
                                            <input type="hidden" name="ingredients[]" value="<?php echo e($ingredient); ?>">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-ingredient">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Добавьте один или несколько ингредиентов</div>
                        </div>
                        
                        <!-- Фильтр по категории -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Категория</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Все категории</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(($selectedCategory ?? '') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?> (<?php echo e($category->recipes_count); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <!-- Фильтр по времени приготовления -->
                        <div class="mb-3">
                            <label for="cooking_time" class="form-label">Время приготовления</label>
                            <select class="form-select" id="cooking_time" name="cooking_time">
                                <option value="">Любое время</option>
                                <option value="15" <?php echo e(($selectedCookingTime ?? '') == 15 ? 'selected' : ''); ?>>До 15 минут</option>
                                <option value="30" <?php echo e(($selectedCookingTime ?? '') == 30 ? 'selected' : ''); ?>>До 30 минут</option>
                                <option value="60" <?php echo e(($selectedCookingTime ?? '') == 60 ? 'selected' : ''); ?>>До 1 часа</option>
                                <option value="120" <?php echo e(($selectedCookingTime ?? '') == 120 ? 'selected' : ''); ?>>До 2 часов</option>
                            </select>
                        </div>
                        
                        <!-- Только с фото -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="has_image" name="has_image" value="1" <?php echo e(request()->has('has_image') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="has_image">Только с фото</label>
                        </div>
                        
                        <!-- Кнопки действий -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Применить фильтры
                            </button>
                            <a href="<?php echo e(route('recipes.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-1"></i> Сбросить все фильтры
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Популярные теги для быстрого поиска -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Популярные теги</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo e(route('search', ['query' => 'завтрак'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">завтрак</a>
                        <a href="<?php echo e(route('search', ['query' => 'десерт'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">десерт</a>
                        <a href="<?php echo e(route('search', ['query' => 'быстрый ужин'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">быстрый ужин</a>
                        <a href="<?php echo e(route('search', ['query' => 'суп'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">суп</a>
                        <a href="<?php echo e(route('search', ['query' => 'курица'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">курица</a>
                        <a href="<?php echo e(route('search', ['query' => 'вегетарианское'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">вегетарианское</a>
                        <a href="<?php echo e(route('search', ['query' => 'без глютена'])); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none p-2">без глютена</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Основной контент - список рецептов -->
        <div class="col-lg-9">
            <!-- Заголовок и информация о поиске -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <?php if(!empty($search)): ?>
                        <h2>Результаты поиска: "<?php echo e($search); ?>"</h2>
                        <p class="text-muted">
                            Найдено <?php echo e($recipes->total()); ?> <?php echo e(trans_choice('рецепт|рецепта|рецептов', $recipes->total())); ?>

                            <?php if(!empty($searchTerms)): ?>
                                <span class="small">(искали: <?php echo e(implode(', ', $searchTerms)); ?>)</span>
                            <?php endif; ?>
                        </p>
                    <?php elseif(!empty($selectedCategory)): ?>
                        <h2>Рецепты в категории</h2>
                    <?php else: ?>
                        <h2>Все рецепты</h2>
                    <?php endif; ?>
                </div>
                
                <!-- Сортировка результатов -->
                <div class="d-flex align-items-center">
                    <label for="sort" class="form-label me-2 mb-0">Сортировка:</label>
                    <select class="form-select form-select-sm" id="sort" name="sort">
                        <option value="relevance" <?php echo e(request('sort') == 'relevance' ? 'selected' : ''); ?>>По релевантности</option>
                        <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Новые первыми</option>
                        <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>Популярные</option>
                        <option value="cooking_time_asc" <?php echo e(request('sort') == 'cooking_time_asc' ? 'selected' : ''); ?>>По времени (возр.)</option>
                        <option value="cooking_time_desc" <?php echo e(request('sort') == 'cooking_time_desc' ? 'selected' : ''); ?>>По времени (убыв.)</option>
                    </select>
                </div>
            </div>
            
            <!-- Результаты поиска -->
            <?php if($recipes->count() > 0): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
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
                                    <h5 class="card-title">
                                        <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="text-decoration-none text-dark">
                                            <?php if(!empty($search)): ?>
                                                <?php echo preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->title); ?>

                                            <?php else: ?>
                                                <?php echo e($recipe->title); ?>

                                            <?php endif; ?>
                                        </a>
                                    </h5>
                                    
                                    <?php if($recipe->user): ?>
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-user me-1"></i> <?php echo e($recipe->user->name); ?>

                                        </p>
                                    <?php endif; ?>
                                    
                                    <p class="card-text">
                                        <?php if(!empty($search) && !empty($recipe->description)): ?>
                                            <?php echo Str::limit(preg_replace('/(' . implode('|', $searchTerms) . ')/iu', '<span class="bg-warning">$1</span>', $recipe->description), 100); ?>

                                        <?php else: ?>
                                            <?php echo e(Str::limit($recipe->description, 100)); ?>

                                        <?php endif; ?>
                                    </p>
                                    
                                    <?php if(!$recipe->categories->isEmpty()): ?>
                                        <div class="mb-2">
                                            <?php $__currentLoopData = $recipe->categories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="badge bg-light text-dark text-decoration-none me-1">
                                                    <?php echo e($category->name); ?>

                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-eye me-1"></i> <?php echo e($recipe->views); ?>

                                            </span>
                                        </div>
                                        <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-book-open me-1"></i> Смотреть рецепт
                                        </a>
                                    </div>
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
                    <i class="fas fa-info-circle me-2"></i> По вашему запросу ничего не найдено.
                    <?php if(!empty($search)): ?>
                        <p class="mt-2 mb-0">Попробуйте изменить поисковый запрос или уменьшить количество фильтров.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Предложения, если ничего не найдено -->
                <div class="mt-4">
                    <h4>Возможно, вас заинтересует:</h4>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-utensils fa-3x text-primary mb-3"></i>
                                    <h5>Популярные рецепты</h5>
                                    <p class="text-muted">Посмотрите наши самые популярные рецепты</p>
                                    <a href="<?php echo e(route('recipes.index', ['sort' => 'popular'])); ?>" class="btn btn-sm btn-outline-primary">
                                        Показать
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-3x text-success mb-3"></i>
                                    <h5>Быстрые рецепты</h5>
                                    <p class="text-muted">Рецепты, которые можно приготовить быстро</p>
                                    <a href="<?php echo e(route('recipes.index', ['cooking_time' => 30])); ?>" class="btn btn-sm btn-outline-success">
                                        Показать
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-random fa-3x text-warning mb-3"></i>
                                    <h5>Случайный рецепт</h5>
                                    <p class="text-muted">Не знаете, что приготовить? Попробуйте что-то новое!</p>
                                    <a href="<?php echo e(route('recipes.index', ['random' => 1])); ?>" class="btn btn-sm btn-outline-warning">
                                        Удивите меня
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .recipe-img {
        height: 200px;
        object-fit: cover;
    }
    
    .recipe-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .ingredient-item {
        transition: all 0.3s ease;
    }
    
    .ingredient-item:hover {
        background-color: #f1f1f1 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Переключение отображения контейнера ингредиентов в зависимости от выбранного типа поиска
        const searchTypeRadios = document.querySelectorAll('input[name="search_type"]');
        const ingredientsContainer = document.querySelector('.ingredients-container');
        
        searchTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'ingredients') {
                    ingredientsContainer.style.display = 'block';
                } else {
                    ingredientsContainer.style.display = 'none';
                }
            });
        });
        
        // Добавление ингредиентов в список
        const addIngredientBtn = document.getElementById('add-ingredient');
        const ingredientInput = document.getElementById('ingredient-input');
        const ingredientsList = document.getElementById('ingredients-list');
        
        if (addIngredientBtn && ingredientInput && ingredientsList) {
            addIngredientBtn.addEventListener('click', function() {
                addIngredient();
            });
            
            ingredientInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addIngredient();
                }
            });
            
            function addIngredient() {
                const ingredient = ingredientInput.value.trim();
                if (ingredient) {
                    // Проверяем, нет ли уже такого ингредиента в списке
                    const existingIngredients = Array.from(ingredientsList.querySelectorAll('input[type="hidden"]')).map(input => input.value);
                    if (!existingIngredients.includes(ingredient)) {
                        // Создаем элемент для отображения ингредиента
                        const ingredientItem = document.createElement('div');
                        ingredientItem.className = 'ingredient-item d-flex align-items-center justify-content-between mb-1 p-2 bg-light rounded';
                        ingredientItem.innerHTML = `
                            <span>${ingredient}</span>
                            <input type="hidden" name="ingredients[]" value="${ingredient}">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-ingredient">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        
                        // Добавляем функцию удаления
                        const removeBtn = ingredientItem.querySelector('.remove-ingredient');
                        removeBtn.addEventListener('click', function() {
                            ingredientItem.remove();
                        });
                        
                        // Добавляем в список
                        ingredientsList.appendChild(ingredientItem);
                        
                        // Очищаем поле ввода
                        ingredientInput.value = '';
                    } else {
                        alert('Этот ингредиент уже добавлен в список!');
                    }
                }
            }
            
            // Добавляем обработчики для существующих кнопок удаления
            document.querySelectorAll('.remove-ingredient').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.ingredient-item').remove();
                });
            });
        }
        
        // Обработка изменения сортировки
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const form = document.getElementById('search-form');
                const sortInput = document.createElement('input');
                sortInput.type = 'hidden';
                sortInput.name = 'sort';
                sortInput.value = this.value;
                form.appendChild(sortInput);
                form.submit();
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/recipes/index.blade.php ENDPATH**/ ?>