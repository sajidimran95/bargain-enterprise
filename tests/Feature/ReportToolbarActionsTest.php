<?php

namespace Tests\Feature;

use App\Livewire\Reports\CustomerOpenBalanceReport;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportToolbarActionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create([
            'email' => 'owner@example.com',
        ]);
        $this->owner->assignRole('owner');
    }

    public function test_comment_share_memorize_email_popups_and_restore(): void
    {
        $this->actingAs($this->owner);

        $component = Livewire::test(CustomerOpenBalanceReport::class)
            ->call('commentOnReport')
            ->assertSet('showCommentModal', true)
            ->set('reportComment', 'Follow up balance')
            ->call('saveReportComment')
            ->assertSet('showCommentModal', false)
            ->assertSet('reportComment', 'Follow up balance')
            ->call('shareReportTemplate')
            ->assertSet('showShareModal', true)
            ->assertSet('showCommentModal', false)
            ->call('copyShareUrl')
            ->assertDispatched('be-toast')
            ->set('datePreset', 'last_month')
            ->set('sortBy', 'amount')
            ->set('basis', 'cash')
            ->call('memorizeReport')
            ->assertSet('showMemorizeModal', true)
            ->set('memorizeName', 'Open Balance — Last Month')
            ->call('saveMemorizedReport')
            ->assertSet('showMemorizeModal', false)
            ->call('openEmailModal')
            ->assertSet('showEmailModal', true)
            ->assertSet('emailTo', 'owner@example.com');

        $this->assertNotSame('', $component->get('shareUrl'));
        $this->assertStringContainsString('Customer Open Balance', (string) $component->get('emailSubject'));

        $this->assertSame(
            'Follow up balance',
            Setting::getValue('report.comment.'.$this->owner->id.'.customer-open-balance-report')
        );

        $memorized = Setting::getValue('report.memorized.'.$this->owner->id.'.customer-open-balance-report');
        $this->assertIsArray($memorized);
        $this->assertSame('Open Balance — Last Month', $memorized['name']);
        $this->assertSame('last_month', $memorized['datePreset']);
        $this->assertSame('amount', $memorized['sortBy']);
        $this->assertSame('cash', $memorized['basis']);

        Livewire::test(CustomerOpenBalanceReport::class)
            ->assertSet('datePreset', 'last_month')
            ->assertSet('sortBy', 'amount')
            ->assertSet('basis', 'cash')
            ->assertSet('reportComment', 'Follow up balance');
    }

    public function test_excel_and_pdf_exports_stream(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(CustomerOpenBalanceReport::class)
            ->call('exportExcel')
            ->assertFileDownloaded('customer-open-balance.xlsm');

        Livewire::test(CustomerOpenBalanceReport::class)
            ->call('exportPdf')
            ->assertFileDownloaded('customer-open-balance.pdf');
    }
}
