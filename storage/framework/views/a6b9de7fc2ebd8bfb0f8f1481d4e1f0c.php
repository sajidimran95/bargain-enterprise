<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <?php if (isset($component)) { $__componentOriginal509c24698bcbab74c89817eaea4789e6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal509c24698bcbab74c89817eaea4789e6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.list-toolbar','data' => ['heading' => 'Chart of Accounts','title' => $accounts->total().' accounts']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.list-toolbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => 'Chart of Accounts','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accounts->total().' accounts')]); ?>
         <?php $__env->slot('new', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'newAccount']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'newAccount']); ?>New <?php echo $__env->renderComponent(); ?>
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

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="be-entity-dialog be-entity-dialog--inline">
            <div class="be-entity-dialog__header">
                <h2 class="be-entity-dialog__title"><?php echo e($editingId ? 'Edit Account' : 'New Account'); ?></h2>
            </div>
            <div class="be-entity-dialog__body">
                <div class="be-form-grid">
                    <div class="be-field">
                        <label class="be-field__label">Number</label>
                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'form.number']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'form.number']); ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Name</label>
                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'form.name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'form.name']); ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Account Type</label>
                        <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model' => 'form.type','options' => [
                                'asset' => 'Asset',
                                'liability' => 'Liability',
                                'equity' => 'Equity',
                                'income' => 'Income',
                                'expense' => 'Expense',
                                'cogs' => 'COGS',
                            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'form.type','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                                'asset' => 'Asset',
                                'liability' => 'Liability',
                                'equity' => 'Equity',
                                'income' => 'Income',
                                'expense' => 'Expense',
                                'cogs' => 'COGS',
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Detail Type / Subtype</label>
                        <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'form.subtype','placeholder' => 'Optional']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'form.subtype','placeholder' => 'Optional']); ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.subtype'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="be-field__error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="be-field flex items-end">
                        <label class="inline-flex items-center gap-2 text-[12px]">
                            <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300">
                            Active
                        </label>
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'saveAccount']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'saveAccount']); ?>OK <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','wire:click' => 'cancelForm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'cancelForm']); ?>Cancel <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <?php if (isset($component)) { $__componentOriginal4068b7ac54131b49d25f3c3cff69db88 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4068b7ac54131b49d25f3c3cff69db88 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.look-for','data' => ['placeholder' => 'Account # / name…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.look-for'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Account # / name…']); ?>
                <div class="be-field">
                    <label class="be-field__label">Type</label>
                    <?php if (isset($component)) { $__componentOriginal847fd48de2d422593186c84a70c7291b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal847fd48de2d422593186c84a70c7291b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.select','data' => ['wire:model.live' => 'typeFilter','options' => [
                            'all' => 'All types',
                            'asset' => 'Asset',
                            'liability' => 'Liability',
                            'equity' => 'Equity',
                            'income' => 'Income',
                            'expense' => 'Expense',
                            'cogs' => 'COGS',
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'typeFilter','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'all' => 'All types',
                            'asset' => 'Asset',
                            'liability' => 'Liability',
                            'equity' => 'Equity',
                            'income' => 'Income',
                            'expense' => 'Expense',
                            'cogs' => 'COGS',
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
                        <th>TYPE</th>
                        <th>DETAIL TYPE</th>
                        <th>BALANCE</th>
                        <th>STATUS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            wire:key="acct-<?php echo e($account->id); ?>"
                            wire:click="selectLine(<?php echo e($account->id); ?>)"
                            class="<?php echo e($selectedLineId === $account->id ? 'is-selected' : ''); ?>"
                        >
                            <td>
                                <span class="font-mono text-[11px] text-gray-500"><?php echo e($account->number); ?></span>
                                <?php echo e($account->name); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($account->is_system): ?><span class="be-badge">System</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e(ucfirst($account->type)); ?></td>
                            <td><?php echo e($account->subtype ?: '—'); ?></td>
                            <td class="num text-gray-400">—</td>
                            <td><?php echo e($account->is_active ? 'Active' : 'Inactive'); ?></td>
                            <td class="text-right whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($account->is_system)): ?>
                                    <button type="button" class="be-link-btn" wire:click="editAccount(<?php echo e($account->id); ?>)" @click.stop>Edit</button>
                                    <button type="button" class="be-link-btn" wire:click="toggleActive(<?php echo e($account->id); ?>)" @click.stop>
                                        <?php echo e($account->is_active ? 'Make Inactive' : 'Make Active'); ?>

                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6"><?php if (isset($component)) { $__componentOriginal93f05f5416e760f97131cc8c9b752903 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93f05f5416e760f97131cc8c9b752903 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.empty-state','data' => ['title' => 'No accounts']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No accounts']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.pagination','data' => ['paginator' => $accounts]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accounts)]); ?>
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
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/accounting/chart-of-accounts.blade.php ENDPATH**/ ?>