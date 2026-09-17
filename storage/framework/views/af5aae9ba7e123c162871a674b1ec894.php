<?php if (isset($component)) { $__componentOriginal1a5737427d04f5e63c498f38666d4996 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a5737427d04f5e63c498f38666d4996 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.report-shell','data' => ['title' => 'MSA Inventory','showDates' => false,'showFilterBar' => true,'hideHeader' => $hideHeader,'showExtraFilters' => $showExtraFilters,'sortBy' => $sortBy,'sortByOptions' => $sortByOptions,'showEmailModal' => $showEmailModal,'showCommentModal' => $showCommentModal,'showShareModal' => $showShareModal,'showMemorizeModal' => $showMemorizeModal,'reportComment' => $reportComment,'shareUrl' => $shareUrl,'memorizeName' => $memorizeName,'emailSubject' => $emailSubject]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.report-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'MSA Inventory','show-dates' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'show-filter-bar' => true,'hide-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($hideHeader),'show-extra-filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showExtraFilters),'sort-by' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortBy),'sort-by-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortByOptions),'show-email-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showEmailModal),'show-comment-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showCommentModal),'show-share-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showShareModal),'show-memorize-modal' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showMemorizeModal),'report-comment' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reportComment),'share-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($shareUrl),'memorize-name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($memorizeName),'email-subject' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emailSubject)]); ?>
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
        <div class="be-stock-search">
            <label class="be-stock-search__label">Look for</label>
            <input
                type="text"
                class="be-input be-scan-input be-stock-search__input"
                wire:model="search"
                wire:keydown.enter.prevent="runSearch"
                placeholder=""
                autocomplete="off"
                spellcheck="false"
            />
            <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model' => 'searchField','class' => 'be-stock-search__field','options' => [
                    'all' => 'All fields',
                    'sku' => 'Name / Barcode',
                    'name' => 'Name',
                    'description' => 'Description',
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'searchField','class' => 'be-stock-search__field','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    'all' => 'All fields',
                    'sku' => 'Name / Barcode',
                    'name' => 'Name',
                    'description' => 'Description',
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
            <button type="button" class="be-btn be-btn--primary" wire:click="runSearch">Search</button>
            <button type="button" class="be-btn" wire:click="resetSearch">Reset</button>
            <label class="be-stock-search__check">
                <input type="checkbox" wire:model="searchWithinResults"> Search within results
            </label>
            <label class="be-stock-search__check">
                <input type="checkbox" wire:model.live="includeInactive"> Include inactive
            </label>
        </div>
     <?php $__env->endSlot(); ?>

    <table class="be-report-table be-table be-table--line-select be-report-table--wide be-stock-table">
        <thead>
            <tr>
                <th class="be-stock-table__status" aria-label="Status"></th>
                <th>NAME</th>
                <th>DESCRIPTION</th>
                <th>TYPE</th>
                <th>ITEM TYPE</th>
                <th class="num">TOTAL QUANTITY ON HAND</th>
                <th class="num">PRICE</th>
                <th class="num">COST</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null }">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr
                    wire:key="inv-stock-<?php echo e($item->id); ?>"
                    @click="selectedLine = 'item-<?php echo e($item->id); ?>'"
                    :class="selectedLine === 'item-<?php echo e($item->id); ?>' ? 'is-selected' : ''"
                    class="<?php echo e($loop->even ? 'be-row-alt' : ''); ?>"
                >
                    <td class="be-stock-table__status">
                        <span class="be-stock-table__diamond <?php echo e($item->is_active ? '' : 'is-inactive'); ?>"></span>
                    </td>
                    <td class="font-mono"><?php echo e($item->barcode ?: $item->sku); ?></td>
                    <td><?php echo e($item->sales_description ?: $item->name); ?></td>
                    <td><?php echo e(str($item->type ?: 'inventory_part')->replace('_', ' ')->title()); ?></td>
                    <td><?php echo e($item->itemType?->label); ?></td>
                    <td class="num"><?php echo e(number_format((float) $item->on_hand, 0)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $item->sales_price, 2)); ?></td>
                    <td class="num"><?php echo e(number_format((float) $item->purchase_cost, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8">No inventory items.</td></tr>
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
<?php endif; ?><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\reports\inventory-stock-report.blade.php ENDPATH**/ ?>