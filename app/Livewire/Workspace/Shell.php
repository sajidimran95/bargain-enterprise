<?php

namespace App\Livewire\Workspace;

use App\Support\Workspace\WorkspaceManager;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Bargain Enterprise')]
class Shell extends Component
{
    /** @var list<array<string, mixed>> */
    public array $tabs = [];

    public string $activeTabId = 'dashboard';

    #[Url(as: 'open')]
    public ?string $open = null;

    public function boot(WorkspaceManager $workspace): void
    {
        $this->syncFromManager($workspace);
    }

    public function mount(WorkspaceManager $workspace): void
    {
        // If this shell is ever loaded inside a tab iframe, bounce to home content.
        if (request()->boolean('embed') || request()->header('Sec-Fetch-Dest') === 'iframe') {
            $this->redirect(route('dashboard.home', [
                'embed' => 1,
                'tab_id' => 'dashboard',
            ]));

            return;
        }

        if (filled($this->open)) {
            $routeName = $this->open;
            $params = request()->except(['open', 'embed', 'workspace_bypass']);
            unset($params['open']);

            try {
                $workspace->openTab($routeName, $this->normalizeParams($routeName, $params));
            } catch (\Throwable) {
                // Ignore unknown open targets; shell still loads.
            }

            $this->open = null;
        }

        $this->syncFromManager($workspace);
    }

    #[On('workspace-open')]
    public function handleWorkspaceOpen(string $route, array $params = [], ?string $title = null, ?string $id = null): void
    {
        $this->openRoute($route, $params, $title, $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function openRoute(string $routeName, array $params = [], ?string $title = null, ?string $id = null): void
    {
        app(WorkspaceManager::class)->openTab($routeName, $params, $title, $id);
        $this->syncFromManager(app(WorkspaceManager::class));
        $this->dispatch('workspace-tabs-updated');
    }

    public function activateTab(string $id): void
    {
        app(WorkspaceManager::class)->activateTab($id);
        $this->syncFromManager(app(WorkspaceManager::class));
        $this->dispatch('workspace-tabs-updated');
    }

    public function closeTab(string $id, bool $force = false): void
    {
        $manager = app(WorkspaceManager::class);
        $tab = $manager->findTab($id);

        if ($tab === null) {
            return;
        }

        if (! $force && ($tab['dirty'] ?? false)) {
            $this->dispatch('workspace-confirm-close', id: $id, title: $tab['title'] ?? 'Tab');

            return;
        }

        $manager->closeTab($id);
        $this->syncFromManager($manager);
        $this->dispatch('workspace-tabs-updated');
    }

    public function closeOtherTabs(string $id): void
    {
        $manager = app(WorkspaceManager::class);
        foreach ($manager->tabs() as $tab) {
            if ($tab['id'] === $id || ! ($tab['closable'] ?? true)) {
                continue;
            }
            if ($tab['dirty'] ?? false) {
                $this->dispatch('be-toast', message: 'Close or discard unsaved tabs before Close Others.');

                return;
            }
        }

        $manager->closeOtherTabs($id);
        $this->syncFromManager($manager);
        $this->dispatch('workspace-tabs-updated');
    }

    public function closeAllTabs(): void
    {
        $manager = app(WorkspaceManager::class);
        foreach ($manager->tabs() as $tab) {
            if (($tab['dirty'] ?? false) && ($tab['closable'] ?? true)) {
                $this->dispatch('be-toast', message: 'Close or discard unsaved tabs before Close All.');

                return;
            }
        }

        $manager->closeAllTabs();
        $this->syncFromManager($manager);
        $this->dispatch('workspace-tabs-updated');
    }

    public function refreshTab(string $id): void
    {
        app(WorkspaceManager::class)->refreshTab($id);
        $this->syncFromManager(app(WorkspaceManager::class));
        $this->dispatch('workspace-tabs-updated');
    }

    public function setTabDirty(string $id, bool $dirty = true): void
    {
        app(WorkspaceManager::class)->setDirty($id, $dirty);
        $this->syncFromManager(app(WorkspaceManager::class));
    }

    public function setTabTitle(string $id, string $title): void
    {
        app(WorkspaceManager::class)->updateTitle($id, $title);
        $this->syncFromManager(app(WorkspaceManager::class));
    }

    protected function syncFromManager(WorkspaceManager $workspace): void
    {
        $this->tabs = $workspace->tabs();
        $this->activeTabId = $workspace->activeId();
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    protected function normalizeParams(string $routeName, array $params): array
    {
        $allowed = match ($routeName) {
            'customers.edit', 'customers.show' => ['customer'],
            'vendors.edit', 'vendors.show' => ['vendor'],
            'items.edit' => ['item'],
            'credit-memos.apply' => ['creditMemo'],
            default => [],
        };

        if ($allowed === []) {
            return [];
        }

        return collect($params)
            ->only($allowed)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();
    }

    public function render()
    {
        return view('livewire.workspace.shell')->layoutData([
            'title' => 'Home',
            'windowTitle' => collect($this->tabs)->firstWhere('id', $this->activeTabId)['title'] ?? 'Home Page',
            'workspaceMode' => true,
            'workspaceTabs' => $this->tabs,
            'workspaceActiveId' => $this->activeTabId,
        ]);
    }
}
