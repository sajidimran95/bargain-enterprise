@props(['entry'])

@if (! empty($entry['separator']))
    <div class="be-menubar__sep" role="separator"></div>
@elseif (! empty($entry['children']))
    <div class="be-menubar__submenu" role="none">
        <button type="button" class="be-menubar__link be-menubar__link--flyout" role="menuitem" aria-haspopup="true">
            <span class="be-menubar__link-label">{{ $entry['label'] }}</span>
            <span class="be-menubar__chevron" aria-hidden="true">▸</span>
        </button>
        <div class="be-menubar__flyout" role="menu">
            @foreach ($entry['children'] as $child)
                <x-erp.menubar-entry :entry="$child" />
            @endforeach
        </div>
    </div>
@elseif (! empty($entry['disabled']))
    <span class="be-menubar__link be-menubar__link--disabled" role="menuitem" aria-disabled="true">
        <span class="be-menubar__link-label">{{ $entry['label'] }}</span>
        @if (! empty($entry['shortcut']))
            <span class="be-menubar__shortcut">{{ $entry['shortcut'] }}</span>
        @endif
    </span>
@elseif (! empty($entry['action']))
    <button
        type="button"
        class="be-menubar__link"
        role="menuitem"
        @click="handleMenuAction(@js($entry)); open = null"
    >
        <span class="be-menubar__link-label">{{ $entry['label'] }}</span>
        @if (! empty($entry['shortcut']))
            <span class="be-menubar__shortcut">{{ $entry['shortcut'] }}</span>
        @endif
    </button>
@else
    <a
        href="{{ route('dashboard', ['open' => $entry['route']]) }}"
        class="be-menubar__link"
        role="menuitem"
        @click.prevent="beOpenWorkspace(@js($entry['route']), @js($entry['params'] ?? []), @js($entry['title'] ?? ($entry['label'] ?? null))); open = null"
    >
        <span class="be-menubar__link-label">{{ $entry['label'] }}</span>
        @if (! empty($entry['shortcut']))
            <span class="be-menubar__shortcut">{{ $entry['shortcut'] }}</span>
        @endif
    </a>
@endif
