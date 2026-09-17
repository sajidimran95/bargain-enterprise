<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show' => false,
    'title' => 'Confirm',
    'confirmLabel' => 'OK',
    'cancelLabel' => 'Cancel',
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
    'show' => false,
    'title' => 'Confirm',
    'confirmLabel' => 'OK',
    'cancelLabel' => 'Cancel',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{ open: <?php echo \Illuminate\Support\Js::from($show)->toHtml() ?> }"
    x-show="open"
    x-cloak
    x-on:be-close-modal.window="open = false"
    class="be-modal"
    role="alertdialog"
    aria-modal="true"
>
    <div class="be-modal__backdrop" x-on:click="open = false"></div>
    <div class="be-modal__panel be-modal__panel--sm" @click.stop>
        <div class="be-modal__header">
            <h2 class="be-modal__title"><?php echo e($title); ?></h2>
        </div>
        <div class="be-modal__body">
            <?php echo e($slot); ?>

        </div>
        <div class="be-modal__footer">
            <button type="button" class="be-btn" x-on:click="open = false"><?php echo e($cancelLabel); ?></button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($confirm)): ?>
                <?php echo e($confirm); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\confirm-dialog.blade.php ENDPATH**/ ?>