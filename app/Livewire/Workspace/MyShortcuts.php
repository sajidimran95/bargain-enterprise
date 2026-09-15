<?php

namespace App\Livewire\Workspace;

use App\Support\ErpShortcuts;
use Livewire\Component;

class MyShortcuts extends Component
{
    public bool $showAdd = false;

    public function mount(): void
    {
        abort_unless(auth()->check(), 403);
    }

    public function openAdd(): void
    {
        $this->showAdd = true;
    }

    public function closeAdd(): void
    {
        $this->showAdd = false;
    }

    public function addShortcut(string $key): void
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);
        ErpShortcuts::add($user, $key);
        $this->dispatch('be-toast', message: 'Shortcut added.');
    }

    public function removeShortcut(string $key): void
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);
        ErpShortcuts::remove($user, $key);
        $this->dispatch('be-toast', message: 'Shortcut removed.');
    }

    public function resetShortcuts(): void
    {
        $user = auth()->user();
        abort_unless($user !== null, 403);
        ErpShortcuts::reset($user);
        $this->dispatch('be-toast', message: 'Shortcuts reset to defaults.');
    }

    public function render()
    {
        $user = auth()->user();

        /** @var list<array{id?: string, title?: string, route?: string, params?: array<string, mixed>}> $openWindows */
        $openWindows = view()->shared('workspaceTabs', []);
        if (! is_array($openWindows)) {
            $openWindows = [];
        }

        return view('livewire.workspace.my-shortcuts', [
            'shortcuts' => ErpShortcuts::forUser($user),
            'available' => ErpShortcuts::availableToAdd($user),
            'openWindows' => $openWindows,
            'activeWindowId' => view()->shared('workspaceActiveId'),
            'currentWindowTitle' => view()->shared('windowTitle', 'Home Page'),
        ]);
    }
}
