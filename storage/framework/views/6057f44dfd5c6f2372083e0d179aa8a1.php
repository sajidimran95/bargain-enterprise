<?php if (isset($component)) { $__componentOriginal1a5737427d04f5e63c498f38666d4996 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a5737427d04f5e63c498f38666d4996 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.report-shell','data' => ['title' => 'Profit & Loss','subtitle' => $subtitle,'showBasis' => true,'basis' => $basis,'hideHeader' => $hideHeader,'showExtraFilters' => $showExtraFilters,'sortBy' => $sortBy,'datePresetOptions' => $datePresetOptions,'sortByOptions' => $sortByOptions,'showEmailModal' => $showEmailModal,'showCommentModal' => $showCommentModal,'showShareModal' => $showShareModal,'showMemorizeModal' => $showMemorizeModal,'reportComment' => $reportComment,'shareUrl' => $shareUrl,'memorizeName' => $memorizeName,'emailSubject' => $emailSubject]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.report-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Profit & Loss','subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subtitle),'show-basis' => true,'basis' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($basis),'hide-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeader),'show-extra-filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showExtraFilters),'sort-by' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortBy),'date-preset-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($datePresetOptions),'sort-by-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortByOptions),'show-email-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailModal),'show-comment-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showCommentModal),'show-share-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showShareModal),'show-memorize-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showMemorizeModal),'report-comment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportComment),'share-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($shareUrl),'memorize-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($memorizeName),'email-subject' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emailSubject)]); ?>
     <?php $__env->slot('excel', null, []); ?> <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
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
<?php endif; ?> <?php $__env->endSlot(); ?>
    <table class="be-report-table be-table">
        <tbody>
            <tr class="be-report-table__group"><td colspan="2">Income</td></tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $statement['income_accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr><td><?php echo e($row['name']); ?></td><td class="num"><?php echo e(number_format($row['amount'], 2)); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <tr class="be-report-table__subtotal"><td>Total Income</td><td class="num"><?php echo e(number_format($statement['income'], 2)); ?></td></tr>
            <tr class="be-report-table__group"><td colspan="2">Cost of Goods Sold</td></tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $statement['expense_accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr><td><?php echo e($row['name']); ?></td><td class="num"><?php echo e(number_format($row['amount'], 2)); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <tr class="be-report-table__subtotal"><td>Total COGS</td><td class="num"><?php echo e(number_format($statement['cogs'], 2)); ?></td></tr>
            <tr class="be-report-table__total"><td>Gross Profit</td><td class="num"><?php echo e(number_format($statement['gross'], 2)); ?></td></tr>
            <tr class="be-report-table__total"><td>Net Income</td><td class="num"><?php echo e(number_format($statement['net'], 2)); ?></td></tr>
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
<?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/reports/profit-loss-report.blade.php ENDPATH**/ ?>