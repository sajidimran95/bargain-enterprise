@props([
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'disabled' => false,
])

<select
    {{ $attributes->merge(['class' => 'be-input be-select'])->class(['opacity-60' => $disabled]) }}
    @disabled($disabled)
>
    @if ($placeholder !== null)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $optionValue => $label)
        <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
            {{ $label }}
        </option>
    @endforeach
    {{ $slot }}
</select>
