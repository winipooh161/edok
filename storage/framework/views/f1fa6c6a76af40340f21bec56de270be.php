

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <?php if($user->avatar): ?>
                            <img src="<?php echo e(asset('storage/'.$user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center text-white bg-primary" style="width: 150px; height: 150px; font-size: 3rem;">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h5 class="mb-1"><?php echo e($user->name); ?></h5>
                    <p class="text-muted mb-3">
                        <?php if($user->isAdmin()): ?>
                            <span class="badge bg-danger">Администратор</span>
                        <?php else: ?>
                            <span class="badge bg-primary">Пользователь</span>
                        <?php endif; ?>
                        
                        <?php if($user->is_verified): ?>
                            <span class="badge bg-success ms-1">
                                <i class="fas fa-check-circle me-1"></i> Проверенный автор
                            </span>
                        <?php endif; ?>
                    </p>
                    
                    <?php if($user->id === auth()->id()): ?>
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Редактировать профиль
                            </a>
                            <a href="<?php echo e(route('profile.change-password')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-key me-1"></i> Изменить пароль
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">О пользователе</div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($user->bio ?: 'Пользователь пока не рассказал о себе.'); ?></p>
                </div>
                <div class="card-footer bg-white">
                    <div class="row text-center">
                        <div class="col">
                            <h5 class="mb-0"><?php echo e($user->recipes->count()); ?></h5>
                            <small class="text-muted">Рецептов</small>
                        </div>
                        <div class="col">
                            <h5 class="mb-0"><?php echo e($user->recipes->sum('views')); ?></h5>
                            <small class="text-muted">Просмотров</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Рецепты пользователя</h5>
                    <?php if($user->id === auth()->id()): ?>
                        <a href="<?php echo e(route('admin.recipes.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Добавить рецепт
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if($recipes->count() > 0): ?>
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php $__currentLoopData = $recipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col">
                                    <div class="card h-100 shadow-sm">
                                        <img src="<?php echo e($recipe->getImageUrl()); ?>" class="card-img-top" alt="<?php echo e($recipe->title); ?>" style="height: 180px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo e($recipe->title); ?></h5>
                                            <p class="card-text text-muted"><?php echo e(Str::limit($recipe->description, 80)); ?></p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-light text-dark">
                                                    <i class="far fa-eye me-1"></i> <?php echo e($recipe->views); ?>

                                                </span>
                                                
                                                <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-book-open me-1"></i> Смотреть
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <div class="mt-4">
                            <?php echo e($recipes->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> У пользователя пока нет опубликованных рецептов.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\profile\show.blade.php ENDPATH**/ ?>