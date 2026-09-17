<div
    class="be-workspace"
    data-workspace-shell
    x-data="workspaceChrome(<?php echo \Illuminate\Support\Js::from([
        'activeTabId' => $activeTabId,
        'tabs' => $tabs,
    ])->toHtml() ?>)"
    x-on:workspace-tabs-updated.window="syncFromLivewire()"
    @keydown.window="onKeydown($event)"
>
    
    <script>
        if (window.parent && window.parent !== window) {
            window.location.replace(<?php echo \Illuminate\Support\Js::from(route('dashboard.home', ['embed' => 1, 'tab_id' => 'dashboard']))->toHtml() ?>);
        }
    </script>
    <div class="be-workspace__tabs" role="tablist" aria-label="Open windows" @click.right.prevent="">
        <div class="be-workspace__tabs-scroll">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div
                    wire:key="ws-tab-<?php echo e($tab['id']); ?>"
                    class="be-workspace__tab <?php echo e($tab['id'] === $activeTabId ? 'is-active' : ''); ?> <?php echo e(!empty($tab['dirty']) ? 'is-dirty' : ''); ?>"
                    role="tab"
                    aria-selected="<?php echo e($tab['id'] === $activeTabId ? 'true' : 'false'); ?>"
                    @click="$wire.activateTab(<?php echo \Illuminate\Support\Js::from($tab['id'])->toHtml() ?>)"
                    @contextmenu.prevent="openContextMenu($event, <?php echo \Illuminate\Support\Js::from($tab['id'])->toHtml() ?>)"
                    title="<?php echo e($tab['title']); ?>"
                >
                    <span class="be-workspace__tab-title"><?php echo e($tab['title']); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab['closable'] ?? true): ?>
                        <button
                            type="button"
                            class="be-workspace__tab-close"
                            title="Close"
                            @click.stop="$wire.closeTab(<?php echo \Illuminate\Support\Js::from($tab['id'])->toHtml() ?>)"
                        >×</button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="be-workspace__tab-actions">
            <button type="button" class="be-workspace__tab-action" title="Close all closable tabs" wire:click="closeAllTabs">Close All</button>
        </div>
    </div>

    <div class="be-workspace__panels">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div
                wire:key="ws-panel-<?php echo e($tab['id']); ?>-<?php echo e($tab['refresh_token'] ?? 0); ?>"
                class="be-workspace__panel <?php echo e($tab['id'] === $activeTabId ? 'is-active' : ''); ?>"
                data-tab-id="<?php echo e($tab['id']); ?>"
                <?php if($tab['id'] !== $activeTabId): ?> hidden <?php endif; ?>
            >
                <iframe
                    wire:ignore
                    class="be-workspace__frame"
                    title="<?php echo e($tab['title']); ?>"
                    src="<?php echo e(\App\Support\Workspace\WorkspaceCatalog::embedUrl($tab['route'] ?? 'dashboard.home', $tab['params'] ?? [], $tab['id'])); ?>&rt=<?php echo e($tab['refresh_token'] ?? 0); ?>"
                    data-workspace-tab="<?php echo e($tab['id']); ?>"
                    loading="<?php echo e($tab['id'] === $activeTabId ? 'eager' : 'lazy'); ?>"
                ></iframe>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div
        x-show="contextMenu.show"
        x-cloak
        class="be-workspace__menu"
        :style="`top:${contextMenu.y}px;left:${contextMenu.x}px`"
        @click.outside="contextMenu.show = false"
    >
        <button type="button" @click="runContext('refresh')">Refresh</button>
        <button type="button" @click="runContext('close')" x-show="contextMenu.closable">Close</button>
        <button type="button" @click="runContext('close-others')">Close Others</button>
        <button type="button" @click="runContext('close-all')">Close All</button>
    </div>

    <div
        x-show="confirm.show"
        x-cloak
        class="be-workspace__confirm-backdrop"
        @keydown.escape.window="confirm.show = false"
    >
        <div class="be-workspace__confirm" role="dialog" aria-modal="true">
            <p class="be-workspace__confirm-title">Unsaved changes will be lost. Continue?</p>
            <p class="be-workspace__confirm-body" x-text="confirm.title"></p>
            <div class="be-workspace__confirm-actions">
                <button type="button" class="be-btn" @click="confirm.show = false">Cancel</button>
                <button type="button" class="be-btn be-btn--primary" @click="discardAndClose()">Discard Changes</button>
            </div>
        </div>
    </div>
</div>

    <?php
        $__scriptKey = '3392321030-0';
        ob_start();
    ?>
<script>
    $wire.on('workspace-confirm-close', (payload) => {
        const detail = Array.isArray(payload) ? payload[0] : payload;
        window.dispatchEvent(new CustomEvent('be-workspace-confirm-close', { detail }));
    });
</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?><?php /**PATH F:\laragon\www\bargain-enterprise\resources\views\livewire\workspace\shell.blade.php ENDPATH**/ ?>