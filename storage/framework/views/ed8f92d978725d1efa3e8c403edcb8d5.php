<?php if (isset($component)) { $__componentOriginal7ae24dd3260c517d5cdce34a251b72b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ae24dd3260c517d5cdce34a251b72b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.module-placeholder','data' => ['title' => $title,'windowTitle' => $title,'phase' => $phase ?? null,'description' => $description ?? 'This module will be implemented in a later phase. Layout and navigation are available now.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.module-placeholder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'window-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'phase' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($phase ?? null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($description ?? 'This module will be implemented in a later phase. Layout and navigation are available now.')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ae24dd3260c517d5cdce34a251b72b6)): ?>
<?php $attributes = $__attributesOriginal7ae24dd3260c517d5cdce34a251b72b6; ?>
<?php unset($__attributesOriginal7ae24dd3260c517d5cdce34a251b72b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ae24dd3260c517d5cdce34a251b72b6)): ?>
<?php $component = $__componentOriginal7ae24dd3260c517d5cdce34a251b72b6; ?>
<?php unset($__componentOriginal7ae24dd3260c517d5cdce34a251b72b6); ?>
<?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\placeholders\module.blade.php ENDPATH**/ ?>