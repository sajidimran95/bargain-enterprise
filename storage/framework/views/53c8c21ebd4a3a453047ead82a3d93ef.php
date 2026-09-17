<div class="be-page">
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
        <?php if (isset($component)) { $__componentOriginal1533802996c09e398453c7a7b321bf25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1533802996c09e398453c7a7b321bf25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.button','data' => ['type' => 'button','variant' => 'primary','wire:click' => 'save']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','wire:click' => 'save']); ?>Save <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $attributes = $__attributesOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__attributesOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1533802996c09e398453c7a7b321bf25)): ?>
<?php $component = $__componentOriginal1533802996c09e398453c7a7b321bf25; ?>
<?php unset($__componentOriginal1533802996c09e398453c7a7b321bf25); ?>
<?php endif; ?>
        <a href="<?php echo e(route('banking.index')); ?>" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">New Bank Account</span>
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

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">New Bank Account</h1>
        </div>
        <div class="be-panel__body">
            <div class="grid gap-3 md:grid-cols-2">
                <div>
                    <label class="be-label">Account Name *</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'name']); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="be-label">Bank Name *</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'bank_name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'bank_name']); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="be-label">Account # Mask</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'account_number_mask','placeholder' => '****1234']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'account_number_mask','placeholder' => '****1234']); ?>
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
                <div>
                    <label class="be-label">Opening Balance</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['type' => 'number','step' => '0.01','wire:model' => 'opening_balance']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','step' => '0.01','wire:model' => 'opening_balance']); ?>
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
                <div>
                    <label class="be-label">GL Number *</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'gl_number']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'gl_number']); ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gl_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="be-label">GL Name *</label>
                    <?php if (isset($component)) { $__componentOriginal5ffc033813591e85e4508e27a2ee1612 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ffc033813591e85e4508e27a2ee1612 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.input','data' => ['wire:model' => 'gl_name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'gl_name']); ?>
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
            </div>
        </div>
    </div>
</div><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\banking\bank-account-form.blade.php ENDPATH**/ ?>