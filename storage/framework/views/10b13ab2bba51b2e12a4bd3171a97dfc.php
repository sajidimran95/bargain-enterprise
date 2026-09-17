<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'showAddLine' => false,
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
    'showAddLine' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="be-item-search" wire:click.outside="clearItemSearch">
    <div class="be-scan-bar be-scan-bar--compact">
        <label class="be-field__label be-field__label--caps mb-0">Item / Scan</label>
        <input
            x-ref="scanInput"
            type="text"
            class="be-input be-scan-input"
            wire:model.live.debounce.150ms="scanCode"
            wire:keydown.enter.prevent="scanItem"
            wire:keydown.escape.prevent="clearItemSearch"
            placeholder="Search code / name — top 5 matches"
            autocomplete="off"
            spellcheck="false"
        >
        <button type="button" class="be-btn be-btn--primary" wire:click="scanItem">Add</button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAddLine): ?>
            <button type="button" class="be-btn" wire:click="addLine">Add Line</button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($this->itemSearchResults) > 0): ?>
        <ul class="be-item-search__results" role="listbox">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->itemSearchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li wire:key="item-search-<?php echo e($result['id']); ?>">
                    <button
                        type="button"
                        class="be-item-search__result"
                        wire:click="selectItemSearchResult(<?php echo e($result['id']); ?>)"
                    >
                        <span class="be-item-search__code"><?php echo e($result['code']); ?></span>
                        <span class="be-item-search__label"><?php echo e($result['label']); ?></span>
                    </button>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/components/erp/item-search-bar.blade.php ENDPATH**/ ?>