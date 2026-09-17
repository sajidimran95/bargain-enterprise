<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'active' => 'home', // home | insights
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
    'active' => 'home', // home | insights
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $embed = request()->boolean('embed')
        || request()->header('X-Workspace-Embed') === '1'
        || request()->header('Sec-Fetch-Dest') === 'iframe';

    $homeUrl = route('dashboard.home', array_filter([
        'embed' => $embed ? 1 : null,
        'tab_id' => $embed ? request('tab_id', 'dashboard') : null,
    ]));

    $insightsUrl = route('dashboard.snapshots', array_filter([
        'embed' => $embed ? 1 : null,
        'tab_id' => $embed ? request('tab_id', 'dashboard') : null,
    ]));
?>

<div class="be-page__tabs" role="tablist" aria-label="Home or Insights">
    <a
        href="<?php echo e($homeUrl); ?>"
        class="be-page__tab <?php echo e($active === 'home' ? 'is-active' : ''); ?>"
        role="tab"
        aria-selected="<?php echo e($active === 'home' ? 'true' : 'false'); ?>"
    >Home Page</a>
    <a
        href="<?php echo e($insightsUrl); ?>"
        class="be-page__tab <?php echo e($active === 'insights' ? 'is-active' : ''); ?>"
        role="tab"
        aria-selected="<?php echo e($active === 'insights' ? 'true' : 'false'); ?>"
    >Insights</a>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\home-insights-tabs.blade.php ENDPATH**/ ?>