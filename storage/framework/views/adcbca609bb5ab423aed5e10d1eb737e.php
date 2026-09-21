<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <?php if (isset($component)) { $__componentOriginal509c24698bcbab74c89817eaea4789e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal509c24698bcbab74c89817eaea4789e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.list-toolbar','data' => ['heading' => 'Vendor Bill List','newRoute' => 'vendor-bills.create','newLabel' => 'Enter Bills','title' => 'Open AP: '.number_format((float) $openAp, 2).' · '.$bills->total().' bills']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Vendor Bill List','new-route' => 'vendor-bills.create','new-label' => 'Enter Bills','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Open AP: '.number_format((float) $openAp, 2).' · '.$bills->total().' bills')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.look-for','data' => ['placeholder' => 'Bill # / vendor…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.look-for'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Bill # / vendor…']); ?>
                <div class="be-field">
                    <label class="be-field__label">Status</label>
                    <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'status','options' => [
                            'all' => 'All statuses',
                            'open' => 'Open',
                            'partial' => 'Partial',
                            'paid' => 'Paid',
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'all' => 'All statuses',
                            'open' => 'Open',
                            'partial' => 'Partial',
                            'paid' => 'Paid',
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

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bill #</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Balance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isRtv = str_starts_with((string) $bill->bill_number, 'RTV-')
                                || str_contains((string) ($bill->memo ?? ''), 'CREDIT');
                            $editRoute = $isRtv ? 'vendor-returns.edit' : 'vendor-bills.edit';
                            $editTitle = ($isRtv ? 'RTV: ' : 'Bill: ').$bill->bill_number;
                        ?>
                        <tr
                            wire:key="vb-<?php echo e($bill->id); ?>"
                            wire:click="selectLine(<?php echo e($bill->id); ?>)"
                            wire:dblclick="openEdit(<?php echo e($bill->id); ?>)"
                            class="<?php echo e($selectedLineId === $bill->id ? 'is-selected' : ''); ?> cursor-pointer"
                        >
                            <td><?php echo e($bill->bill_date?->format('m/d/Y')); ?></td>
                            <td><?php echo e($bill->bill_number); ?></td>
                            <td><?php echo e($bill->vendor?->display_name); ?></td>
                            <td><span class="be-badge"><?php echo e($bill->status); ?></span></td>
                            <td class="num"><?php echo e($bill->lines_count); ?></td>
                            <td class="num"><?php echo e(number_format((float) $bill->total, 2)); ?></td>
                            <td class="num"><?php echo e(number_format((float) $bill->balance_due, 2)); ?></td>
                            <td class="whitespace-nowrap">
                                <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => $editRoute,'params' => ['vendorBill' => $bill->id],'title' => $editTitle,'class' => 'be-link-btn','@click.stop' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($editRoute),'params' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['vendorBill' => $bill->id]),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($editTitle),'class' => 'be-link-btn','@click.stop' => true]); ?>Edit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
                                <a href="<?php echo e(route('vendor-bills.pdf', $bill)); ?>" class="be-link-btn" target="_blank" @click.stop>PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8"><?php if (isset($component)) { $__componentOriginal93f05f5416e760f97131cc8c9b752903 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93f05f5416e760f97131cc8c9b752903 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.empty-state','data' => ['title' => 'No vendor bills']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No vendor bills']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.pagination','data' => ['paginator' => $bills]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bills)]); ?>
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
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/purchasing/vendor-bill-index.blade.php ENDPATH**/ ?>