@props([
    'route',
    'params' => [],
    'title' => null,
    'class' => '',
])

@php
    $href = \App\Support\Workspace\WorkspaceCatalog::embedUrl($route, $params);
    // Empty PHP arrays JSON-encode as [] (truthy in JS). Force {} so Livewire receives an object.
    $routeParams = $params === [] ? new \stdClass : $params;
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $class]) }}
    @click.prevent="
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({ type: 'be-workspace-open', route: @js($route), params: @js($routeParams), title: @js($title) }, '*');
        } else if (window.beWorkspace && typeof window.beWorkspace.open === 'function') {
            window.beWorkspace.open(@js($route), @js($routeParams), @js($title));
        } else if (typeof beOpenWorkspace === 'function') {
            beOpenWorkspace(@js($route), @js($routeParams), @js($title));
        } else {
            window.location.assign(@js($href));
        }
    "
>{{ $slot }}</a>
