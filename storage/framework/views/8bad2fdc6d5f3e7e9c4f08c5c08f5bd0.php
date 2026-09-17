<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <?php if (isset($component)) { $__componentOriginal405c66773dfcb9f37dc65f464a3f4a10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal405c66773dfcb9f37dc65f464a3f4a10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.toolbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Item::class)): ?>
            <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'items.create','class' => 'be-btn be-btn--primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.create','class' => 'be-btn be-btn--primary']); ?>New <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
        <?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'focusSearch']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'focusSearch']); ?>Find <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selected): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $selected)): ?>
                <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'items.edit','params' => ['item' => $selected->id],'title' => 'Item: '.$selected->sku,'class' => 'be-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.edit','params' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['item' => $selected->id]),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Item: '.$selected->sku),'class' => 'be-btn']); ?>Edit <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Item::class)): ?>
                <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['wire:click' => 'duplicateSelected']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'duplicateSelected']); ?>Duplicate <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $selected)): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selected->is_active): ?>
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['wire:click' => 'deactivateSelected','wire:confirm' => 'Deactivate this item?']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'deactivateSelected','wire:confirm' => 'Deactivate this item?']); ?>Make Inactive <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['wire:click' => 'activateSelected']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'activateSelected']); ?>Make Active <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $selected)): ?>
                <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['variant' => 'danger','wire:click' => 'deleteSelected','wire:confirm' => 'Permanently delete this item?']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger','wire:click' => 'deleteSelected','wire:confirm' => 'Permanently delete this item?']); ?>Delete <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'lookups.index','class' => 'be-btn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'lookups.index','class' => 'be-btn']); ?>Lookups <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','onclick' => 'window.print()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','onclick' => 'window.print()']); ?>Print <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
        <span class="ml-auto text-[11px] text-gray-500"><?php echo e($items->total()); ?> items</span>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal405c66773dfcb9f37dc65f464a3f4a10)): ?>
<?php $attributes = $__attributesOriginal405c66773dfcb9f37dc65f464a3f4a10; ?>
<?php unset($__attributesOriginal405c66773dfcb9f37dc65f464a3f4a10); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal405c66773dfcb9f37dc65f464a3f4a10)): ?>
<?php $component = $__componentOriginal405c66773dfcb9f37dc65f464a3f4a10; ?>
<?php unset($__componentOriginal405c66773dfcb9f37dc65f464a3f4a10); ?>
<?php endif; ?>

    <div class="be-panel m-0 rounded-none border-0 border-t" style="border-color: var(--be-border);">
        <div class="be-panel__body">
            <div class="mb-2 flex flex-wrap items-end gap-2">
                <div class="be-field">
                    <label class="be-field__label">Look for</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['xRef' => 'listSearch','wire:model' => 'search','class' => 'w-56','wire:keydown.enter' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-ref' => 'listSearch','wire:model' => 'search','class' => 'w-56','wire:keydown.enter' => 'search']); ?>
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
                    <label class="be-field__label">Search in</label>
                    <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model' => 'searchField','options' => [
                            'all' => 'All fields',
                            'sku' => 'SKU / Name',
                            'name' => 'Name',
                            'description' => 'Description',
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'searchField','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'all' => 'All fields',
                            'sku' => 'SKU / Name',
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
                </div>
                <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['variant' => 'primary','wire:click' => 'search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','wire:click' => 'search']); ?>Search <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['wire:click' => 'resetSearch']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'resetSearch']); ?>Reset <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                <label class="inline-flex items-center gap-1 text-[11px]">
                    <input type="checkbox" wire:model="searchWithinResults"> Search within results
                </label>
                <label class="inline-flex items-center gap-1 text-[11px]">
                    <input type="checkbox" wire:model.live="includeInactive"> Include inactive
                </label>
            </div>

            <div class="be-datatable-wrap" style="max-height: calc(100vh - 300px);">
                <table class="be-table be-table--line-select be-table--blue-select">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>TYPE</th>
                            <th>ITEM TYPE</th>
                            <th class="text-right">TOTAL QTY ON HAND</th>
                            <th class="text-right">PRICE</th>
                            <th class="text-right">COST</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr
                                wire:key="item-<?php echo e($item->id); ?>"
                                wire:click="selectItem(<?php echo e($item->id); ?>)"
                                class="cursor-pointer <?php echo e($selectedId === $item->id ? 'is-selected' : ''); ?>"
                                @dblclick="window.location.assign(<?php echo \Illuminate\Support\Js::from(route('items.edit', $item))->toHtml() ?>)"
                            >
                                <td>
                                    <?php echo e($item->sku); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($item->is_active)): ?>
                                        <span class="be-badge">Inactive</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td><?php echo e($item->sales_description ?: $item->name); ?></td>
                                <td><?php echo e($item->type ?: 'Inventory Part'); ?></td>
                                <td><?php echo e($item->itemType?->label ?? '—'); ?></td>
                                <td class="num <?php echo e((float) $item->on_hand < 0 ? 'text-red-700' : ''); ?>">
                                    <?php echo e(number_format((float) $item->on_hand, 0)); ?>

                                </td>
                                <td class="num"><?php echo e(number_format((float) $item->sales_price, 2)); ?></td>
                                <td class="num"><?php echo e(number_format((float) $item->purchase_cost, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7">
                                    <?php if (isset($component)) { $__componentOriginal93f05f5416e760f97131cc8c9b752903 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93f05f5416e760f97131cc8c9b752903 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.empty-state','data' => ['title' => 'No items','message' => 'Create an item to get started.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No items','message' => 'Create an item to get started.']); ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Item::class)): ?>
                                            <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'items.create','class' => 'be-btn be-btn--primary mt-2 inline-flex']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.create','class' => 'be-btn be-btn--primary mt-2 inline-flex']); ?>New Item <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
                                        <?php endif; ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93f05f5416e760f97131cc8c9b752903)): ?>
<?php $attributes = $__attributesOriginal93f05f5416e760f97131cc8c9b752903; ?>
<?php unset($__attributesOriginal93f05f5416e760f97131cc8c9b752903); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93f05f5416e760f97131cc8c9b752903)): ?>
<?php $component = $__componentOriginal93f05f5416e760f97131cc8c9b752903; ?>
<?php unset($__componentOriginal93f05f5416e760f97131cc8c9b752903); ?>
<?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="be-list-footer">
                <div class="relative" x-data="{ open: false }">
                    <button type="button" class="be-btn" @click="open = !open">Item ▾</button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute bottom-full left-0 z-20 mb-1 min-w-[160px] border bg-white shadow" style="border-color: var(--be-border-dark);">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Item::class)): ?>
                            <?php if (isset($component)) { $__componentOriginal5abd15ccddcad372df58dab78ed00d60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5abd15ccddcad372df58dab78ed00d60 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.workspace-link','data' => ['route' => 'items.create','class' => 'block px-3 py-1.5 text-[12px] hover:bg-sky-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.workspace-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'items.create','class' => 'block px-3 py-1.5 text-[12px] hover:bg-sky-50']); ?>New <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $attributes = $__attributesOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__attributesOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5abd15ccddcad372df58dab78ed00d60)): ?>
<?php $component = $__componentOriginal5abd15ccddcad372df58dab78ed00d60; ?>
<?php unset($__componentOriginal5abd15ccddcad372df58dab78ed00d60); ?>
<?php endif; ?>
                        <?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selected): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $selected)): ?>
                                <a href="<?php echo e(route('items.edit', $selected)); ?>" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Edit</a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Item::class)): ?>
                                <button type="button" class="block w-full px-3 py-1.5 text-left text-[12px] hover:bg-sky-50" wire:click="duplicateSelected" @click="open = false">Duplicate</button>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $selected)): ?>
                                <button type="button" class="block w-full px-3 py-1.5 text-left text-[12px] text-red-700 hover:bg-sky-50" wire:click="deleteSelected" wire:confirm="Permanently delete this item?" @click="open = false">Delete</button>
                            <?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button type="button" class="be-btn" @click="open = !open">Activities ▾</button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute bottom-full left-0 z-20 mb-1 min-w-[180px] border bg-white shadow" style="border-color: var(--be-border-dark);">
                        <a href="<?php echo e(route('inventory.adjustments')); ?>" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Adjust Quantity</a>
                        <a href="<?php echo e(route('inventory.index')); ?>" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Inventory Center</a>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button type="button" class="be-btn" @click="open = !open">Reports ▾</button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute bottom-full left-0 z-20 mb-1 min-w-[180px] border bg-white shadow" style="border-color: var(--be-border-dark);">
                        <a href="<?php echo e(route('reports.inventory')); ?>" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Inventory / Stock</a>
                        <a href="<?php echo e(route('reports.sales-by-item')); ?>" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Sales by Item</a>
                    </div>
                </div>
                <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'exportExcel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'exportExcel']); ?>Excel <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','disabled' => true,'title' => 'Coming soon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','disabled' => true,'title' => 'Coming soon']); ?>Attach <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                <label class="ml-auto inline-flex items-center gap-1 text-[11px]">
                    <input type="checkbox" wire:model.live="includeInactive"> Include inactive
                </label>
            </div>

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
        </div>
    </div>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\items\item-list.blade.php ENDPATH**/ ?>