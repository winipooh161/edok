

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-lg-6">
            <h1 class="mb-0"><i class="fas fa-utensils text-primary me-2"></i> Управление рецептами</h1>
            <p class="text-muted">Просмотр и редактирование рецептов</p>
        </div>
        <div class="col-lg-6 text-end">
            <a href="<?php echo e(route('admin.recipes.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Добавить новый рецепт
            </a>
            <a href="<?php echo e(route('admin.parser.index')); ?>" class="btn btn-success ms-2">
                <i class="fas fa-file-import me-1"></i> Импортировать рецепт
            </a>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Фильтры</h5>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="<?php echo e(request()->hasAny(['search', 'user_id', 'category_id', 'status']) ? 'true' : 'false'); ?>">
                <i class="fas fa-chevron-<?php echo e(request()->hasAny(['search', 'user_id', 'category_id', 'status']) ? 'up' : 'down'); ?>"></i>
            </button>
        </div>
        <div class="card-body collapse <?php echo e(request()->hasAny(['search', 'user_id', 'category_id', 'status']) ? 'show' : ''); ?>" id="filtersCollapse">
            <form action="<?php echo e(route('admin.recipes.index')); ?>" method="GET" class="row g-3" id="filter-form">
                <?php if(auth()->user()->isAdmin() && isset($users)): ?>
                <div class="col-md-4">
                    <label for="user_id" class="form-label">Автор</label>
                    <select class="form-select shadow-sm" id="user_id" name="user_id">
                        <option value="">Все авторы</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> <?php echo $user->role == 'admin' ? '<span class="text-warning">(Админ)</span>' : ''; ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md-4">
                    <label for="category_id" class="form-label">Категория</label>
                    <select class="form-select shadow-sm" id="category_id" name="category_id">
                        <option value="">Все категории</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="status" class="form-label">Статус публикации</label>
                    <select class="form-select shadow-sm" id="status" name="status">
                        <option value="">Все статусы</option>
                        <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Опубликованные</option>
                        <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Черновики</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="search" class="form-label">Поиск по названию</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control shadow-sm" id="search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Введите текст...">
                        <?php if(request('search')): ?>
                            <button type="button" class="btn btn-outline-secondary clear-input" data-target="search">
                                <i class="fas fa-times"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="sort" class="form-label">Сортировка</label>
                    <select class="form-select shadow-sm" id="sort" name="sort">
                        <option value="created_at_desc" <?php echo e(request('sort', 'created_at_desc') == 'created_at_desc' ? 'selected' : ''); ?>>Сначала новые</option>
                        <option value="created_at_asc" <?php echo e(request('sort') == 'created_at_asc' ? 'selected' : ''); ?>>Сначала старые</option>
                        <option value="title_asc" <?php echo e(request('sort') == 'title_asc' ? 'selected' : ''); ?>>По названию (А-Я)</option>
                        <option value="title_desc" <?php echo e(request('sort') == 'title_desc' ? 'selected' : ''); ?>>По названию (Я-А)</option>
                        <option value="views_desc" <?php echo e(request('sort') == 'views_desc' ? 'selected' : ''); ?>>По популярности</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="per_page" class="form-label">Элементов на странице</label>
                    <select class="form-select shadow-sm" id="per_page" name="per_page">
                        <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>10</option>
                        <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25</option>
                        <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50</option>
                        <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Применить фильтры
                            </button>
                            <a href="<?php echo e(route('admin.recipes.index')); ?>" class="btn btn-secondary ms-2" id="reset-filters">
                                <i class="fas fa-undo me-1"></i> Сбросить
                            </a>
                        </div>
                        <?php if(request()->hasAny(['search', 'user_id', 'category_id', 'status', 'sort', 'per_page'])): ?>
                            <div class="text-end">
                                <span class="badge bg-info">
                                    <i class="fas fa-info-circle me-1"></i> Применены фильтры: 
                                    <?php if(request('search')): ?> <span class="badge bg-secondary ms-1">Поиск</span> <?php endif; ?>
                                    <?php if(request('user_id')): ?> <span class="badge bg-secondary ms-1">Автор</span> <?php endif; ?>
                                    <?php if(request('category_id')): ?> <span class="badge bg-secondary ms-1">Категория</span> <?php endif; ?>
                                    <?php if(request('status') !== null && request('status') !== ''): ?> <span class="badge bg-secondary ms-1">Статус</span> <?php endif; ?>
                                    <?php if(request('sort') && request('sort') != 'created_at_desc'): ?> <span class="badge bg-secondary ms-1">Сортировка</span> <?php endif; ?>
                                    <?php if(request('per_page') && request('per_page') != 10): ?> <span class="badge bg-secondary ms-1">Пагинация</span> <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Список рецептов</h5>
            <span class="badge bg-primary rounded-pill"><?php echo e($recipes->total()); ?> рецептов</span>
        </div>
        
        <?php if(request()->hasAny(['search', 'user_id', 'category_id', 'status'])): ?>
            <div class="card-header bg-light py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Результаты фильтрации:</strong>
                        <?php if(request('search')): ?>
                            <span class="badge bg-info ms-2">Поиск: "<?php echo e(request('search')); ?>"</span>
                        <?php endif; ?>
                        <?php if(request('user_id') && isset($users)): ?>
                            <?php
                                $selectedUser = $users->firstWhere('id', request('user_id'));
                                $userName = $selectedUser ? $selectedUser->name : 'ID: ' . request('user_id');
                            ?>
                            <span class="badge bg-info ms-2">Автор: <?php echo e($userName); ?></span>
                        <?php endif; ?>
                        <?php if(request('category_id')): ?>
                            <?php
                                $selectedCategory = $categories->firstWhere('id', request('category_id'));
                                $categoryName = $selectedCategory ? $selectedCategory->name : 'ID: ' . request('category_id');
                            ?>
                            <span class="badge bg-info ms-2">Категория: <?php echo e($categoryName); ?></span>
                        <?php endif; ?>
                        <?php if(request('status') !== null && request('status') !== ''): ?>
                            <span class="badge bg-info ms-2">Статус: <?php echo e(request('status') == '1' ? 'Опубликованные' : 'Черновики'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <a href="<?php echo e(route('admin.recipes.index')); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Очистить фильтры
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="60">ID</th>
                            <th width="100">Изображение</th>
                            <th>Название</th>
                            <th>Автор</th>
                            <th>Категории</th>
                            <th class="text-center">Статус</th>
                            <th class="text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center"><?php echo e($recipe->id); ?></td>
                                <td>
                                    <img src="<?php echo e($recipe->getImageUrl()); ?>" alt="<?php echo e($recipe->title); ?>" 
                                        class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.recipes.edit', $recipe)); ?>" 
                                       class="fw-bold text-decoration-none text-truncate d-block" 
                                       style="max-width: 250px;">
                                        <?php echo e($recipe->title); ?>

                                    </a>
                                    <small class="text-muted">
                                        <?php echo e(Str::limit($recipe->description, 50)); ?>

                                    </small>
                                </td>
                                <td>
                                    <?php echo e($recipe->user->name ?? 'Н/Д'); ?>

                                    <?php if($recipe->user && $recipe->user->isAdmin()): ?>
                                        <span class="badge bg-warning text-dark">Админ</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $__currentLoopData = $recipe->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-secondary"><?php echo e($category->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td class="text-center">
                                    <?php if($recipe->is_published): ?>
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Опубликован</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fas fa-file me-1"></i> Черновик</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-sm btn-outline-primary" target="_blank" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.recipes.edit', $recipe)); ?>" class="btn btn-sm btn-outline-secondary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.recipes.destroy', $recipe)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить этот рецепт?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i> Рецепты не найдены
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($recipes->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($recipes->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Автоматическая отправка формы при изменении полей выбора
        const autoSubmitSelects = document.querySelectorAll('#filter-form select');
        autoSubmitSelects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
        
        // Обработка кнопок очистки в полях ввода
        const clearButtons = document.querySelectorAll('.clear-input');
        clearButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                document.getElementById(targetId).value = '';
                document.getElementById('filter-form').submit();
            });
        });
        
        // Сохранение состояния фильтров в localStorage
        const saveFilters = function() {
            const formData = new FormData(document.getElementById('filter-form'));
            const filters = {};
            
            for (let [key, value] of formData.entries()) {
                filters[key] = value;
            }
            
            localStorage.setItem('admin_recipe_filters', JSON.stringify(filters));
        };
        
        // Загрузка фильтров из localStorage (только если нет активных фильтров в URL)
        const loadFilters = function() {
            if (window.location.search === '') {
                const savedFilters = localStorage.getItem('admin_recipe_filters');
                
                if (savedFilters) {
                    const filters = JSON.parse(savedFilters);
                    let hasValidFilters = false;
                    
                    for (let key in filters) {
                        const input = document.querySelector(`#filter-form [name="${key}"]`);
                        if (input && filters[key]) {
                            input.value = filters[key];
                            hasValidFilters = true;
                        }
                    }
                    
                    if (hasValidFilters) {
                        document.getElementById('filter-form').submit();
                    }
                }
            }
        };
        
        // Вызов сохранения при отправке формы
        document.getElementById('filter-form').addEventListener('submit', saveFilters);
        
        // Очистка сохраненных фильтров при сбросе
        document.getElementById('reset-filters').addEventListener('click', function(e) {
            localStorage.removeItem('admin_recipe_filters');
        });
        
        // Загрузка фильтров при загрузке страницы
        loadFilters();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/admin/recipes/index.blade.php ENDPATH**/ ?>