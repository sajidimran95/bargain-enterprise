{{-- Toast is hosted in layouts/app via Alpine erpShell().showToast(). --}}
@props(['message' => ''])

<div
    x-data
    x-init="
        @if (filled($message))
            $dispatch('be-toast', { message: @js($message) });
        @endif
    "
    class="hidden"
    aria-hidden="true"
></div>
