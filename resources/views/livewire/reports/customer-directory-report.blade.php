<x-erp.report-shell
    title="Customer Contact List"
    :show-dates="false"
    :hide-header="$hideHeader"
    :show-email-modal="$showEmailModal"
>
    <x-slot:excel>
        <x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button>
    </x-slot:excel>

    <table class="be-report-table be-table be-table--line-select">
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
