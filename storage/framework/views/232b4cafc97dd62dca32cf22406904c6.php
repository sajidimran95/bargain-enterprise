<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Actions',
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
    'label' => 'Actions',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{ open: false }"
    class="be-dropdown"
    @click.outside="open = false"
    <?php echo e($attributes); ?>

>
    <button type="button" class="be-btn be-dropdown__trigger" @click="open = !open">
        <?php echo e($label); ?>

        <span aria-hidden="true">▾</span>
    </button>
    <div x-show="open" x-cloak class="be-dropdown__menu" role="menu">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\dropdown.blade.php ENDPATH**/ ?>