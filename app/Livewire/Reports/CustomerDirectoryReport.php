<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('MSA Customer List')]
class CustomerDirectoryReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public string $status = 'active';

    public string $search = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'all';
        $this->applyDatePreset('all');
        $this->sortBy = 'default';
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (Customer $customer) => [
            $this->customerLabel($customer),
            $customer->fullName(),
            $customer->phone,
            $customer->fax,
            $customer->bill_to_street1,
            $customer->bill_to_street2,
            $customer->bill_to_city,
            $customer->bill_to_state,
            $customer->bill_to_zip,
        ]);

        return $this->exportReportCsv(
            'customer-directory.csv',
            ['Customer', 'Primary Contact', 'Work Phone', 'Fax', 'Street1', 'Street2', 'City', 'State', 'Zip'],
            $rows
        );
    }

    /**
     * @return Collection<int, Customer>
     */
    protected function rows(): Collection
    {
        $query = Customer::query();

        if ($this->status === 'active') {
            $query->active();
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        if (filled($this->search)) {
            $query->search($this->search);
        }

        return $this->applySort($query)->get();
    }

    protected function applySort(Builder $query): Builder
    {
        return match ($this->sortBy) {
            'customer' => $query->orderBy('customer_number')->orderBy('display_name'),
            'city' => $query->orderBy('bill_to_city')->orderBy('display_name'),
            'state' => $query->orderBy('bill_to_state')->orderBy('bill_to_city')->orderBy('display_name'),
            'zip' => $query->orderBy('bill_to_zip')->orderBy('display_name'),
            'contact' => $query->orderBy('last_name')->orderBy('first_name')->orderBy('display_name'),
            default => $query->orderBy('display_name'),
        };
    }

    protected function customerLabel(Customer $customer): string
    {
        return $customer->customer_number
            ? trim($customer->customer_number.' ('.$customer->display_name.')')
            : (string) $customer->display_name;
    }

    /**
     * @return array<string, string>
     */
    protected function directorySortOptions(): array
    {
        return [
            'default' => 'Default',
            'customer' => 'Customer',
            'contact' => 'Primary Contact',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
        ];
    }

    protected function reportPdfTitle(): string
    {
        return 'MSA Customer List';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return null;
    }

    protected function reportPdfFilename(): string
    {
        return 'msa-customer-list.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Customer</th><th>Primary Contact</th><th>Work Phone</th><th>City</th><th>State</th><th>Zip</th></tr></thead><tbody>';
        foreach ($this->rows() as $customer) {
            $html .= '<tr>'
                .'<td>'.e($this->customerLabel($customer)).'</td>'
                .'<td>'.e($customer->fullName()).'</td>'
                .'<td>'.e((string) $customer->phone).'</td>'
                .'<td>'.e((string) $customer->bill_to_city).'</td>'
                .'<td>'.e((string) $customer->bill_to_state).'</td>'
                .'<td>'.e((string) $customer->bill_to_zip).'</td>'
                .'</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }

    public function render()
    {
        return view('livewire.reports.customer-directory-report', [
            'rows' => $this->rows(),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->directorySortOptions(),
        ])->layoutData([
            'title' => 'MSA Customer List',
            'windowTitle' => 'MSA Customer List',
        ]);
    }
}
