<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show' => false,
    'title' => null,
    'maxWidth' => 'lg',
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
    'title' => null,
    'maxWidth' => 'lg',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $width = match ($maxWidth) {
        'sm' => 'be-modal__panel--sm',
        'md' => 'be-modal__panel--md',
        'xl' => 'be-modal__panel--xl',
        'full' => 'be-modal__panel--full',
        default => 'be-modal__panel--lg',
    };
?>

<div
    x-data="{ open: <?php echo \Illuminate\Support\Js::from($show)->toHtml() ?> }"
    x-show="open"
    x-cloak
    x-on:be-close-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    class="be-modal"
    role="dialog"
    aria-modal="true"
    <?php echo e($attributes); ?>

>
    <div class="be-modal__backdrop" x-on:click="open = false"></div>
    <div class="be-modal__panel <?php echo e($width); ?>" @click.stop>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($title || isset($header)): ?>
            <div class="be-modal__header">
                <h2 class="be-modal__title"><?php echo e($title ?? $header); ?></h2>
                <button type="button" class="be-modal__close" x-on:click="open = false" aria-label="Close">&times;</button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="be-modal__body">
            <?php echo e($slot); ?>

        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($footer)): ?>
            <div class="be-modal__footer">
                <?php echo e($footer); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\components\erp\modal.blade.php ENDPATH**/ ?>