<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Settings')]
class CompanySettings extends Component
{
    use AuthorizesRequests;

    public string $company_name = '';

    public string $negative_policy = 'WARN';

    public bool $allow_manager_override = true;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('settings.manage'), 403);

        $this->company_name = (string) Setting::getValue('company.name', config('bargain.company_name'));
        $this->negative_policy = (string) Setting::getValue(
            'inventory.negative_policy',
            config('bargain.inventory.negative_policy', 'WARN')
        );
        $this->allow_manager_override = (bool) Setting::getValue(
            'inventory.allow_manager_override',
            config('bargain.inventory.allow_manager_override', true)
        );
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->hasPermission('settings.manage'), 403);

        $this->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'negative_policy' => ['required', 'in:ALLOW,WARN,BLOCK'],
            'allow_manager_override' => ['boolean'],
        ]);

        Setting::setValue('company.name', $this->company_name, 'string', 'company');
        Setting::setValue('inventory.negative_policy', $this->negative_policy, 'string', 'inventory');
        Setting::setValue('inventory.allow_manager_override', $this->allow_manager_override, 'boolean', 'inventory');

        config(['bargain.company_name' => $this->company_name]);

        $this->dispatch('be-toast', message: 'Settings saved.');
    }

    public function render()
    {
        return view('livewire.settings.company-settings')->layoutData([
            'title' => 'Settings',
            'windowTitle' => 'Settings',
        ]);
    }
}
