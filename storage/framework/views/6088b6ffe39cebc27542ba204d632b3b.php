<div class="be-sidebar__body">
    <nav class="be-sidebar__nav" x-ref="shortcutNav" aria-label="Navigation">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $shortcuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div
                class="be-sidebar__link-wrap"
                wire:key="shortcut-<?php echo e($item['key']); ?>"
                data-label="<?php echo e(strtolower($item['label'])); ?>"
            >
                <a
                    href="<?php echo e(route('dashboard', ['open' => $item['route']])); ?>"
                    class="be-sidebar__link <?php echo e($item['active'] ? 'is-active' : ''); ?>"
                    @click.prevent="beOpenWorkspace(<?php echo \Illuminate\Support\Js::from($item['route'])->toHtml() ?>)"
                >
                    <span class="be-sidebar__icon" aria-hidden="true"><?php echo $__env->make('components.erp.icons.'.$item['icon'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
                    <span class="flex-1"><?php echo e($item['label']); ?></span>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="be-sidebar__muted px-2">No navigation items. Use the gear under My Shortcuts to add some.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </nav>

    <div class="be-sidebar__myshortcuts" x-data="{ expanded: true }">
        <div class="be-sidebar__myshortcuts-head">
            <button
                type="button"
                class="be-sidebar__myshortcuts-title"
                @click="expanded = !expanded"
            >
                <span class="be-sidebar__myshortcuts-chevron" :class="expanded ? 'is-open' : ''" aria-hidden="true">▾</span>
                My Shortcuts
            </button>
            <button
                type="button"
                class="be-sidebar__gear"
                title="Customize shortcuts"
                wire:click="openAdd"
                aria-label="Customize My Shortcuts"
            >
                <svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="8" cy="8" r="2.2"/>
                    <path d="M8 1.5v1.4M8 13.1v1.4M1.5 8h1.4M13.1 8h1.4M3.1 3.1l1 1M11.9 11.9l1 1M3.1 12.9l1-1M11.9 4.1l1-1"/>
                </svg>
            </button>
        </div>

        <div class="be-sidebar__myshortcuts-body" x-show="expanded" x-cloak>
            <a
                href="<?php echo e(route('dashboard', ['open' => 'accounting.chart'])); ?>"
                class="be-sidebar__footer-link"
                @click.prevent="beOpenWorkspace('accounting.chart')"
            >View Balances</a>
            <a
                href="<?php echo e(route('dashboard', ['open' => 'reports.index'])); ?>"
                class="be-sidebar__footer-link"
                @click.prevent="beOpenWorkspace('reports.index')"
            >Run Favorite Reports</a>
            <div class="be-sidebar__footer-link be-sidebar__footer-link--static">Open Windows</div>

            <div class="be-sidebar__open-windows">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $openWindows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wsTab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button
                        type="button"
                        class="be-sidebar__window-item <?php echo e(($activeWindowId ?? '') === ($wsTab['id'] ?? '') ? 'is-active' : ''); ?>"
                        @click="beOpenWorkspace(<?php echo \Illuminate\Support\Js::from($wsTab['route'] ?? 'dashboard.home')->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($wsTab['params'] ?? [])->toHtml() ?>)"
                    ><?php echo e($wsTab['title'] ?? 'Window'); ?></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="be-sidebar__muted"><?php echo e($currentWindowTitle); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAdd): ?>
        <div class="be-modal be-modal--sidebar">
            <div class="be-modal__backdrop" wire:click="closeAdd"></div>
            <div class="be-modal__panel be-modal__panel--sm" wire:click.stop>
                <div class="be-modal__header">
                    <h3 class="be-modal__title">My Shortcuts</h3>
                    <button type="button" class="be-modal__close" wire:click="closeAdd">×</button>
                </div>
                <div class="be-modal__body space-y-3">
                    <div>
                        <p class="mb-1 text-[11px] font-semibold text-gray-600">Pinned (click × to remove)</p>
                        <div class="be-sidebar__add-list border border-gray-200 p-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $shortcuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="be-sidebar__add-item be-sidebar__add-item--pinned" wire:key="pinned-<?php echo e($item['key']); ?>">
                                    <span class="be-sidebar__icon" aria-hidden="true"><?php echo $__env->make('components.erp.icons.'.$item['icon'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
                                    <span class="flex-1"><?php echo e($item['label']); ?></span>
                                    <button type="button" class="be-link-btn" wire:click="removeShortcut(<?php echo \Illuminate\Support\Js::from($item['key'])->toHtml() ?>)">×</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="be-sidebar__muted">None pinned yet.</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <p class="mb-1 text-[11px] font-semibold text-gray-600">Add shortcut</p>
                        <div class="be-sidebar__add-list border border-gray-200 p-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $available; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <button
                                    type="button"
                                    class="be-sidebar__add-item"
                                    wire:key="add-<?php echo e($item['key']); ?>"
                                    wire:click="addShortcut(<?php echo \Illuminate\Support\Js::from($item['key'])->toHtml() ?>)"
                                >
                                    <span class="be-sidebar__icon" aria-hidden="true"><?php echo $__env->make('components.erp.icons.'.$item['icon'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
                                    <span><?php echo e($item['label']); ?></span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="be-sidebar__muted px-2 py-1">All available shortcuts are already pinned.</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="be-modal__footer">
                    <button type="button" class="be-btn" wire:click="resetShortcuts" wire:confirm="Reset sidebar shortcuts to defaults?">Reset Defaults</button>
                    <button type="button" class="be-btn be-btn--primary" wire:click="closeAdd">Done</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/workspace/my-shortcuts.blade.php ENDPATH**/ ?>