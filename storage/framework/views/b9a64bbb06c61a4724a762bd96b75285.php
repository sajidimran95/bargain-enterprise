<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['entry']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['entry']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($entry['separator'])): ?>
    <div class="be-menubar__sep" role="separator"></div>
<?php elseif(! empty($entry['children'])): ?>
    <div class="be-menubar__submenu" role="none">
        <button type="button" class="be-menubar__link be-menubar__link--flyout" role="menuitem" aria-haspopup="true">
            <span class="be-menubar__link-label"><?php echo e($entry['label']); ?></span>
            <span class="be-menubar__chevron" aria-hidden="true">▸</span>
        </button>
        <div class="be-menubar__flyout" role="menu">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $entry['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.menubar-entry','data' => ['entry' => $child]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.menubar-entry'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entry' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9)): ?>
<?php $attributes = $__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9; ?>
<?php unset($__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9)): ?>
<?php $component = $__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9; ?>
<?php unset($__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php elseif(! empty($entry['disabled'])): ?>
    <span class="be-menubar__link be-menubar__link--disabled" role="menuitem" aria-disabled="true">
        <span class="be-menubar__link-label"><?php echo e($entry['label']); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($entry['shortcut'])): ?>
            <span class="be-menubar__shortcut"><?php echo e($entry['shortcut']); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </span>
<?php elseif(! empty($entry['action'])): ?>
    <button
        type="button"
        class="be-menubar__link"
        role="menuitem"
        @click="handleMenuAction(<?php echo \Illuminate\Support\Js::from($entry)->toHtml() ?>); open = null"
    >
        <span class="be-menubar__link-label"><?php echo e($entry['label']); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($entry['shortcut'])): ?>
            <span class="be-menubar__shortcut"><?php echo e($entry['shortcut']); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </button>
<?php else: ?>
    <a
        href="<?php echo e(route('dashboard', ['open' => $entry['route']])); ?>"
        class="be-menubar__link"
        role="menuitem"
        @click.prevent="beOpenWorkspace(<?php echo \Illuminate\Support\Js::from($entry['route'])->toHtml() ?>); open = null"
    >
        <span class="be-menubar__link-label"><?php echo e($entry['label']); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($entry['shortcut'])): ?>
            <span class="be-menubar__shortcut"><?php echo e($entry['shortcut']); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/components/erp/menubar-entry.blade.php ENDPATH**/ ?>