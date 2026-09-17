<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <?php if (isset($component)) { $__componentOriginal509c24698bcbab74c89817eaea4789e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal509c24698bcbab74c89817eaea4789e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.list-toolbar','data' => ['heading' => 'Quote List','newRoute' => 'quotes.create','newLabel' => 'New Quote','title' => $quotes->total().' quotes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Quote List','new-route' => 'quotes.create','new-label' => 'New Quote','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($quotes->total().' quotes')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal509c24698bcbab74c89817eaea4789e6)): ?>
<?php $attributes = $__attributesOriginal509c24698bcbab74c89817eaea4789e6; ?>
<?php unset($__attributesOriginal509c24698bcbab74c89817eaea4789e6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal509c24698bcbab74c89817eaea4789e6)): ?>
<?php $component = $__componentOriginal509c24698bcbab74c89817eaea4789e6; ?>
<?php unset($__componentOriginal509c24698bcbab74c89817eaea4789e6); ?>
<?php endif; ?>

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <?php if (isset($component)) { $__componentOriginal4068b7ac54131b49d25f3c3cff69db88 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4068b7ac54131b49d25f3c3cff69db88 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.look-for','data' => ['placeholder' => 'Quote # / customer…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.look-for'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Quote # / customer…']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4068b7ac54131b49d25f3c3cff69db88)): ?>
<?php $attributes = $__attributesOriginal4068b7ac54131b49d25f3c3cff69db88; ?>
<?php unset($__attributesOriginal4068b7ac54131b49d25f3c3cff69db88); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4068b7ac54131b49d25f3c3cff69db88)): ?>
<?php $component = $__componentOriginal4068b7ac54131b49d25f3c3cff69db88; ?>
<?php unset($__componentOriginal4068b7ac54131b49d25f3c3cff69db88); ?>
<?php endif; ?>
            <div class="be-field mb-2">
                <label class="be-field__label">Status</label>
                <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'status','options' => [
                        'all' => 'All statuses',
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'converted' => 'Converted',
                    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'all' => 'All statuses',
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'converted' => 'Converted',
                    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $attributes = $__attributesOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__attributesOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal847fd48de2d422593186c84a70c7291b)): ?>
<?php $component = $__componentOriginal847fd48de2d422593186c84a70c7291b; ?>
<?php unset($__componentOriginal847fd48de2d422593186c84a70c7291b); ?>
<?php endif; ?>
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            wire:key="qt-<?php echo e($quote->id); ?>"
                            wire:click="selectLine(<?php echo e($quote->id); ?>)"
                            class="<?php echo e($selectedLineId === $quote->id ? 'is-selected' : ''); ?>"
                        >
                            <td><?php echo e($quote->quote_date?->format('m/d/Y')); ?></td>
                            <td><?php echo e($quote->number); ?></td>
                            <td><?php echo e($quote->customer?->display_name); ?></td>
                            <td><span class="be-badge"><?php echo e($quote->status); ?></span></td>
                            <td class="num"><?php echo e($quote->lines_count); ?></td>
                            <td class="num"><?php echo e(number_format((float) $quote->total, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6"><?php if (isset($component)) { $__componentOriginal93f05f5416e760f97131cc8c9b752903 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93f05f5416e760f97131cc8c9b752903 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.empty-state','data' => ['title' => 'No quotes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No quotes']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93f05f5416e760f97131cc8c9b752903)): ?>
<?php $attributes = $__attributesOriginal93f05f5416e760f97131cc8c9b752903; ?>
<?php unset($__attributesOriginal93f05f5416e760f97131cc8c9b752903); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93f05f5416e760f97131cc8c9b752903)): ?>
<?php $component = $__componentOriginal93f05f5416e760f97131cc8c9b752903; ?>
<?php unset($__componentOriginal93f05f5416e760f97131cc8c9b752903); ?>
<?php endif; ?></td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php if (isset($component)) { $__componentOriginal81d0caf1c8074ca6113817ce4cf3aeb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81d0caf1c8074ca6113817ce4cf3aeb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.pagination','data' => ['paginator' => $quotes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($quotes)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal81d0caf1c8074ca6113817ce4cf3aeb7)): ?>
<?php $attributes = $__attributesOriginal81d0caf1c8074ca6113817ce4cf3aeb7; ?>
<?php unset($__attributesOriginal81d0caf1c8074ca6113817ce4cf3aeb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal81d0caf1c8074ca6113817ce4cf3aeb7)): ?>
<?php $component = $__componentOriginal81d0caf1c8074ca6113817ce4cf3aeb7; ?>
<?php unset($__componentOriginal81d0caf1c8074ca6113817ce4cf3aeb7); ?>
<?php endif; ?>
        </div>
    </div>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\sales\quote-index.blade.php ENDPATH**/ ?>