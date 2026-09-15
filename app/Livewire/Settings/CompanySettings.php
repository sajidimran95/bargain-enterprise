<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Preferences')]
class CompanySettings extends Component
{
    public string $negative_policy = 'WARN';

    public bool $allow_manager_override = true;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('settings.manage'), 403);

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
            'negative_policy' => ['required', 'in:ALLOW,WARN,BLOCK'],
            'allow_manager_override' => ['boolean'],
        ]);

        Setting::setValue('inventory.negative_policy', $this->negative_policy, 'string', 'inventory');
        Setting::setValue('inventory.allow_manager_override', $this->allow_manager_override, 'boolean', 'inventory');

        $this->dispatch('be-toast', message: 'Preferences saved.');
    }

    public function render()
    {
        return view('livewire.settings.company-settings')->layoutData([
            'title' => 'Preferences',
            'windowTitle' => 'Preferences',
        ]);
    }
}
