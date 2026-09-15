<?php

namespace App\Support\Workspace;

use Illuminate\Support\Facades\Session;

class WorkspaceManager
{
    public const SESSION_TABS = 'workspace.tabs';

    public const SESSION_ACTIVE = 'workspace.active_id';

    /**
     * @return list<array{
     *     id: string,
     *     type: string,
     *     title: string,
     *     route: string,
     *     params: array<string, mixed>,
     *     url: string,
     *     closable: bool,
     *     dirty: bool,
     *     refresh_token: int
     * }>
     */
    public function tabs(): array
    {
        $tabs = Session::get(self::SESSION_TABS, []);

        if (! is_array($tabs) || $tabs === []) {
            $tabs = [$this->makeHomeTab()];
            $this->persist($tabs, $tabs[0]['id']);

            return array_values($tabs);
        }

        $repaired = [];
        $changed = false;

        foreach ($tabs as $tab) {
            if (! is_array($tab) || blank($tab['id'] ?? null)) {
                $changed = true;

                continue;
            }

            // Never embed the workspace shell itself (causes nested chrome).
            $route = (string) ($tab['route'] ?? '');
            if ($route === '' || $route === 'dashboard') {
                $route = 'dashboard.home';
                $tab['route'] = $route;
                $tab['id'] = $tab['id'] === 'dashboard' ? 'dashboard' : $tab['id'];
                $changed = true;
            }

            $params = is_array($tab['params'] ?? null) ? $tab['params'] : [];
            $freshUrl = WorkspaceCatalog::embedUrl($route, $params, (string) $tab['id']);

            if (($tab['url'] ?? null) !== $freshUrl) {
                $tab['url'] = $freshUrl;
                $changed = true;
            }

            // Drop accidental duplicate shell tabs.
            if (str_contains((string) ($tab['url'] ?? ''), '/dashboard?') || rtrim((string) ($tab['url'] ?? ''), '/') === url('/dashboard')) {
                if ($tab['id'] !== 'dashboard') {
                    $changed = true;

                    continue;
                }
                $tab = $this->makeHomeTab();
                $changed = true;
            }

            $repaired[] = $tab;
        }

        if ($repaired === []) {
            $repaired = [$this->makeHomeTab()];
            $changed = true;
        }

        // Ensure home tab exists and always points at dashboard.home embed (never /dashboard shell).
        $hasHome = false;
        foreach ($repaired as $i => $tab) {
            if (($tab['id'] ?? null) !== 'dashboard') {
                continue;
            }

            $hasHome = true;
            $home = $this->makeHomeTab();
            $needsHomeFix = ($tab['route'] ?? null) !== 'dashboard.home'
                || ($tab['url'] ?? null) !== $home['url'];

            if ($needsHomeFix) {
                $repaired[$i] = array_merge($home, [
                    'dirty' => (bool) ($tab['dirty'] ?? false),
                    'refresh_token' => (int) ($tab['refresh_token'] ?? 0),
                ]);
                $changed = true;
            }
        }

        if (! $hasHome) {
            array_unshift($repaired, $this->makeHomeTab());
            $changed = true;
        }

        if ($changed) {
            $active = Session::get(self::SESSION_ACTIVE, 'dashboard');
            if ($this->findIn($repaired, (string) $active) === null) {
                $active = $repaired[0]['id'];
            }
            $this->persist($repaired, (string) $active);
        }

        return array_values($repaired);
    }

    public function activeId(): string
    {
        $tabs = $this->tabs();
        $active = Session::get(self::SESSION_ACTIVE);

        if (is_string($active) && $this->findTab($active) !== null) {
            return $active;
        }

        return $tabs[0]['id'] ?? 'dashboard';
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function openTab(string $routeName, array $params = [], ?string $title = null, ?string $id = null): array
    {
        $definition = WorkspaceCatalog::get($routeName);
        if ($definition === null) {
            throw new \InvalidArgumentException("Unknown workspace route [{$routeName}].");
        }

        $permission = $definition['permission'];
        if (is_string($permission) && $permission !== '' && auth()->check() && ! auth()->user()?->hasPermission($permission)) {
            abort(403);
        }

        $tabId = $id ?? WorkspaceCatalog::tabId($routeName, $params);

        if (in_array($routeName, ['dashboard', 'dashboard.home'], true)) {
            $tabId = 'dashboard';
            $existing = $this->findTab('dashboard');
            if ($existing === null) {
                $tabs = $this->tabs();
                $this->persist($tabs, 'dashboard');
            } else {
                $this->activateTab('dashboard');
            }

            return $this->findTab('dashboard') ?? $this->makeHomeTab();
        }

        $existing = $this->findTab($tabId);

        if ($existing !== null) {
            if ($title !== null) {
                $this->updateTitle($tabId, $title);
            }
            $this->activateTab($tabId);

            return $this->findTab($tabId) ?? $existing;
        }

        $tab = [
            'id' => $tabId,
            'type' => $definition['type'],
            'title' => WorkspaceCatalog::titleFor($routeName, $params, $title),
            'route' => $routeName,
            'params' => $params,
            'url' => WorkspaceCatalog::embedUrl($routeName, $params, $tabId),
            'closable' => (bool) $definition['closable'],
            'dirty' => false,
            'refresh_token' => 0,
        ];

        $tabs = $this->tabs();
        $tabs[] = $tab;
        $this->persist($tabs, $tabId);

        return $tab;
    }

    public function activateTab(string $id): void
    {
        if ($this->findTab($id) === null) {
            return;
        }

        Session::put(self::SESSION_ACTIVE, $id);
    }

    public function closeTab(string $id): void
    {
        $tabs = $this->tabs();
        $index = null;

        foreach ($tabs as $i => $tab) {
            if ($tab['id'] === $id) {
                if (! ($tab['closable'] ?? true)) {
                    return;
                }
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return;
        }

        $wasActive = $this->activeId() === $id;
        array_splice($tabs, $index, 1);

        if ($tabs === []) {
            $tabs = [$this->makeHomeTab()];
        }

        $newActive = $this->activeId();
        if ($wasActive) {
            $newActive = $tabs[max(0, $index - 1)]['id'] ?? $tabs[0]['id'];
        } elseif ($this->findIn($tabs, $newActive) === null) {
            $newActive = $tabs[0]['id'];
        }

        $this->persist($tabs, $newActive);
    }

    public function closeOtherTabs(string $id): void
    {
        $keep = $this->findTab($id);
        $home = $this->findTab('dashboard') ?? $this->makeHomeTab();

        $tabs = [];
        if (($home['id'] ?? '') !== ($keep['id'] ?? null)) {
            $tabs[] = $home;
        }
        if ($keep !== null) {
            $tabs[] = $keep;
        }
        if ($tabs === []) {
            $tabs[] = $this->makeHomeTab();
        }

        // Deduplicate by id
        $unique = [];
        foreach ($tabs as $tab) {
            $unique[$tab['id']] = $tab;
        }
        $tabs = array_values($unique);

        $this->persist($tabs, $keep['id'] ?? $tabs[0]['id']);
    }

    public function closeAllTabs(): void
    {
        $home = $this->makeHomeTab();
        $this->persist([$home], $home['id']);
    }

    public function isTabOpen(string $id): bool
    {
        return $this->findTab($id) !== null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findTab(string $id): ?array
    {
        return $this->findIn($this->tabs(), $id);
    }

    public function refreshTab(string $id): void
    {
        $tabs = $this->tabs();
        foreach ($tabs as $i => $tab) {
            if ($tab['id'] !== $id) {
                continue;
            }
            $tabs[$i]['refresh_token'] = (int) ($tab['refresh_token'] ?? 0) + 1;
            $tabs[$i]['dirty'] = false;
            $this->persist($tabs, $this->activeId());

            return;
        }
    }

    public function setDirty(string $id, bool $dirty): void
    {
        $tabs = $this->tabs();
        foreach ($tabs as $i => $tab) {
            if ($tab['id'] !== $id) {
                continue;
            }
            $tabs[$i]['dirty'] = $dirty;
            $this->persist($tabs, $this->activeId());

            return;
        }
    }

    public function updateTitle(string $id, string $title): void
    {
        $tabs = $this->tabs();
        foreach ($tabs as $i => $tab) {
            if ($tab['id'] !== $id) {
                continue;
            }
            $tabs[$i]['title'] = $title;
            $this->persist($tabs, $this->activeId());

            return;
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function makeHomeTab(): array
    {
        return [
            'id' => 'dashboard',
            'type' => 'dashboard',
            'title' => 'Home Page',
            'route' => 'dashboard.home',
            'params' => [],
            'url' => WorkspaceCatalog::embedUrl('dashboard.home', [], 'dashboard'),
            'closable' => false,
            'dirty' => false,
            'refresh_token' => 0,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $tabs
     */
    protected function persist(array $tabs, string $activeId): void
    {
        Session::put(self::SESSION_TABS, array_values($tabs));
        Session::put(self::SESSION_ACTIVE, $activeId);
    }

    /**
     * @param  list<array<string, mixed>>  $tabs
     * @return array<string, mixed>|null
     */
    protected function findIn(array $tabs, string $id): ?array
    {
        foreach ($tabs as $tab) {
            if (($tab['id'] ?? null) === $id) {
                return $tab;
            }
        }

        return null;
    }
}
