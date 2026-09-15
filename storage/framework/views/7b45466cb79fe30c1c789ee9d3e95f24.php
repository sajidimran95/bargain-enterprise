<div
    class="be-global-search"
    x-data
    @be-focus-search.window="$refs.searchInput.focus()"
    @click.outside="$wire.close()"
>
    <label for="global-search" class="sr-only">Global search</label>
    <div class="be-global-search__field">
        <svg class="be-global-search__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 104.06 9.06l3.19 3.19a.75.75 0 101.06-1.06l-3.19-3.19A5.5 5.5 0 008.5 3zm-4 5.5a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd"/>
        </svg>
        <input
            id="global-search"
            x-ref="searchInput"
            type="search"
            class="be-input be-global-search__input"
            placeholder="Search customers, invoices, items, vendors…"
            wire:model.live.debounce.300ms="query"
            autocomplete="off"
            aria-autocomplete="list"
            aria-expanded="<?php echo e($open ? 'true' : 'false'); ?>"
        >
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($open && count($groups)): ?>
        <div class="be-global-search__panel" role="listbox">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="be-global-search__group">
                    <div class="be-global-search__group-label"><?php echo e($group['label']); ?></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $group['results']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($result['url']); ?>" class="be-global-search__result" role="option">
                            <div class="be-global-search__result-title"><?php echo e($result['title']); ?></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($result['subtitle'])): ?>
                                <div class="be-global-search__result-sub"><?php echo e($result['subtitle']); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/search/global-search.blade.php ENDPATH**/ ?>