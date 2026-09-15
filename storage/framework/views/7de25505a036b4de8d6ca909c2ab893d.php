<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route',
    'params' => [],
    'title' => null,
    'class' => '',
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
    'route',
    'params' => [],
    'title' => null,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $href = \App\Support\Workspace\WorkspaceCatalog::embedUrl($route, $params);
?>

<a
    href="<?php echo e($href); ?>"
    <?php echo e($attributes->merge(['class' => $class])); ?>

    @click.prevent="
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({
                type: 'be-workspace-open',
                route: <?php echo \Illuminate\Support\Js::from($route)->toHtml() ?>,
                params: <?php echo \Illuminate\Support\Js::from($params)->toHtml() ?>,
                title: <?php echo \Illuminate\Support\Js::from($title)->toHtml() ?>,
            }, '*');
        } else if (window.beWorkspace && typeof window.beWorkspace.open === 'function') {
            window.beWorkspace.open(<?php echo \Illuminate\Support\Js::from($route)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($params)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($title)->toHtml() ?>);
        } else if (typeof beOpenWorkspace === 'function') {
            beOpenWorkspace(<?php echo \Illuminate\Support\Js::from($route)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($params)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($title)->toHtml() ?>);
        } else {
            window.location.assign(<?php echo \Illuminate\Support\Js::from($href)->toHtml() ?>);
        }
    "
><?php echo e($slot); ?></a>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/components/erp/workspace-link.blade.php ENDPATH**/ ?>