@props([
    'route',
    'params' => [],
    'title' => null,
    'class' => '',
])

@php
    $href = \App\Support\Workspace\WorkspaceCatalog::embedUrl($route, $params);
    $openJs = 'event.preventDefault();'
        .'if(window.parent&&window.parent!==window){window.parent.postMessage({type:\'be-workspace-open\',route:'.Illuminate\Support\Js::from($route).',params:'.Illuminate\Support\Js::from($params).',title:'.Illuminate\Support\Js::from($title).'},\'*\');}'
        .'else if(window.beWorkspace&&typeof window.beWorkspace.open===\'function\'){window.beWorkspace.open('.Illuminate\Support\Js::from($route).','.Illuminate\Support\Js::from($params).','.Illuminate\Support\Js::from($title).');}'
        .'else if(typeof beOpenWorkspace===\'function\'){beOpenWorkspace('.Illuminate\Support\Js::from($route).','.Illuminate\Support\Js::from($params).','.Illuminate\Support\Js::from($title).');}'
        .'else{window.location.assign('.Illuminate\Support\Js::from($href).');}';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => $class]) }}
    onclick="{!! $openJs !!}"
>{{ $slot }}</a>
