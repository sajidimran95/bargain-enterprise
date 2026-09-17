<?php if (isset($component)) { $__componentOriginal1a5737427d04f5e63c498f38666d4996 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a5737427d04f5e63c498f38666d4996 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.report-shell','data' => ['title' => 'MSA Customer List','showDates' => false,'showFilterBar' => true,'hideHeader' => $hideHeader,'showExtraFilters' => $showExtraFilters,'sortBy' => $sortBy,'sortByOptions' => $sortByOptions,'showEmailModal' => $showEmailModal,'showCommentModal' => $showCommentModal,'showShareModal' => $showShareModal,'showMemorizeModal' => $showMemorizeModal,'reportComment' => $reportComment,'shareUrl' => $shareUrl,'memorizeName' => $memorizeName,'emailSubject' => $emailSubject]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.report-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'MSA Customer List','show-dates' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'show-filter-bar' => true,'hide-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeader),'show-extra-filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showExtraFilters),'sort-by' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortBy),'sort-by-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortByOptions),'show-email-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailModal),'show-comment-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showCommentModal),'show-share-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showShareModal),'show-memorize-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showMemorizeModal),'report-comment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportComment),'share-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($shareUrl),'memorize-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($memorizeName),'email-subject' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emailSubject)]); ?>
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

     <?php $__env->slot('filters', null, []); ?> 
        <div class="flex flex-wrap items-end gap-3">
            <div class="be-field">
                <label class="be-field__label">Look for</label>
                <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model.live.debounce.300ms' => 'search','placeholder' => 'Customer / phone / city…','class' => 'w-64']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','placeholder' => 'Customer / phone / city…','class' => 'w-64']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $attributes = $__attributesOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__attributesOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ffc033813591e85e4508e27a2ee1612)): ?>
<?php $component = $__componentOriginal5ffc033813591e85e4508e27a2ee1612; ?>
<?php unset($__componentOriginal5ffc033813591e85e4508e27a2ee1612); ?>
<?php endif; ?>
            </div>
            <div class="be-field">
                <label class="be-field__label">Status</label>
                <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'status','options' => [
                        'active' => 'Active Customers',
                        'inactive' => 'Inactive Customers',
                        'all' => 'All Customers',
                    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'active' => 'Active Customers',
                        'inactive' => 'Inactive Customers',
                        'all' => 'All Customers',
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
        </div>
     <?php $__env->endSlot(); ?>

    <table class="be-report-table be-table be-table--line-select be-report-table--wide">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Primary Contact</th>
                <th>Work Phone</th>
                <th>Fax</th>
                <th>Street1</th>
                <th>Street2</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null }">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr
                    wire:key="cust-dir-<?php echo e($customer->id); ?>"
                    @click="selectedLine = 'cust-<?php echo e($customer->id); ?>'"
                    :class="selectedLine === 'cust-<?php echo e($customer->id); ?>' ? 'is-selected' : ''"
                >
                    <td><?php echo e($customer->customer_number ? $customer->customer_number.' ('.$customer->display_name.')' : $customer->display_name); ?></td>
                    <td><?php echo e($customer->fullName()); ?></td>
                    <td><?php echo e($customer->phone); ?></td>
                    <td><?php echo e($customer->fax); ?></td>
                    <td><?php echo e($customer->bill_to_street1); ?></td>
                    <td><?php echo e($customer->bill_to_street2); ?></td>
                    <td><?php echo e($customer->bill_to_city); ?></td>
                    <td><?php echo e($customer->bill_to_state); ?></td>
                    <td><?php echo e($customer->bill_to_zip); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9">No customers.</td></tr>
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
<?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/reports/customer-directory-report.blade.php ENDPATH**/ ?>