<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="save" type="button">OK</x-erp.button>
        <x-erp.workspace-link route="employees.time" class="be-btn">Enter Time</x-erp.workspace-link>
        <x-erp.workspace-link route="dashboard.home" class="be-btn">Home</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Payroll</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">Turn On Payroll</h1>
            <p class="text-[11px] text-gray-600">Enable payroll processing for this company file.</p>
        </div>

        <div class="be-entity-dialog__body max-w-xl">
            <label class="mb-4 inline-flex items-center gap-2 text-[12px]">
                <input type="checkbox" wire:model="payroll_enabled">
                Payroll is enabled for this company
            </label>

            <div class="be-field">
                <label class="be-field__label">Pay Frequency *</label>
                <x-erp.select
                    wire:model="pay_frequency"
                    :options="[
                        'weekly' => 'Weekly',
                        'biweekly' => 'Every two weeks',
                        'semimonthly' => 'Twice a month',
                        'monthly' => 'Monthly',
                    ]"
                />
                @error('pay_frequency') <span class="be-field__error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>
