<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($title)): ?>
            <?php echo e($title); ?> — <?php echo e(config('bargain.company_name')); ?>

        <?php else: ?>
            <?php echo e(config('bargain.company_name')); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="be-body be-body--embed" data-workspace-embed="1" data-workspace-tab-id="<?php echo e(request('tab_id')); ?>">
    <?php echo e($slot); ?>


    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <script>
        (function () {
            const tabId = new URLSearchParams(window.location.search).get('tab_id')
                || document.body.dataset.workspaceTabId
                || null;

            window.beWorkspace = {
                tabId,
                open(route, params = {}, title = null, id = null) {
                    window.parent.postMessage({
                        type: 'be-workspace-open',
                        route,
                        params,
                        title,
                        id,
                    }, '*');
                },
                setDirty(dirty) {
                    window.parent.postMessage({ type: 'be-workspace-dirty', tabId, dirty: !!dirty }, '*');
                },
                setTitle(title) {
                    window.parent.postMessage({ type: 'be-workspace-title', tabId, title }, '*');
                },
                close() {
                    window.parent.postMessage({ type: 'be-workspace-close', tabId }, '*');
                },
            };

            window.addEventListener('be-workspace-dirty', (e) => {
                window.beWorkspace.setDirty(!!(e.detail && e.detail.dirty));
            });

            document.addEventListener('change', () => {
                if (document.querySelector('form, .be-invoice-page, .be-page')) {
                    // Soft dirty signal for form interaction inside embeds.
                    window.beWorkspace.setDirty(true);
                }
            }, { capture: true, once: true });
        })();
    </script>
</body>
</html>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/layouts/embed.blade.php ENDPATH**/ ?>