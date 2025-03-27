

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Результаты пакетного парсинга</h3>
                        <div>
                            <a href="<?php echo e(route('admin.parser.batch')); ?>" class="btn btn-outline-primary me-2">
                                <i class="fas fa-sync-alt"></i> Запустить новый пакетный парсинг
                            </a>
                            <a href="<?php echo e(route('admin.recipes.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-list"></i> К списку рецептов
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h4>Обработка завершена</h4>
                    <div class="mb-4">
                        <p>Успешно обработано: <strong><?php echo e(isset($processed) ? count($processed) : 0); ?></strong></p>
                        <p>Не удалось обработать: <strong><?php echo e(isset($failed) ? count($failed) : 0); ?></strong></p>
                    </div>

                    <?php if(isset($processed) && count($processed) > 0): ?>
                        <h5>Успешно созданные рецепты:</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>URL</th>
                                        <th>Название рецепта</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $processed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <a href="<?php echo e($item['url']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 300px;">
                                                    <?php echo e($item['url']); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($item['title']); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('admin.recipes.edit', $item['id'])); ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Редактировать
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($failed) && count($failed) > 0): ?>
                        <h5 class="mt-4">Не удалось обработать следующие URL:</h5>
                        <div class="table-responsive">
                            <table class="table table-danger">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>URL</th>
                                        <th>Ошибка</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $failed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <a href="<?php echo e($item['url']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 300px;">
                                                    <?php echo e($item['url']); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($item['error']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(isset($total_urls) || isset($duplicate_urls)): ?>
            <div class="card mb-4 mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Результаты проверки URL</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($message)): ?>
                        <div class="alert alert-info">
                            <?php echo e($message); ?>

                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <h6>Статистика:</h6>
                        <ul>
                            <?php if(isset($total_urls)): ?>
                                <li>Уникальных URL для обработки: <strong><?php echo e($total_urls); ?></strong></li>
                            <?php endif; ?>
                            <?php if(isset($duplicate_urls) && is_array($duplicate_urls) && count($duplicate_urls) > 0): ?>
                                <li>Пропущено дубликатов: <strong><?php echo e(count($duplicate_urls)); ?></strong></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <?php if(isset($duplicate_urls) && is_array($duplicate_urls) && count($duplicate_urls) > 0): ?>
                        <div class="mb-3">
                            <h6>Список пропущенных URL (уже есть в базе):</h6>
                            <div class="border p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <ol class="mb-0">
                                    <?php $__currentLoopData = $duplicate_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e($url); ?>" target="_blank"><?php echo e($url); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ol>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Кнопки для продолжения или отмены процесса -->
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('admin.parser.batch')); ?>" class="btn btn-secondary">Вернуться</a>
                        <?php if(isset($total_urls) && $total_urls > 0): ?>
                            <a href="<?php echo e(route('admin.parser.processBatch')); ?>" class="btn btn-primary">
                                Начать обработку <?php echo e($total_urls); ?> URL
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/admin/parser/batch_result.blade.php ENDPATH**/ ?>