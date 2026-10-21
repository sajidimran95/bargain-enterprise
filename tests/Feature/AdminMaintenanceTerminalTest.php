<?php

namespace Tests\Feature;

use App\Livewire\Admin\MaintenanceTerminal;
use App\Models\User;
use App\Support\Maintenance\MaintenanceCommands;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminMaintenanceTerminalTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.terminal'))
            ->assertRedirect(route('login'));
    }

    public function test_sales_user_cannot_open_the_maintenance_terminal(): void
    {
        $sales = User::factory()->create();
        $sales->assignRole('sales');

        $this->actingAs($sales)
            ->get(route('admin.terminal'))
            ->assertForbidden();
    }

    public function test_admin_can_open_the_maintenance_terminal(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.terminal'))
            ->assertSee('Cache clear')
            ->assertSee('Optimize clear')
            ->assertSee('Run migrations')
            ->assertSee("runScript('cache')", false)
            ->assertSee("runScript('optimize')", false)
            ->assertSee("runScript('migrate')", false);
    }

    public function test_admin_sees_maintenance_terminal_in_the_company_menu(): void
    {
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertSee('Maintenance Terminal');
    }

    public function test_sales_user_does_not_see_maintenance_terminal_in_the_company_menu(): void
    {
        $sales = User::factory()->create();
        $sales->assignRole('sales');

        $this->actingAs($sales)
            ->get(route('dashboard'))
            ->assertDontSee('Maintenance Terminal');
    }

    public function test_admin_sees_the_result_of_a_maintenance_script(): void
    {
        $this->mock(MaintenanceCommands::class, function ($mock): void {
            $mock->shouldReceive('scripts')->andReturn([
                'cache' => [
                    'label' => 'Cache clear',
                    'command' => 'cache:clear',
                    'parameters' => [],
                ],
            ]);
            $mock->shouldReceive('run')->once()->with('cache')->andReturn('Application cache cleared successfully.');
        });

        Livewire::actingAs($this->admin)
            ->test(MaintenanceTerminal::class)
            ->call('runScript', 'cache')
            ->assertSee('Application cache cleared successfully.');
    }

    #[DataProvider('maintenanceScripts')]
    public function test_maintenance_script_calls_the_matching_artisan_command(
        string $script,
        string $command,
        array $parameters,
        string $output,
    ): void {
        Artisan::shouldReceive('call')->once()->with($command, $parameters)->andReturn(0);
        Artisan::shouldReceive('output')->once()->andReturn($output);

        $this->assertSame($output, app(MaintenanceCommands::class)->run($script));
    }

    public function test_blank_command_output_is_reported_as_finished(): void
    {
        Artisan::shouldReceive('call')->once()->with('cache:clear', [])->andReturn(0);
        Artisan::shouldReceive('output')->once()->andReturn('   ');

        $this->assertSame('Finished.', app(MaintenanceCommands::class)->run('cache'));
    }

    public function test_failed_command_includes_the_exit_code(): void
    {
        Artisan::shouldReceive('call')->once()->with('migrate', ['--force' => true])->andReturn(1);
        Artisan::shouldReceive('output')->once()->andReturn('Migration failed.');

        $this->assertSame("Migration failed.\nExit code: 1", app(MaintenanceCommands::class)->run('migrate'));
    }

    public function test_destructive_commands_are_rejected(): void
    {
        Artisan::shouldReceive('call')->never();

        $this->expectException(InvalidArgumentException::class);

        app(MaintenanceCommands::class)->run('migrate:fresh');
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: array<string, mixed>, 3: string}>
     */
    public static function maintenanceScripts(): array
    {
        return [
            'cache clear' => ['cache', 'cache:clear', [], 'Application cache cleared successfully.'],
            'optimize clear' => ['optimize', 'optimize:clear', [], 'Cached files cleared.'],
            'migrations' => ['migrate', 'migrate', ['--force' => true], 'Nothing to migrate.'],
        ];
    }
}
