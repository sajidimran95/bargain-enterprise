@props([
    'heading' => null,
    'title' => null,
    'newRoute' => null,
    'newParams' => [],
    'newTitle' => null,
    'newUrl' => null,
    'newLabel' => 'New',
    'showFind' => true,
    'showPrint' => true,
    'showExcel' => true,
    'excelAction' => 'exportExcel',
])

<div {{ $attributes->class('be-list-header') }}>
    @if ($heading)
        <h1 class="be-list-header__title">{{ $heading }}</h1>
    @endif

    <div class="be-list-header__actions">
        @if ($newRoute)
            <x-erp.workspace-link
                :route="$newRoute"
                :params="$newParams"
                :title="$newTitle"
                class="be-btn be-btn--primary"
            >{{ $newLabel }}</x-erp.workspace-link>
        @elseif ($newUrl)
            <a
                href="{{ $newUrl }}"
                class="be-btn be-btn--primary"
                @click.prevent="
                    const u = new URL(@js($newUrl), window.location.origin);
                    u.searchParams.set('embed', '1');
                    if (window.beWorkspace?.tabId) {
                        u.searchParams.set('tab_id', window.beWorkspace.tabId);
                    }
                    if (window.beWorkspace && window.parent !== window) {
                        window.location.assign(u.toString());
                    } else if (typeof beOpenWorkspace === 'function') {
                        window.location.assign(u.toString());
                    } else {
                        window.location.assign(@js($newUrl));
                    }
                "
            >{{ $newLabel }}</a>
        @elseif (isset($new))
            {{ $new }}
        @endif

        @if ($showFind)
            <x-erp.button type="button" wire:click="focusSearch">Find</x-erp.button>
        @endif

        @if ($showPrint)
            <x-erp.button type="button" wire:click="printSelected">Print</x-erp.button>
        @endif

        @if ($showExcel)
            <x-erp.button type="button" wire:click="{{ $excelAction }}">Excel</x-erp.button>
        @endif

        {{ $slot }}

        @if ($title)
            <span class="ml-auto text-[11px] text-gray-500">{{ $title }}</span>
        @endif
    </div>
</div>
