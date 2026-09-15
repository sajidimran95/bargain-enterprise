<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php
    $isWorkspaceEmbed = request()->boolean('embed')
        || request()->header('X-Workspace-Embed') === '1'
        || request()->header('Sec-Fetch-Dest') === 'iframe';
    $companyName = \App\Models\Setting::getValue('company.name', config('bargain.company_name'));
    $productName = config('bargain.product_name');
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isWorkspaceEmbed): ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($title)): ?>
            <?php echo e($title); ?> — <?php echo e($companyName); ?>

        <?php else: ?>
            <?php echo e($companyName); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="be-body be-body--embed" data-workspace-embed="1">
    <?php echo e($slot); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <script>
        (function () {
            const params = new URLSearchParams(window.location.search);
            const tabId = params.get('tab_id');
            window.beWorkspace = {
                tabId,
                open(route, routeParams = {}, title = null, id = null) {
                    window.parent.postMessage({ type: 'be-workspace-open', route, params: routeParams, title, id }, '*');
                },
                setDirty(dirty) {
                    if (!tabId) return;
                    window.parent.postMessage({ type: 'be-workspace-dirty', tabId, dirty: !!dirty }, '*');
                },
                setTitle(title) {
                    if (!tabId) return;
                    window.parent.postMessage({ type: 'be-workspace-title', tabId, title }, '*');
                },
            };
            document.addEventListener('input', () => window.beWorkspace.setDirty(true), { once: true, capture: true });
        })();
    </script>
</body>
</html>
<?php else: ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($title)): ?>
            <?php echo e($title); ?> — <?php echo e($companyName); ?>

        <?php else: ?>
            <?php echo e($companyName); ?> — <?php echo e($productName); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body
    class="be-body"
    x-data="erpShell()"
    @keydown.window="handleShortcuts($event)"
>
    <div class="be-app" @be-toast.window="showToast(($event.detail && $event.detail.message) || 'Saved')">
        
        <header class="be-top">
            <div class="be-titlebar">
                <span class="be-titlebar__company"><?php echo e($companyName); ?></span>
                <span class="be-titlebar__sep">—</span>
                <span class="be-titlebar__product"><?php echo e($productName); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($windowTitle)): ?>
                    <span class="be-titlebar__sep">—</span>
                    <span class="be-titlebar__window">[<?php echo e($windowTitle); ?>]</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="be-menubar" role="menubar" aria-label="Main menu" x-data="{ open: null }" @keydown.escape.window="open = null">
                <?php
                    $menuBar = config('erp_menubar');
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $menuBar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="be-menubar__group"
                        @mouseenter="open = <?php echo \Illuminate\Support\Js::from($menu)->toHtml() ?>"
                        @mouseleave="open = null"
                        :class="{ 'be-menubar__group--open': open === <?php echo \Illuminate\Support\Js::from($menu)->toHtml() ?> }"
                    >
                        <button
                            type="button"
                            class="be-menubar__item"
                            role="menuitem"
                            :aria-expanded="(open === <?php echo \Illuminate\Support\Js::from($menu)->toHtml() ?>).toString()"
                            @click="open = open === <?php echo \Illuminate\Support\Js::from($menu)->toHtml() ?> ? null : <?php echo \Illuminate\Support\Js::from($menu)->toHtml() ?>"
                        ><?php echo e($menu); ?></button>
                        <div class="be-menubar__dropdown" x-show="open === <?php echo \Illuminate\Support\Js::from($menu)->toHtml() ?>" x-cloak role="menu">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if (isset($component)) { $__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.menubar-entry','data' => ['entry' => $entry]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.menubar-entry'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entry' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entry)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9)): ?>
<?php $attributes = $__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9; ?>
<?php unset($__attributesOriginale62c43ec56c7a6ca53e1ec11ab050dc9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9)): ?>
<?php $component = $__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9; ?>
<?php unset($__componentOriginale62c43ec56c7a6ca53e1ec11ab050dc9); ?>
<?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="be-utilitybar">
                <div class="be-utilitybar__search">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('search.global-search', []);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-166257523-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
                <div class="be-utilitybar__user">
                    <span class="be-utilitybar__user-name"><?php echo e(auth()->user()->name); ?></span>
                    <span class="be-utilitybar__user-role"><?php echo e(auth()->user()->primaryRoleLabel()); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="be-link-btn">Sign out</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="be-shell">
            <aside class="be-sidebar" aria-label="Navigation">
                <div class="be-sidebar__search">
                    <input
                        type="search"
                        class="be-input be-input--sidebar"
                        placeholder="Search Company or Help"
                        x-ref="shortcutSearch"
                        @input="filterShortcuts($event.target.value)"
                        aria-label="Search Company or Help"
                    >
                </div>

                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('workspace.my-shortcuts', []);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-166257523-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </aside>

            <main class="be-main" id="main-content" tabindex="-1">
                <?php echo e($slot); ?>

            </main>
        </div>
    </div>

    <div
        x-show="toast.show"
        x-cloak
        x-transition
        class="be-toast"
        role="status"
        aria-live="polite"
        @click="toast.show = false"
    >
        <span x-text="toast.message"></span>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <script>
        function erpShell() {
            return {
                toast: { show: false, message: '' },
                filterShortcuts(value) {
                    const q = (value || '').toLowerCase().trim();
                    const nav = this.$el.querySelector('.be-sidebar__nav');
                    if (!nav) {
                        return;
                    }
                    nav.querySelectorAll('[data-label]').forEach((el) => {
                        el.style.display = !q || el.dataset.label.includes(q) ? '' : 'none';
                    });
                    if (!q) {
                        return;
                    }
                    nav.querySelectorAll('.be-sidebar__group').forEach((group) => {
                        if (group.style.display === 'none') {
                            return;
                        }
                        const data = group._x_dataStack && group._x_dataStack[0];
                        if (data) {
                            data.open = true;
                        }
                    });
                },
                handleShortcuts(e) {
                    const tag = (e.target.tagName || '').toLowerCase();
                    const typing = ['input', 'textarea', 'select'].includes(tag) || e.target.isContentEditable;

                    if (e.key === 'Escape') {
                        window.dispatchEvent(new CustomEvent('be-close-modal'));
                        return;
                    }

                    if (typing && !(e.ctrlKey || e.metaKey)) {
                        return;
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'f') {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('be-focus-search'));
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('be-save'));
                        this.showToast('Save shortcut ready (wired per form in later phases)');
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'n') {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('be-new'));
                        this.showToast('New shortcut ready (wired per module in later phases)');
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'p') {
                        e.preventDefault();
                        window.print();
                    }

                    if (e.key === 'F2') {
                        e.preventDefault();
                        beOpenWorkspace('customers.index');
                    }
                    if (e.key === 'F3') {
                        e.preventDefault();
                        beOpenWorkspace('items.index');
                    }
                    if (e.key === 'F4') {
                        e.preventDefault();
                        beOpenWorkspace('payments.index');
                    }
                },
                handleMenuAction(entry) {
                    const action = entry && entry.action;
                    if (!action) return;
                    if (action === 'logout') {
                        document.querySelector('.be-utilitybar__user form')?.submit();
                        return;
                    }
                    if (action === 'toast') {
                        this.showToast(entry.message || entry.label || 'OK');
                        return;
                    }
                    if (action === 'focus-tabs') {
                        document.querySelector('.be-workspace__tabs')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        return;
                    }
                    const shell = document.querySelector('[data-workspace-shell]');
                    const wireId = shell?.closest('[wire\\:id]')?.getAttribute('wire:id') || shell?.getAttribute('wire:id');
                    const component = wireId && window.Livewire ? Livewire.find(wireId) : null;
                    if (!component) {
                        this.showToast('Open the workspace Home to use Window commands.');
                        return;
                    }
                    if (action === 'close-tab') component.call('closeTab', component.activeTabId);
                    if (action === 'close-others') component.call('closeOtherTabs', component.activeTabId);
                    if (action === 'close-all') component.call('closeAllTabs');
                    if (action === 'refresh-tab') component.call('refreshTab', component.activeTabId);
                },
                showToast(message) {
                    this.toast.message = message;
                    this.toast.show = true;
                    setTimeout(() => this.toast.show = false, 2500);
                }
            }
        }
    </script>
</body>
</html>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/layouts/app.blade.php ENDPATH**/ ?>