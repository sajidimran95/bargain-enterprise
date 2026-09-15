<?php

namespace App\Livewire\Banking;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Vendor;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Checks')]
class CheckIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.view'), 403);
    }

    public function createDraft(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        $bankAccount = BankAccount::query()->where('is_active', true)->orderBy('id')->first();

        if (! $bankAccount) {
            $this->dispatch('be-toast', message: 'Create a bank account first.');

            return;
        }

        $vendor = Vendor::query()->active()->orderBy('id')->first();

        Check::query()->create([
            'check_number' => $this->nextNumber(Check::class, 'check_number', 'CHK-'),
            'bank_account_id' => $bankAccount->id,
            'vendor_id' => $vendor?->id,
            'check_date' => now()->toDateString(),
            'amount' => 0,
            'payee' => $vendor?->display_name ?? 'Draft payee',
            'memo' => 'Draft check',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft check created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('banking.view'), 403);

        $rows = Check::query()
            ->with(['bankAccount', 'vendor'])
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('check_number', 'like', $like)
                        ->orWhere('payee', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('check_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Check $check) => [
                $check->check_date?->format('Y-m-d'),
                $check->check_number,
                $check->bankAccount?->name,
                $check->payee ?: $check->vendor?->display_name,
                number_format((float) $check->amount, 2, '.', ''),
                $check->memo,
            ]);

        return $this->csvDownload(
            'checks.csv',
            ['Date', 'Check #', 'Bank Account', 'Payee', 'Amount', 'Memo'],
            $rows
        );
    }

    public function render()
    {
        $checks = Check::query()
            ->with(['bankAccount', 'vendor'])
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('check_number', 'like', $like)
                        ->orWhere('payee', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('check_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.banking.check-index', [
            'checks' => $checks,
        ])->layoutData([
            'title' => 'Write Checks',
            'windowTitle' => 'Checks',
        ]);
    }
}
