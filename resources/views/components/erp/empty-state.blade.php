@props([
    'title' => 'No records found',
    'message' => null,
])

<div {{ $attributes->class(['be-empty']) }}>
    <p class="mb-1 font-semibold text-gray-700">{{ $title }}</p>
    @if ($message)
        <p>{{ $message }}</p>
    @endif
    {{ $slot }}
</div>
