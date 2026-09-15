<?php

namespace App\Livewire\Accounting;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Services\AccountingService;
use App\Support\DocumentNumbers;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Journal Entries')]
class JournalIndex extends Component
{
    use WithErpListActions;
    use WithPagination;

    #[Url]
    public ?int $selectedId = null;

    public bool $showForm = false;

    /** @var array<string, mixed> */
    public array $form = [
        'entry_date' => '',
        'memo' => '',
        'debit_account_id' => '',
        'credit_account_id' => '',
        'amount' => '',
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.view'), 403);
        $this->form['entry_date'] = now()->toDateString();
    }

    public function selectEntry(int $id): void
    {
        $this->selectedId = $this->selectedId === $id ? null : $id;
    }

    public function newEntry(): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.manage'), 403);
        $this->form = [
            'entry_date' => now()->toDateString(),
            'memo' => '',
            'debit_account_id' => '',
            'credit_account_id' => '',
            'amount' => '',
        ];
        $this->showForm = true;
    }

    public function cancelForm(): void
    {
        $this->showForm = false;
    }

    public function saveEntry(AccountingService $accounting): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.manage'), 403);

        $validated = $this->validate([
            'form.entry_date' => ['required', 'date'],
            'form.memo' => ['nullable', 'string', 'max:255'],
            'form.debit_account_id' => ['required', 'exists:accounts,id'],
            'form.credit_account_id' => ['required', 'exists:accounts,id', 'different:form.debit_account_id'],
            'form.amount' => ['required', 'numeric', 'min:0.01'],
        ])['form'];

        $entryNumber = DocumentNumbers::next(JournalEntry::class, 'entry_number', 'JE-');

        $debitAccount = Account::query()->findOrFail($validated['debit_account_id']);
        $creditAccount = Account::query()->findOrFail($validated['credit_account_id']);

        try {
            $entry = $accounting->postBalancedEntry(
                $entryNumber,
                $validated['entry_date'],
                [
                    ['account' => $debitAccount, 'debit' => $validated['amount'], 'credit' => 0],
                    ['account' => $creditAccount, 'debit' => 0, 'credit' => $validated['amount']],
                ],
                $validated['memo'] ?: 'Manual journal entry',
                null,
                null,
                auth()->id()
            );
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        $this->showForm = false;
        $this->selectedId = $entry->id;
        $this->dispatch('be-toast', message: 'Journal entry '.$entry->entry_number.' posted.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('accounting.view'), 403);

        $rows = JournalEntry::query()
            ->withCount('lines')
            ->withSum('lines as debit_total', 'debit')
            ->withSum('lines as credit_total', 'credit')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('entry_number', 'like', $like)
                        ->orWhere('memo', 'like', $like);
                });
            })
            ->orderByDesc('entry_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (JournalEntry $entry) => [
                $entry->entry_date?->format('Y-m-d'),
                $entry->entry_number,
                $entry->memo,
                $entry->lines_count,
                number_format((float) ($entry->debit_total ?? 0), 2, '.', ''),
                number_format((float) ($entry->credit_total ?? 0), 2, '.', ''),
            ]);

        return $this->csvDownload(
            'journal-entries.csv',
            ['Date', 'Number', 'Memo', 'Lines', 'Debit Total', 'Credit Total'],
            $rows
        );
    }

    public function render()
    {
        $entries = JournalEntry::query()
            ->withCount('lines')
            ->withSum('lines as debit_total', 'debit')
            ->withSum('lines as credit_total', 'credit')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('entry_number', 'like', $like)
                        ->orWhere('memo', 'like', $like);
                });
            })
            ->orderByDesc('entry_date')
            ->orderByDesc('id')
            ->paginate(30);

        $selectedEntry = $this->selectedId
            ? JournalEntry::query()->with(['lines.account'])->find($this->selectedId)
            : null;

        $accounts = Account::query()->where('is_active', true)->orderBy('number')->get(['id', 'number', 'name']);

        return view('livewire.accounting.journal-index', [
            'entries' => $entries,
            'selectedEntry' => $selectedEntry,
            'accounts' => $accounts,
        ])->layoutData([
            'title' => 'Journal Entries',
            'windowTitle' => 'Journal Entries',
        ]);
    }
}
