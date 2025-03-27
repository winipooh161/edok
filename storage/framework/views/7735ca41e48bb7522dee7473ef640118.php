

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h1>Управление категориями</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">Добавить категорию</a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Slug</th>
                            <th>Рецептов</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($category->id); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td><?php echo e($category->slug); ?></td>
                            <td><?php echo e($category->recipes_count); ?></td>
                            <td><?php echo e($category->created_at->format('d.m.Y H:i')); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="btn btn-sm btn-info" target="_blank">Просмотр</a>
                                    <a href="<?php echo e(route('admin.categories.edit', $category->id)); ?>" class="btn btn-sm btn-primary">Редактировать</a>
                                    <form action="<?php echo e(route('admin.categories.destroy', $category->id)); ?>" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($categories->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\admin\categories\index.blade.php ENDPATH**/ ?>