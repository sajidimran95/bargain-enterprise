<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'info',
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
    'type' => 'info',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $class = match ($type) {
        'success' => 'be-alert be-alert--success',
        'warning' => 'be-alert be-alert--warning',
        'danger' => 'be-alert be-alert--danger',
        default => 'be-alert',
    };
?>

<div <?php echo e($attributes->class([$class])); ?> role="status">
    <?php echo e($slot); ?>

</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\alert.blade.php ENDPATH**/ ?>