<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <?php if (isset($component)) { $__componentOriginal509c24698bcbab74c89817eaea4789e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal509c24698bcbab74c89817eaea4789e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.list-toolbar','data' => ['heading' => 'Inventory Stock Status','newRoute' => 'inventory.adjustments','newParams' => ['new' => 1],'newLabel' => 'New Adjustment','title' => $items->total().' SKUs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Inventory Stock Status','new-route' => 'inventory.adjustments','new-params' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['new' => 1]),'new-label' => 'New Adjustment','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($items->total().' SKUs')]); ?>
        <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'items.index','class' => 'be-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.index','class' => 'be-btn']); ?>Item List <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'reports.inventory','class' => 'be-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'reports.inventory','class' => 'be-btn']); ?>MSA Inventory <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.look-for','data' => ['placeholder' => 'SKU / name…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.look-for'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'SKU / name…']); ?>
                <div class="be-field">
                    <label class="be-field__label">Stock</label>
                    <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'stock','options' => [
                            'all' => 'All stock',
                            'low' => 'At/below reorder',
                            'zero' => 'Zero on hand',
                            'negative' => 'Negative on hand',
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'stock','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'all' => 'All stock',
                            'low' => 'At/below reorder',
                            'zero' => 'Zero on hand',
                            'negative' => 'Negative on hand',
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
                        <th>NAME</th>
                        <th>DESCRIPTION</th>
                        <th>TYPE</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">PRICE</th>
                        <th class="text-right">COST</th>
                        <th class="text-right">REORDER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr
                            wire:key="inv-<?php echo e($item->id); ?>"
                            wire:click="selectLine(<?php echo e($item->id); ?>)"
                            class="<?php echo e($selectedLineId === $item->id ? 'is-selected' : ''); ?>"
                        >
                            <td><?php echo e($item->sku); ?></td>
                            <td><?php echo e($item->sales_description ?: $item->name); ?></td>
                            <td><?php echo e($item->type ?: $item->category?->code); ?></td>
                            <td class="num <?php echo e((float) $item->on_hand < 0 ? 'text-red-700' : ''); ?>"><?php echo e(number_format((float) $item->on_hand, 2)); ?></td>
                            <td class="num"><?php echo e(number_format((float) $item->sales_price, 2)); ?></td>
                            <td class="num"><?php echo e(number_format((float) ($item->average_cost ?: 0), 4)); ?></td>
                            <td class="num"><?php echo e(number_format((float) ($item->reorder_min ?? 0), 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php if (isset($component)) { $__componentOriginal81d0caf1c8074ca6113817ce4cf3aeb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal81d0caf1c8074ca6113817ce4cf3aeb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.pagination','data' => ['paginator' => $items]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($items)]); ?>
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

            <p class="be-section-title">Recent Inventory Movements</p>
            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>SKU</th>
                        <th>Type</th>
                        <th class="text-right">In</th>
                        <th class="text-right">Out</th>
                        <th class="text-right">Balance</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr
                            wire:key="tx-<?php echo e($tx->id); ?>"
                            wire:click="selectLine(<?php echo e($tx->id); ?>)"
                            class="<?php echo e($selectedLineId === $tx->id ? 'is-selected' : ''); ?>"
                        >
                            <td><?php echo e(optional($tx->occurred_at)->format('m/d/Y')); ?></td>
                            <td><?php echo e($tx->item?->sku); ?></td>
                            <td><?php echo e($tx->type); ?></td>
                            <td class="num"><?php echo e(number_format((float) $tx->qty_in, 2)); ?></td>
                            <td class="num"><?php echo e(number_format((float) $tx->qty_out, 2)); ?></td>
                            <td class="num"><?php echo e(number_format((float) $tx->balance_after, 2)); ?></td>
                            <td><?php echo e($tx->memo); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\inventory\inventory-index.blade.php ENDPATH**/ ?>