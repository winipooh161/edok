

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Форма для правообладателей</h1>
                </div>
                <div class="card-body">
                    <p>Если вы обнаружили на нашем сайте материалы, которые нарушают ваши авторские права, пожалуйста, заполните эту форму для подачи жалобы.</p>
                    
                    <div class="alert alert-info mb-4">
                        <p class="mb-0">Мы рассматриваем все обращения правообладателей и стремимся оперативно реагировать на запросы об удалении контента. Материалы, нарушающие авторские права, будут удалены в кратчайшие сроки.</p>
                    </div>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo e(route('legal.dmca.submit')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Ваше имя или наименование организации *</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                            <?php $__errorArgs = ['name'];
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
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Ваш email *</label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                            <?php $__errorArgs = ['email'];
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
                        
                        <div class="mb-3">
                            <label for="content_url" class="form-label">URL страницы с нарушающим материалом *</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['content_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="content_url" name="content_url" value="<?php echo e(old('content_url')); ?>" required>
                            <?php $__errorArgs = ['content_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Укажите полный URL страницы, на которой размещен контент, нарушающий ваши права</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="original_url" class="form-label">URL оригинального материала (если имеется)</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['original_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="original_url" name="original_url" value="<?php echo e(old('original_url')); ?>">
                            <?php $__errorArgs = ['original_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Если возможно, укажите ссылку на оригинальный контент, чтобы мы могли подтвердить ваши права</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание проблемы *</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="5" required><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Пожалуйста, опишите нарушение ваших прав и укажите, какой именно контент (текст, изображение, видео) нарушает ваши авторские права</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input <?php $__errorArgs = ['confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="checkbox" id="confirmation" name="confirmation" required>
                                <label class="form-check-label" for="confirmation">
                                    Я подтверждаю, что являюсь правообладателем или уполномоченным представителем правообладателя, и имею все основания для подачи этой жалобы *
                                </label>
                                <?php $__errorArgs = ['confirmation'];
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
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Отправить жалобу</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <p class="text-muted mb-0">* - Обязательные поля для заполнения</p>
                </div>
            </div>
            
            <div class="mt-4">
                <p>Также вы можете связаться с нами по электронной почте: <a href="mailto:<?php echo e(config('app.contact_email', 'contact@example.com')); ?>"><?php echo e(config('app.contact_email', 'contact@example.com')); ?></a></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\legal\dmca.blade.php ENDPATH**/ ?>