<?php

namespace App\Livewire\Employees;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Enter Time')]
class EnterTime extends Component
{
    public string $work_date = '';

    public string $worker_name = '';

    public string $hours = '8';

    public string $memo = '';

    /** @var list<array{date: string, name: string, hours: string, memo: string}> */
    public array $entries = [];

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);

        $this->work_date = now()->toDateString();
        $this->worker_name = (string) (auth()->user()?->name ?? '');
        $this->entries = array_values(Setting::getValue($this->storageKey(), []) ?: []);
    }

    public function saveEntry(): void
    {
        abort_unless(auth()->check(), 403);

        $this->validate([
            'work_date' => ['required', 'date'],
            'worker_name' => ['required', 'string', 'max:120'],
            'hours' => ['required', 'numeric', 'min:0.25', 'max:24'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        array_unshift($this->entries, [
            'date' => $this->work_date,
            'name' => $this->worker_name,
            'hours' => number_format((float) $this->hours, 2, '.', ''),
            'memo' => $this->memo,
        ]);
        $this->entries = array_slice($this->entries, 0, 50);

        Setting::setValue($this->storageKey(), $this->entries, 'json', 'employees');

        $this->memo = '';
        $this->dispatch('be-toast', message: 'Time entry saved.');
    }

    public function render()
    {
        return view('livewire.employees.enter-time')->layoutData([
            'title' => 'Enter Time',
            'windowTitle' => 'Enter Time',
        ]);
    }

    private function storageKey(): string
    {
        return 'employees.time_entries.'.(int) auth()->id();
    }
}
