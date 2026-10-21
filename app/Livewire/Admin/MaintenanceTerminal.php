<?php

namespace App\Livewire\Admin;

use App\Support\Maintenance\MaintenanceCommands;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Maintenance Terminal')]
class MaintenanceTerminal extends Component
{
    public string $output = '';

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    public function runScript(string $script, MaintenanceCommands $commands): void
    {
        $this->authorizeAdmin();

        if (! array_key_exists($script, $commands->scripts())) {
            abort(404);
        }

        $this->output = $commands->run($script);
    }

    public function render(MaintenanceCommands $commands)
    {
        return view('livewire.admin.maintenance-terminal', [
            'scripts' => $commands->scripts(),
        ])->layoutData([
            'title' => 'Maintenance Terminal',
            'windowTitle' => 'Maintenance Terminal',
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole(['owner', 'admin']) === true, 403);
    }
}
