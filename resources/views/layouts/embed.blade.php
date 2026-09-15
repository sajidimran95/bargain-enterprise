<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title)
            {{ $title }} — {{ config('bargain.company_name') }}
        @else
            {{ config('bargain.company_name') }}
        @endisset
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="be-body be-body--embed" data-workspace-embed="1" data-workspace-tab-id="{{ request('tab_id') }}">
    {{ $slot }}

    @livewireScripts
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
