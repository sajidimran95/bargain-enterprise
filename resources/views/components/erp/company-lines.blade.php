@php
    $lines = \App\Support\CompanyProfile::lines();
@endphp
@if ($lines !== [])
    <div {{ $attributes->merge(['class' => 'muted']) }}>
        @foreach ($lines as $line)
            {{ $line }}@if (! $loop->last)<br>@endif
        @endforeach
    </div>
@endif
