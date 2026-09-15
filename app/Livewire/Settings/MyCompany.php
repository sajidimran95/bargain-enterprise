<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('My Company')]
class MyCompany extends Component
{
    public string $company_name = '';

    public string $legal_name = '';

    public string $phone = '';

    public string $fax = '';

    public string $email = '';

    public string $website = '';

    public string $address1 = '';

    public string $address2 = '';

    public string $city = '';

    public string $state = '';

    public string $zip = '';

    public string $country = '';

    public string $federal_ein = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('settings.manage'), 403);

        $this->company_name = (string) Setting::getValue('company.name', config('bargain.company_name'));
        $this->legal_name = (string) Setting::getValue('company.legal_name', $this->company_name);
        $this->phone = (string) Setting::getValue('company.phone', '');
        $this->fax = (string) Setting::getValue('company.fax', '');
        $this->email = (string) Setting::getValue('company.email', '');
        $this->website = (string) Setting::getValue('company.website', '');
        $this->address1 = (string) Setting::getValue('company.address1', '');
        $this->address2 = (string) Setting::getValue('company.address2', '');
        $this->city = (string) Setting::getValue('company.city', '');
        $this->state = (string) Setting::getValue('company.state', '');
        $this->zip = (string) Setting::getValue('company.zip', '');
        $this->country = (string) Setting::getValue('company.country', 'USA');
        $this->federal_ein = (string) Setting::getValue('company.federal_ein', '');
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->hasPermission('settings.manage'), 403);

        $this->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:50'],
            'zip' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'federal_ein' => ['nullable', 'string', 'max:50'],
        ]);

        Setting::setValue('company.name', $this->company_name, 'string', 'company');
        Setting::setValue('company.legal_name', $this->legal_name ?: $this->company_name, 'string', 'company');
        Setting::setValue('company.phone', $this->phone, 'string', 'company');
        Setting::setValue('company.fax', $this->fax, 'string', 'company');
        Setting::setValue('company.email', $this->email, 'string', 'company');
        Setting::setValue('company.website', $this->website, 'string', 'company');
        Setting::setValue('company.address1', $this->address1, 'string', 'company');
        Setting::setValue('company.address2', $this->address2, 'string', 'company');
        Setting::setValue('company.city', $this->city, 'string', 'company');
        Setting::setValue('company.state', $this->state, 'string', 'company');
        Setting::setValue('company.zip', $this->zip, 'string', 'company');
        Setting::setValue('company.country', $this->country, 'string', 'company');
        Setting::setValue('company.federal_ein', $this->federal_ein, 'string', 'company');

        config(['bargain.company_name' => $this->company_name]);

        $this->dispatch('be-toast', message: 'Company information saved.');
    }

    public function render()
    {
        return view('livewire.settings.my-company')->layoutData([
            'title' => 'My Company',
            'windowTitle' => 'Company Information',
        ]);
    }
}
