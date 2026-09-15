<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Customer Contact List')]
class CustomerDirectoryReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'all';
        $this->applyDatePreset('all');
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (Customer $customer) => [
            $customer->display_name,
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

    protected function rows()
    {
        return Customer::query()
            ->active()
            ->orderBy('display_name')
            ->get();
    }

    protected function reportPdfTitle(): string
    {
        return 'Customer Contact List';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return null;
    }

    protected function reportPdfFilename(): string
    {
        return 'customer-directory.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Customer</th><th>Primary Contact</th><th>Work Phone</th><th>City</th><th>State</th><th>Zip</th></tr></thead><tbody>';
        foreach ($this->rows() as $customer) {
            $html .= '<tr>'
                .'<td>'.e($customer->display_name).'</td>'
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
        ])->layoutData([
            'title' => 'Customer Contact List',
            'windowTitle' => 'Customer Contact List',
        ]);
    }
}
