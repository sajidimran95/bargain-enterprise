@props([
    'active' => 'home', // home | insights
])

@php
    $embed = request()->boolean('embed')
        || request()->header('X-Workspace-Embed') === '1'
        || request()->header('Sec-Fetch-Dest') === 'iframe';

    $homeUrl = route('dashboard.home', array_filter([
        'embed' => $embed ? 1 : null,
        'tab_id' => $embed ? request('tab_id', 'dashboard') : null,
    ]));

    $insightsUrl = route('dashboard.snapshots', array_filter([
        'embed' => $embed ? 1 : null,
        'tab_id' => $embed ? request('tab_id', 'dashboard') : null,
    ]));
@endphp

<div class="be-page__tabs" role="tablist" aria-label="Home or Insights">
    <a
        href="{{ $homeUrl }}"
        class="be-page__tab {{ $active === 'home' ? 'is-active' : '' }}"
        role="tab"
        aria-selected="{{ $active === 'home' ? 'true' : 'false' }}"
    >Home Page</a>
    <a
        href="{{ $insightsUrl }}"
        class="be-page__tab {{ $active === 'insights' ? 'is-active' : '' }}"
        role="tab"
        aria-selected="{{ $active === 'insights' ? 'true' : 'false' }}"
    >Insights</a>
</div>
