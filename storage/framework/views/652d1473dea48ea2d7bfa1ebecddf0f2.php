<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php $__currentLoopData = $sitemaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sitemap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <sitemap>
        <loc><?php echo e($sitemap['url']); ?></loc>
        <lastmod><?php echo e($sitemap['lastmod']); ?></lastmod>
    </sitemap>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</sitemapindex>
<?php /**PATH C:\OSPanel\domains\eats\resources\views/sitemap/index.blade.php ENDPATH**/ ?>