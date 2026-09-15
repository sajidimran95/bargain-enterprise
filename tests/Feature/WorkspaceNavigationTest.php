<?php

namespace Tests\Feature;

use App\Livewire\Sales\CreditMemoIndex;
use App\Livewire\Sales\InvoiceIndex;
use App\Livewire\Workspace\Shell;
use App\Models\User;
use App\Support\Workspace\WorkspaceManager;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WorkspaceNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_dashboard_renders_workspace_shell_with_home_tab(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('data-workspace-shell', false)
            ->assertSee('Home Page')
            ->assertSee('My Shortcuts');
    }

    public function test_module_routes_redirect_into_workspace_unless_embedded(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('customers.index', ['workspace_test_redirect' => 1]))
            ->assertRedirect(route('dashboard', ['open' => 'customers.index']));

        $this->get(route('customers.index', ['embed' => 1]))
            ->assertOk()
            ->assertSee('Customer');
    }

    public function test_shell_opens_activates_and_closes_tabs_without_duplicates(): void
    {
        $this->actingAs($this->owner);

        $component = Livewire::test(Shell::class)
            ->call('openRoute', 'customers.index')
            ->assertSet('activeTabId', 'customers.index')
            ->call('openRoute', 'items.index')
            ->assertSet('activeTabId', 'items.index')
            ->call('openRoute', 'customers.index')
            ->assertSet('activeTabId', 'customers.index');

        $tabs = collect($component->get('tabs'));
        $this->assertSame(1, $tabs->where('id', 'customers.index')->count());
        $this->assertTrue($tabs->contains(fn ($tab) => $tab['id'] === 'items.index'));

        $component->call('closeTab', 'items.index');
        $ids = collect($component->get('tabs'))->pluck('id');
        $this->assertFalse($ids->contains('items.index'));
        $this->assertTrue($ids->contains('customers.index'));
        $this->assertTrue($ids->contains('dashboard'));

        $component->call('closeAllTabs')
            ->assertSet('activeTabId', 'dashboard');
        $this->assertCount(1, $component->get('tabs'));
    }

    public function test_close_others_keeps_home_and_target(): void
    {
        $this->actingAs($this->owner);

        $component = Livewire::test(Shell::class)
            ->call('openRoute', 'customers.index')
            ->call('openRoute', 'vendors.index')
            ->call('openRoute', 'invoices.index')
            ->call('closeOtherTabs', 'vendors.index');

        $ids = collect($component->get('tabs'))->pluck('id')->sort()->values()->all();
        $this->assertSame(['dashboard', 'vendors.index'], $ids);
    }

    public function test_refresh_tab_bumps_token(): void
    {
        $this->actingAs($this->owner);

        $component = Livewire::test(Shell::class)
            ->call('openRoute', 'reports.index')
            ->call('refreshTab', 'reports.index');

        $tab = collect($component->get('tabs'))->firstWhere('id', 'reports.index');
        $this->assertSame(1, $tab['refresh_token']);
    }

    public function test_workspace_manager_persists_in_session(): void
    {
        $this->actingAs($this->owner);

        $manager = app(WorkspaceManager::class);
        $manager->openTab('inventory.index');
        $manager->openTab('banking.index');

        $this->assertTrue($manager->isTabOpen('inventory.index'));
        $this->assertSame('banking.index', $manager->activeId());

        $manager->closeTab('banking.index');
        $this->assertSame('inventory.index', $manager->activeId());
    }

    public function test_dirty_tab_close_dispatches_confirm_instead_of_closing(): void
    {
        $this->actingAs($this->owner);

        $component = Livewire::test(Shell::class)
            ->call('openRoute', 'invoices.create')
            ->call('setTabDirty', 'invoices.create', true)
            ->call('closeTab', 'invoices.create')
            ->assertDispatched('workspace-confirm-close');

        $this->assertTrue(
            collect($component->get('tabs'))->contains(fn ($tab) => $tab['id'] === 'invoices.create')
        );

        $component->call('closeTab', 'invoices.create', true);
        $this->assertFalse(
            collect($component->get('tabs'))->contains(fn ($tab) => $tab['id'] === 'invoices.create')
        );
    }

    public function test_list_create_button_opens_via_workspace_bridge(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(CreditMemoIndex::class)
            ->assertSee('be-workspace-open', false)
            ->assertSee('credit-memos.create', false)
            ->assertSee('New Credit Memo');

        Livewire::test(InvoiceIndex::class)
            ->assertSee('be-workspace-open', false)
            ->assertSee('invoices.create', false)
            ->assertSee('Create Invoices');
    }

    public function test_iframe_module_request_stays_embedded_instead_of_nesting_shell(): void
    {
        $this->actingAs($this->owner);

        $this->withHeaders(['Sec-Fetch-Dest' => 'iframe'])
            ->get(route('credit-memos.index'))
            ->assertRedirect();

        $redirect = $this->withHeaders(['Sec-Fetch-Dest' => 'iframe'])
            ->get(route('credit-memos.index'))
            ->headers->get('Location');

        $this->assertNotNull($redirect);
        $this->assertStringContainsString('embed=1', $redirect);
        $this->assertStringNotContainsString('/dashboard?', $redirect);
    }

    public function test_shell_inside_iframe_redirects_to_home_embed(): void
    {
        $this->actingAs($this->owner);

        Livewire::withHeaders(['Sec-Fetch-Dest' => 'iframe'])
            ->test(Shell::class)
            ->assertRedirect(route('dashboard.home', [
                'embed' => 1,
                'tab_id' => 'dashboard',
            ]));
    }

    public function test_embed_home_does_not_render_desktop_chrome(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('dashboard.home', ['embed' => 1]))
            ->assertOk()
            ->assertSee('data-workspace-embed', false)
            ->assertDontSee('My Shortcuts')
            ->assertDontSee('Open Windows');
    }

    public function test_embed_home_insights_tab_keeps_embed_query(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('dashboard.home', ['embed' => 1]))
            ->assertOk()
            ->assertSee('Insights')
            ->assertSee('embed=1', false);

        $this->get(route('dashboard.snapshots', ['embed' => 1]))
            ->assertOk()
            ->assertSee('Insights')
            ->assertSee('data-workspace-embed', false)
            ->assertDontSee('My Shortcuts')
            ->assertSee('Company');
    }

    public function test_home_tab_url_never_points_at_shell_route(): void
    {
        $this->actingAs($this->owner);

        $manager = app(WorkspaceManager::class);
        session([
            WorkspaceManager::SESSION_TABS => [[
                'id' => 'dashboard',
                'type' => 'dashboard',
                'title' => 'Home Page',
                'route' => 'dashboard',
                'params' => [],
                'url' => '/dashboard',
                'closable' => false,
                'dirty' => false,
                'refresh_token' => 0,
            ]],
            WorkspaceManager::SESSION_ACTIVE => 'dashboard',
        ]);

        $home = $manager->findTab('dashboard');
        $this->assertSame('dashboard.home', $home['route']);
        $this->assertStringContainsString('embed=1', $home['url']);
        $this->assertStringContainsString('dashboard/home', $home['url']);
    }
}
