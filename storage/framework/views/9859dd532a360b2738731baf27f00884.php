<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <?php if (isset($component)) { $__componentOriginal509c24698bcbab74c89817eaea4789e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal509c24698bcbab74c89817eaea4789e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.list-toolbar','data' => ['heading' => 'Invoice List','newRoute' => 'invoices.create','newLabel' => 'Create Invoices','title' => 'Open AR: '.number_format((float) $openBalance, 2).' · '.$invoices->total().' invoices']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Invoice List','new-route' => 'invoices.create','new-label' => 'Create Invoices','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Open AR: '.number_format((float) $openBalance, 2).' · '.$invoices->total().' invoices')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.look-for','data' => ['placeholder' => 'Invoice # / customer…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.look-for'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Invoice # / customer…']); ?>
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
                        'all' => 'All invoices',
                        'open' => 'Open',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'draft' => 'Draft',
                    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'all' => 'All invoices',
                        'open' => 'Open',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'draft' => 'Draft',
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
                        <th>Num</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Balance Due</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            wire:key="inv-<?php echo e($invoice->id); ?>"
                            wire:click="selectLine(<?php echo e($invoice->id); ?>)"
                            class="<?php echo e($selectedLineId === $invoice->id ? 'is-selected' : ''); ?>"
                        >
                            <td><?php echo e($invoice->invoice_date?->format('m/d/Y')); ?></td>
                            <td><?php echo e($invoice->invoice_number); ?></td>
                            <td><?php echo e($invoice->customer?->display_name); ?></td>
                            <td><span class="be-badge"><?php echo e($invoice->status); ?></span></td>
                            <td class="num"><?php echo e(number_format((float) $invoice->total, 2)); ?></td>
                            <td class="num"><?php echo e(number_format((float) $invoice->balance_due, 2)); ?></td>
                            <td class="whitespace-nowrap">
                                <a href="<?php echo e(route('invoices.pdf', $invoice)); ?>" class="be-link-btn" target="_blank" @click.stop>PDF</a>
                                <button type="button" class="be-link-btn" wire:click="openInvoiceEmail(<?php echo e($invoice->id); ?>)" @click.stop>Email</button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7">No invoices found.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>

            <div class="mt-3"><?php echo e($invoices->links()); ?></div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginalca3f3500bda03705c6d4369613317308 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalca3f3500bda03705c6d4369613317308 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.email-modal','data' => ['show' => $showEmailModal,'wireSend' => 'sendInvoiceEmail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.email-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailModal),'wire-send' => 'sendInvoiceEmail']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalca3f3500bda03705c6d4369613317308)): ?>
<?php $attributes = $__attributesOriginalca3f3500bda03705c6d4369613317308; ?>
<?php unset($__attributesOriginalca3f3500bda03705c6d4369613317308); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalca3f3500bda03705c6d4369613317308)): ?>
<?php $component = $__componentOriginalca3f3500bda03705c6d4369613317308; ?>
<?php unset($__componentOriginalca3f3500bda03705c6d4369613317308); ?>
<?php endif; ?>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/sales/invoice-index.blade.php ENDPATH**/ ?>