

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="animate-on-scroll"><i class="fas fa-edit text-primary me-2"></i> Проверка импортированного рецепта</h1>
            <p class="animate-on-scroll">Проверьте и отредактируйте данные перед сохранением рецепта.</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('admin.parser.index')); ?>" class="btn btn-secondary animate-on-scroll">
                <i class="fas fa-arrow-left me-1"></i> Назад к парсеру
            </a>
        </div>
    </div>
    
    <div class="card animate-on-scroll">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit me-2"></i> Редактирование данных рецепта
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.parser.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="source_url" value="<?php echo e($parseResult['source_url']); ?>">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Название рецепта*</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title" name="title" 
                                   value="<?php echo e(old('title', $parseResult['title'])); ?>" required>
                            <?php $__errorArgs = ['title'];
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
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="3"><?php echo e(old('description', $parseResult['description'])); ?></textarea>
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
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="ingredients" class="form-label">Ингредиенты*</label>
                                
                                <?php if(!empty($parseResult['servings'])): ?>
                                <div class="input-group" style="width: 180px;">
                                    <span class="input-group-text">Порций</span>
                                    <input type="number" class="form-control" id="servings" name="servings" value="<?php echo e(old('servings', $parseResult['servings'])); ?>" min="1">
                                </div>
                                <?php endif; ?>
                            </div>
                            <textarea class="form-control <?php $__errorArgs = ['ingredients'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ingredients" name="ingredients" rows="10" required><?php echo e(old('ingredients', $parseResult['ingredients'])); ?></textarea>
                            <?php $__errorArgs = ['ingredients'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Каждый ингредиент должен быть на новой строке.</div>
                            
                            <?php if(!empty($parseResult['structured_ingredients'])): ?>
                            <input type="hidden" name="structured_ingredients" value="<?php echo e(json_encode($parseResult['structured_ingredients'])); ?>">
                            
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#structuredIngredients">
                                    <i class="fas fa-table me-1"></i> Показать структурированные ингредиенты
                                </button>
                                <div class="collapse mt-2" id="structuredIngredients">
                                    <div class="card card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Ингредиент</th>
                                                        <th>Количество</th>
                                                        <th>Ед. изм.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $parseResult['structured_ingredients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($ingredient['name']); ?></td>
                                                        <td><?php echo e($ingredient['quantity'] ?? '-'); ?></td>
                                                        <td><?php echo e($ingredient['unit']); ?></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Инструкции*</label>
                            <textarea class="form-control <?php $__errorArgs = ['instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="instructions" name="instructions" rows="10" required><?php echo e(old('instructions', $parseResult['instructions'])); ?></textarea>
                            <?php $__errorArgs = ['instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Каждый шаг должен быть на новой строке.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cooking_time" class="form-label">Время приготовления (минуты)</label>
                            <input type="number" class="form-control <?php $__errorArgs = ['cooking_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cooking_time" name="cooking_time" 
                                   value="<?php echo e(old('cooking_time', $parseResult['cooking_time'])); ?>">
                            <?php $__errorArgs = ['cooking_time'];
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
                        
                        <!-- Энергетическая ценность -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>Энергетическая ценность на порцию</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label for="calories" class="form-label">Калорийность (ккал)</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['calories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="calories" name="calories" 
                                               value="<?php echo e(old('calories', $parseResult['calories'])); ?>">
                                        <?php $__errorArgs = ['calories'];
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
                                    <div class="col-6 mb-2">
                                        <label for="proteins" class="form-label">Белки (г)</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['proteins'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="proteins" name="proteins" 
                                               value="<?php echo e(old('proteins', $parseResult['proteins'])); ?>">
                                        <?php $__errorArgs = ['proteins'];
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
                                    <div class="col-6 mb-2">
                                        <label for="fats" class="form-label">Жиры (г)</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['fats'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="fats" name="fats" 
                                               value="<?php echo e(old('fats', $parseResult['fats'])); ?>">
                                        <?php $__errorArgs = ['fats'];
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
                                    <div class="col-6 mb-2">
                                        <label for="carbs" class="form-label">Углеводы (г)</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['carbs'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="carbs" name="carbs" 
                                               value="<?php echo e(old('carbs', $parseResult['carbs'])); ?>">
                                        <?php $__errorArgs = ['carbs'];
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
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categories" class="form-label">Категории</label>
                            
                            <?php if(!empty($newCategories)): ?>
                            <div class="alert alert-success mb-2">
                                <i class="fas fa-info-circle me-2"></i> Автоматически созданы следующие категории:
                                <ul class="mb-0 mt-1">
                                    <?php $__currentLoopData = $newCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $newCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($newCategory->name); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            
                            <div class="card mb-2 bg-light">
                                <div class="card-body py-2">
                                    <strong>Обнаруженные категории:</strong>
                                    <?php if(!empty($parseResult['detected_categories'])): ?>
                                        <?php echo e(implode(', ', $parseResult['detected_categories'])); ?>

                                    <?php else: ?>
                                        <em>Категории не обнаружены</em>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <select multiple class="form-select <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="categories" name="categories[]" size="8">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(in_array($category->id, $selectedCategories) ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Удерживайте Ctrl (Cmd на Mac) для выбора нескольких категорий</div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" checked>
                            <label class="form-check-label" for="is_published">Опубликовать сразу</label>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Изображения найденные на странице</label>
                            <p class="form-text">Первое изображение будет использовано как главное для рецепта.</p>
                            
                            <!-- Переключатель режима отображения изображений -->
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary select-all-images">
                                    <i class="fas fa-check-square me-1"></i> Выбрать все
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary unselect-all-images">
                                    <i class="fas fa-square me-1"></i> Отменить все
                                </button>
                                <div class="form-check form-switch d-inline-block ms-3">
                                    <input class="form-check-input" type="checkbox" id="show-only-recipe-images" checked>
                                    <label class="form-check-label" for="show-only-recipe-images">Только изображения рецепта</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Сначала выводим только изображения рецепта -->
                                <?php if(!empty($parseResult['recipe_image_urls'])): ?>
                                    <?php $__currentLoopData = $parseResult['recipe_image_urls']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-6 mb-3 image-item recipe-image">
                                            <div class="card h-100">
                                                <img src="<?php echo e($imageUrl); ?>" class="card-img-top" alt="Изображение <?php echo e($index + 1); ?>" style="height: 120px; object-fit: cover;">
                                                <div class="card-body p-2 text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input image-checkbox" type="checkbox" name="image_urls[]" value="<?php echo e($imageUrl); ?>" id="image_recipe_<?php echo e($index); ?>" checked>
                                                        <label class="form-check-label" for="image_recipe_<?php echo e($index); ?>">
                                                            <?php echo e($index === 0 ? 'Главное' : 'Выбрать'); ?>

                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                
                                <!-- Другие изображения (будут скрыты по умолчанию) -->
                                <?php if(!empty($parseResult['image_urls'])): ?>
                                    <?php $__currentLoopData = $parseResult['image_urls']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!in_array($imageUrl, $parseResult['recipe_image_urls'] ?? [])): ?>
                                            <div class="col-6 mb-3 image-item other-image" style="display: none;">
                                                <div class="card h-100">
                                                    <img src="<?php echo e($imageUrl); ?>" class="card-img-top" alt="Изображение <?php echo e($index + 1); ?>" style="height: 120px; object-fit: cover;">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input image-checkbox" type="checkbox" name="image_urls[]" value="<?php echo e($imageUrl); ?>" id="image_other_<?php echo e($index); ?>">
                                                            <label class="form-check-label" for="image_other_<?php echo e($index); ?>">
                                                                Выбрать
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                
                                <?php if(empty($parseResult['image_urls'])): ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i> Изображения не найдены.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="<?php echo e(route('admin.parser.index')); ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Отмена
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Сохранить рецепт
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Кнопка выбора всех изображений
        document.querySelector('.select-all-images').addEventListener('click', function() {
            document.querySelectorAll('.image-checkbox').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });
        
        // Кнопка отмены выбора всех изображений
        document.querySelector('.unselect-all-images').addEventListener('click', function() {
            document.querySelectorAll('.image-checkbox').forEach(function(checkbox, index) {
                // Оставляем главное изображение выбранным
                if (checkbox.closest('.recipe-image') && index === 0) {
                    checkbox.checked = true;
                } else {
                    checkbox.checked = false;
                }
            });
        });
        
        // Переключатель отображения только изображений рецепта
        document.getElementById('show-only-recipe-images').addEventListener('change', function() {
            const otherImages = document.querySelectorAll('.other-image');
            
            otherImages.forEach(function(image) {
                image.style.display = this.checked ? 'none' : 'block';
            }, this);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views\admin\parser\preview.blade.php ENDPATH**/ ?>