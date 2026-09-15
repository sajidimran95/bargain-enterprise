@props(['wireSend' => 'sendReportEmail', 'show' => false])

@if ($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md border bg-white p-4 shadow-lg" style="border-color: var(--be-border-dark);">
            <h2 class="mb-3 text-[14px] font-semibold">Email Document</h2>
            <div class="be-field mb-3">
                <label class="be-field__label">To</label>
                <x-erp.input type="email" wire:model="emailTo" placeholder="customer@example.com" />
                @error('emailTo') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-2">
                <x-erp.button type="button" wire:click="closeEmailModal">Cancel</x-erp.button>
                <x-erp.button type="button" variant="primary" wire:click="{{ $wireSend }}">Send</x-erp.button>
            </div>
        </div>
    </div>
@endif
