<?php if (isset($component)) { $__componentOriginal1a5737427d04f5e63c498f38666d4996 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a5737427d04f5e63c498f38666d4996 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.report-shell','data' => ['title' => 'Customer Open Balance','subtitle' => $subtitle,'showBasis' => true,'basis' => $basis,'hideHeader' => $hideHeader,'showExtraFilters' => $showExtraFilters,'sortBy' => $sortBy,'datePresetOptions' => $datePresetOptions,'sortByOptions' => $sortByOptions,'showEmailModal' => $showEmailModal,'showCommentModal' => $showCommentModal,'showShareModal' => $showShareModal,'showMemorizeModal' => $showMemorizeModal,'reportComment' => $reportComment,'shareUrl' => $shareUrl,'memorizeName' => $memorizeName,'emailSubject' => $emailSubject]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.report-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Customer Open Balance','subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subtitle),'show-basis' => true,'basis' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($basis),'hide-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeader),'show-extra-filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showExtraFilters),'sort-by' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortBy),'date-preset-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($datePresetOptions),'sort-by-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortByOptions),'show-email-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailModal),'show-comment-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showCommentModal),'show-share-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showShareModal),'show-memorize-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showMemorizeModal),'report-comment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportComment),'share-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($shareUrl),'memorize-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($memorizeName),'email-subject' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emailSubject)]); ?>
     <?php $__env->slot('excel', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'exportExcel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'exportExcel']); ?>Excel ▾ <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>

    <table class="be-report-table be-table be-table--line-select">
        <thead>
            <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Num</th>
                <th>Memo</th>
                <th>Due Date</th>
                <th class="num">Open Balance</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null, collapsed: {} }">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php ($groupKey = 'g-'.$loop->index); ?>
                <tr
                    class="be-report-table__group"
                    @click="collapsed['<?php echo e($groupKey); ?>'] = !collapsed['<?php echo e($groupKey); ?>']"
                >
                    <td colspan="7">
                        <span
                            class="be-report-table__group-toggle"
                            x-text="collapsed['<?php echo e($groupKey); ?>'] ? '▶' : '▼'"
                        ></span>
                        <?php echo e($group['customer_label']); ?>

                    </td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['invoices']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr
                        x-show="!collapsed['<?php echo e($groupKey); ?>']"
                        @click="selectedLine = 'inv-<?php echo e($invoice->id); ?>'"
                        :class="selectedLine === 'inv-<?php echo e($invoice->id); ?>' ? 'is-selected' : ''"
                    >
                        <td>Invoice</td>
                        <td><?php echo e($invoice->invoice_date?->format('m/d/Y')); ?></td>
                        <td><?php echo e($invoice->invoice_number); ?></td>
                        <td><?php echo e($invoice->memo); ?></td>
                        <td><?php echo e($invoice->due_date?->format('m/d/Y')); ?></td>
                        <td class="num"><?php echo e(number_format((float) $invoice->balance_due, 2)); ?></td>
                        <td class="num"><?php echo e(number_format((float) $invoice->total, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <tr class="be-report-table__subtotal" x-show="!collapsed['<?php echo e($groupKey); ?>']">
                    <td colspan="5" class="text-right">Total <?php echo e($group['customer_label']); ?></td>
                    <td class="num"><?php echo e(number_format((float) $group['open_balance'], 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $group['amount'], 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7">No open balances in this date range.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($groups->isNotEmpty()): ?>
                <tr class="be-report-table__total">
                    <td colspan="5" class="text-right">TOTAL</td>
                    <td class="num"><?php echo e(number_format((float) $grandOpen, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $grandAmount, 2)); ?></td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a5737427d04f5e63c498f38666d4996)): ?>
<?php $attributes = $__attributesOriginal1a5737427d04f5e63c498f38666d4996; ?>
<?php unset($__attributesOriginal1a5737427d04f5e63c498f38666d4996); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a5737427d04f5e63c498f38666d4996)): ?>
<?php $component = $__componentOriginal1a5737427d04f5e63c498f38666d4996; ?>
<?php unset($__componentOriginal1a5737427d04f5e63c498f38666d4996); ?>
<?php endif; ?><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\reports\customer-open-balance-report.blade.php ENDPATH**/ ?>