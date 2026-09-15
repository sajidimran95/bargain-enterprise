@props([
    'paginator',
])

@if ($paginator->hasPages())
    <div {{ $attributes->class(['be-pagination']) }}>
        {{ $paginator->links() }}
    </div>
@endif
