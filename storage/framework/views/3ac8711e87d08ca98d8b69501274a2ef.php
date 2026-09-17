
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'windowTitle' => null,
    'description' => 'This module will be implemented in a later phase.',
    'phase' => null,
    'newUrl' => null,
    'newLabel' => 'New',
]));

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

foreach (array_filter(([
    'title',
    'windowTitle' => null,
    'description' => 'This module will be implemented in a later phase.',
    'phase' => null,
    'newUrl' => null,
    'newLabel' => 'New',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $windowTitle = $windowTitle ?? $title;
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
     <?php $__env->slot('windowTitle', null, []); ?> <?php echo e($windowTitle); ?> <?php $__env->endSlot(); ?>

    <div
        class="be-page"
        x-data
        @be-placeholder-find.window="$dispatch('be-toast', { message: 'Open a live list screen to use Find.' })"
        @be-placeholder-excel.window="$dispatch('be-toast', { message: 'Excel export is available on live list screens.' })"
    >
        <div class="be-toolbar">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newUrl): ?>
                <a href="<?php echo e($newUrl); ?>" class="be-btn be-btn--primary"><?php echo e($newLabel); ?></a>
            <?php else: ?>
                <button
                    type="button"
                    class="be-btn be-btn--primary"
                    @click="$dispatch('be-toast', { message: 'Create form for <?php echo e($title); ?> is coming next — use New on related list pages.' })"
                >New</button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <button type="button" class="be-btn" @click="$dispatch('be-placeholder-find')">Find</button>
            <button type="button" class="be-btn" onclick="window.print()">Print</button>
            <button type="button" class="be-btn" @click="$dispatch('be-placeholder-excel')">Excel</button>
            <span class="ml-auto text-[11px] text-gray-500">Phase <?php echo e($phase ?? '—'); ?> · list screens are live</span>
        </div>

        <div class="be-panel">
            <div class="be-panel__header">
                <h1 class="be-panel__title"><?php echo e($title); ?></h1>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($phase): ?>
                    <span class="be-badge be-badge--warn">Detail form Phase <?php echo e($phase); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="be-panel__body">
                <div class="be-empty">
                    <p class="mb-2 font-semibold text-gray-700"><?php echo e($title); ?></p>
                    <p><?php echo e($description); ?></p>
                    <p class="mt-2 text-[12px] text-gray-500">
                        Use the sidebar submenus to open live list screens (New / Find / Print / Excel work there).
                    </p>
                    <?php echo e($slot); ?>

                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\module-placeholder.blade.php ENDPATH**/ ?>