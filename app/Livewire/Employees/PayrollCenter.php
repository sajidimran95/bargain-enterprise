<?php

namespace App\Livewire\Employees;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Payroll')]
class PayrollCenter extends Component
{
    public bool $payroll_enabled = false;

    public string $pay_frequency = 'biweekly';

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);

        $this->payroll_enabled = (bool) Setting::getValue('employees.payroll_enabled', false);
        $this->pay_frequency = (string) Setting::getValue('employees.pay_frequency', 'biweekly');
    }

    public function save(): void
    {
        abort_unless(auth()->check(), 403);

        $this->validate([
            'payroll_enabled' => ['boolean'],
            'pay_frequency' => ['required', 'in:weekly,biweekly,semimonthly,monthly'],
        ]);

        Setting::setValue('employees.payroll_enabled', $this->payroll_enabled, 'boolean', 'employees');
        Setting::setValue('employees.pay_frequency', $this->pay_frequency, 'string', 'employees');

        $this->dispatch('be-toast', message: $this->payroll_enabled
            ? 'Payroll is turned on.'
            : 'Payroll settings saved.');
    }

    public function render()
    {
        return view('livewire.employees.payroll-center')->layoutData([
            'title' => 'Payroll',
            'windowTitle' => 'Turn On Payroll',
        ]);
    }
}
