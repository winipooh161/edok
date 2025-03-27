

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1>Управление рецептами</h1>
            <?php if(auth()->user()->isAdmin()): ?>
            <p class="text-muted">Вы просматриваете список всех рецептов (администраторский доступ)</p>
            <?php else: ?>
            <p class="text-muted">Вы просматриваете список ваших рецептов</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('admin.recipes.create')); ?>" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Добавить рецепт
            </a>
            <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('admin.parser.index')); ?>" class="btn btn-primary ms-2">
                <i class="fas fa-spider me-1"></i> Парсер рецептов
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">Фильтры</div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.recipes.index')); ?>" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Поиск по названию</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-4">
                    <label for="category_id" class="form-label">Категория</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Все категории</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <!-- Добавляем фильтр по пользователю только для админов -->
                <?php if(auth()->user()->isAdmin() && isset($users)): ?>
                <div class="col-md-4">
                    <label for="user_id" class="form-label">Автор</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">Все авторы</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> <?php echo e($user->role == 'admin' ? '(Admin)' : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                    <a href="<?php echo e(route('admin.recipes.index')); ?>" class="btn btn-secondary">Сбросить</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Список рецептов</h5>
            <span class="badge bg-secondary"><?php echo e($recipes->total()); ?> рецептов</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Автор</th>
                            <th>Категории</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($recipe->id); ?></td>
                                <td>
                                    <?php if($recipe->image_url): ?>
                                        <img src="<?php echo e(asset($recipe->image_url)); ?>" alt="<?php echo e($recipe->title); ?>" class="img-thumbnail" style="max-width: 80px; max-height: 60px;">
                                    <?php else: ?>
                                        <div class="text-center text-muted">
                                            <i class="fas fa-image fa-2x"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" target="_blank">
                                        <?php echo e($recipe->title); ?>

                                    </a>
                                </td>
                                <td><?php echo e($recipe->user ? $recipe->user->name : 'Не указан'); ?></td>
                                <td>
                                    <?php $__currentLoopData = $recipe->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-secondary"><?php echo e($category->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>
                                    <?php if($recipe->is_published): ?>
                                        <span class="badge bg-success">Опубликован</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Не опубликован</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($recipe->created_at->format('d.m.Y H:i')); ?></td>
                                <td>
                                    <?php if($recipe->isOwnedBy(auth()->user())): ?>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.recipes.edit', $recipe->id)); ?>" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.recipes.destroy', $recipe->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот рецепт?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Нет доступа</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Рецепты не найдены
                                    </div>
                                    <a href="<?php echo e(route('admin.recipes.create')); ?>" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Добавить рецепт
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($recipes->withQueryString()->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\admin\recipes\index.blade.php ENDPATH**/ ?>