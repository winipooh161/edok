<?php $__env->startComponent('mail::message'); ?>
# Уведомление о нарушении авторских прав

Получена новая жалоба о нарушении авторских прав на сайте.

**Информация о заявителе:**
- **Имя/организация:** <?php echo e($name); ?>

- **Email:** <?php echo e($email); ?>


**Детали жалобы:**
- **URL проблемного контента:** <?php echo e($content_url); ?>

<?php if($original_url): ?>
- **URL оригинального материала:** <?php echo e($original_url); ?>

<?php endif; ?>

**Описание проблемы:**
<?php echo e($description); ?>


<?php $__env->startComponent('mail::button', ['url' => $content_url]); ?>
Просмотреть контент
<?php echo $__env->renderComponent(); ?>

Пожалуйста, проверьте информацию и примите необходимые меры.

С уважением,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\OSPanel\domains\eats\resources\views\emails\dmca-notification.blade.php ENDPATH**/ ?>