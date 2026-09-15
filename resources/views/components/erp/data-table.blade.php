@props([
    'headers' => [],
])

<div class="be-datatable-wrap">
    <table {{ $attributes->class(['be-table']) }}>
        @if (count($headers))
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
