<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <x-erp.workspace-link :route="$cancelRoute" class="be-btn">Cancel</x-erp.workspace-link>
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <span class="be-doc-toolbar__title">{{ $pageTitle }}</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">{{ $pageTitle }}</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Bank Account</label>
                            <x-erp.select wire:model="bank_account_id" class="be-input--combo" :options="['' => 'Select…'] + $bankAccounts" />
                            @error('bank_account_id') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">{{ $numberLabel }}</label>
                            @if (($numberField ?? null) === 'check_number')
                                <x-erp.input wire:model="check_number" class="be-input--combo" />
                                @error('check_number') <span class="be-field__error">{{ $message }}</span> @enderror
                            @else
                                <x-erp.input wire:model="number" class="be-input--combo" />
                                @error('number') <span class="be-field__error">{{ $message }}</span> @enderror
                            @endif
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            @if ($showPayee ?? false)
                                <x-erp.input type="date" wire:model="check_date" class="be-input--combo" />
                            @else
                                <x-erp.input type="date" wire:model="deposit_date" class="be-input--combo" />
                            @endif
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Amount</label>
                            @if (($amountField ?? null) === 'amount')
                                <x-erp.input type="number" step="0.01" min="0.01" wire:model="amount" class="be-input--combo" />
                                @error('amount') <span class="be-field__error">{{ $message }}</span> @enderror
                            @else
                                <x-erp.input type="number" step="0.01" min="0.01" wire:model="total" class="be-input--combo" />
                                @error('total') <span class="be-field__error">{{ $message }}</span> @enderror
                            @endif
                        </div>
                        @if ($showPayee ?? false)
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Payee</label>
                                <x-erp.input wire:model="payee" class="be-input--combo" />
                                @error('payee') <span class="be-field__error">{{ $message }}</span> @enderror
                            </div>
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Vendor</label>
                                <x-erp.select wire:model="vendor_id" class="be-input--combo" :options="['' => 'Select…'] + ($vendors ?? [])" />
                            </div>
                        @endif
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input wire:model="memo" class="be-input--combo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
