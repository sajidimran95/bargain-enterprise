<x-erp.report-shell
    title="MSA Customer List"
    :show-dates="false"
    :show-filter-bar="true"
    :hide-header="$hideHeader"
    :show-extra-filters="$showExtraFilters"
    :sort-by="$sortBy"
    :sort-by-options="$sortByOptions"
    :show-email-modal="$showEmailModal"
>
    <x-slot:excel>
        <x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button>
    </x-slot:excel>

    <x-slot:filters>
        <div class="flex flex-wrap items-end gap-3">
            <div class="be-field">
                <label class="be-field__label">Look for</label>
                <x-erp.input wire:model.live.debounce.300ms="search" placeholder="Customer / phone / city…" class="w-64" />
            </div>
            <div class="be-field">
                <label class="be-field__label">Status</label>
                <x-erp.select
                    wire:model.live="status"
                    :options="[
                        'active' => 'Active Customers',
                        'inactive' => 'Inactive Customers',
                        'all' => 'All Customers',
                    ]"
                />
            </div>
        </div>
    </x-slot:filters>

    <table class="be-report-table be-table be-table--line-select be-report-table--wide">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Primary Contact</th>
                <th>Work Phone</th>
                <th>Fax</th>
                <th>Street1</th>
                <th>Street2</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null }">
            @forelse ($rows as $customer)
                <tr
                    wire:key="cust-dir-{{ $customer->id }}"
                    @click="selectedLine = 'cust-{{ $customer->id }}'"
                    :class="selectedLine === 'cust-{{ $customer->id }}' ? 'is-selected' : ''"
                >
                    <td>{{ $customer->customer_number ? $customer->customer_number.' ('.$customer->display_name.')' : $customer->display_name }}</td>
                    <td>{{ $customer->fullName() }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->fax }}</td>
                    <td>{{ $customer->bill_to_street1 }}</td>
                    <td>{{ $customer->bill_to_street2 }}</td>
                    <td>{{ $customer->bill_to_city }}</td>
                    <td>{{ $customer->bill_to_state }}</td>
                    <td>{{ $customer->bill_to_zip }}</td>
                </tr>
            @empty
                <tr><td colspan="9">No customers.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-erp.report-shell>
