<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'placeholder' => 'Search…',
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
    'placeholder' => 'Search…',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $inputAttributes = $attributes->except('class');
?>

<div <?php echo e($attributes->only('class')->class(['be-search-box'])); ?>>
    <svg class="be-search-box__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 013.95 9.3l3.12 3.13a.75.75 0 11-1.06 1.06l-3.13-3.12A5.5 5.5 0 118.5 3zm0 1.5a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd"/>
    </svg>
    <input
        type="search"
        class="be-input be-search-box__input"
        placeholder="<?php echo e($placeholder); ?>"
        <?php echo e($inputAttributes); ?>

    >
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\search-box.blade.php ENDPATH**/ ?>