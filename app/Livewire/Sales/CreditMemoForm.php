<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CreateCreditMemoAction;
use App\Livewire\Concerns\WithDocumentRibbon;
use App\Livewire\Concerns\WithLineItems;
use App\Models\CreditMemo;
use App\Models\Customer;
use App\Models\TaxCode;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Credit Memo')]
class CreditMemoForm extends Component
{
    use WithDocumentRibbon;
    use WithLineItems;

    public string $credit_number = '';

    public string $customer_id = '';

    public string $credit_date = '';

    public string $status = 'open';

    public string $memo = '';

    public string $customer_message = '';

    public string $tax_code_id = '';

    public string $class = '';

    public string $template = 'Custom Credit Memo';

    public string $po_number = '';

    public bool $print_later = false;

    public bool $email_later = false;

    public bool $is_pending = false;

    public string $ribbonTab = 'main';

    public string $inspectorTab = 'name';

    public bool $inspectorOpen = true;

    public string $saveMode = 'close';

    public function toggleInspector(): void
    {
        $this->inspectorOpen = ! $this->inspectorOpen;
    }

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);
        $this->credit_number = DocumentNumbers::next(CreditMemo::class, 'credit_number', 'CM-');
        $this->credit_date = now()->toDateString();
        $this->ensureLineCapacity(10);
    }

    public function updatedCustomerId(): void
    {
        $customer = Customer::query()->with('taxCode')->find($this->customer_id);
        if ($customer?->tax_code_id) {
            $this->tax_code_id = (string) $customer->tax_code_id;
        }

        if ($this->customer_id) {
            $this->inspectorOpen = true;
            $this->inspectorTab = 'name';
        }
    }

    public function clearForm(): void
    {
        $this->navigatorId = null;
        $this->customer_id = '';
        $this->memo = '';
        $this->customer_message = '';
        $this->tax_code_id = '';
        $this->class = '';
        $this->template = 'Custom Credit Memo';
        $this->po_number = '';
        $this->print_later = false;
        $this->email_later = false;
        $this->is_pending = false;
        $this->credit_number = DocumentNumbers::next(CreditMemo::class, 'credit_number', 'CM-');
        $this->credit_date = now()->toDateString();
        $this->lines = [];
        $this->ensureLineCapacity(10);
        $this->scanCode = '';
        $this->pendingAttachments = [];
    }

    public function createCopy(): void
    {
        $this->navigatorId = null;
        $this->credit_number = DocumentNumbers::next(CreditMemo::class, 'credit_number', 'CM-');
        $this->credit_date = now()->toDateString();
        $this->print_later = false;
        $this->email_later = false;
        $this->is_pending = false;
        $this->dispatch('be-toast', message: 'Copied — new credit number assigned. Save when ready.');
    }

    public function memorize(): void
    {
        session()->put('memorized.credit_memo', [
            'customer_id' => $this->customer_id,
            'class' => $this->class,
            'template' => $this->template,
            'po_number' => $this->po_number,
            'memo' => $this->memo,
            'customer_message' => $this->customer_message,
            'tax_code_id' => $this->tax_code_id,
            'lines' => $this->lines,
        ]);
        $this->dispatch('be-toast', message: 'Credit memo memorized for this session.');
    }

    public function togglePending(): void
    {
        $this->is_pending = ! $this->is_pending;
        $this->dispatch('be-toast', message: $this->is_pending
            ? 'Marked as pending (will not post inventory/AR until cleared).'
            : 'Pending cleared.');
    }

    public function attachFile(): void
    {
        $this->openAttachModal();
    }

    protected function ensureLineCapacity(int $min): void
    {
        while (count($this->lines) < $min) {
            $this->addLine();
        }
    }

    public function saveAndClose(CreateCreditMemoAction $action): mixed
    {
        $this->saveMode = 'close';

        return $this->save($action);
    }

    public function saveAndNew(CreateCreditMemoAction $action): mixed
    {
        $this->saveMode = 'new';

        return $this->save($action);
    }

    public function save(CreateCreditMemoAction $action): mixed
    {
        $memo = $this->persistCreditMemo($action);
        if (! $memo) {
            return null;
        }

        if ($this->saveMode === 'stay') {
            return $memo;
        }

        if ($this->saveMode === 'new') {
            $this->clearForm();

            return null;
        }

        return $this->redirect(route('credit-memos.index'), navigate: true);
    }

    protected function saveForRibbon(): ?Model
    {
        $this->saveMode = 'stay';

        return $this->persistCreditMemo(app(CreateCreditMemoAction::class));
    }

    protected function persistCreditMemo(CreateCreditMemoAction $action): ?CreditMemo
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'credit_number' => ['required', 'string', 'max:50', 'unique:credit_memos,credit_number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'credit_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
            'customer_message' => ['nullable', 'string', 'max:255'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'class' => ['nullable', 'string', 'max:100'],
            'template' => ['nullable', 'string', 'max:100'],
            'po_number' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $memo = $action->handle([
                'credit_number' => $this->credit_number,
                'customer_id' => (int) $this->customer_id,
                'credit_date' => $this->credit_date,
                'memo' => $this->memo ?: null,
                'class' => $this->class ?: null,
                'template' => $this->template ?: null,
                'po_number' => $this->po_number ?: null,
                'print_later' => $this->print_later,
                'email_later' => $this->email_later,
                'is_pending' => $this->is_pending,
                'created_by' => auth()->id(),
            ], $this->validatedLinePayload());
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->navigatorId = (int) $memo->id;
        $stored = $this->storePendingAttachmentsFor('attachments/credit-memos/'.$memo->credit_number);
        $this->dispatch('be-toast', message: 'Credit memo '.$memo->credit_number.' saved.'
            .($stored ? ' '.$stored.' attachment(s) stored.' : ''));

        return $memo;
    }

    protected function documentModelClass(): string
    {
        return CreditMemo::class;
    }

    protected function documentNumberColumn(): string
    {
        return 'credit_number';
    }

    protected function documentPdfRouteName(): string
    {
        return 'credit-memos.pdf';
    }

    protected function documentBatchListRouteName(): string
    {
        return 'credit-memos.index';
    }

    protected function documentPdfView(): string
    {
        return 'pdf.credit-memo';
    }

    protected function documentPdfData(Model $document): array
    {
        return ['creditMemo' => $document];
    }

    protected function loadDocumentIntoForm(Model $document): void
    {
        /** @var CreditMemo $document */
        $document->loadMissing(['lines.item', 'customer']);

        $this->customer_id = (string) $document->customer_id;
        $this->credit_date = $document->credit_date?->toDateString() ?: now()->toDateString();
        $this->memo = (string) ($document->memo ?? '');
        $this->customer_message = '';
        $this->tax_code_id = (string) ($document->customer?->tax_code_id ?? '');
        $this->class = (string) ($document->class ?? '');
        $this->template = (string) ($document->template ?: 'Custom Credit Memo');
        $this->po_number = (string) ($document->po_number ?? '');
        $this->print_later = (bool) $document->print_later;
        $this->email_later = (bool) $document->email_later;
        $this->is_pending = (bool) $document->is_pending;
        $this->credit_number = DocumentNumbers::next(CreditMemo::class, 'credit_number', 'CM-');

        $this->lines = [];
        foreach ($document->lines as $line) {
            $this->lines[] = [
                'item_id' => (string) $line->item_id,
                'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                'description' => (string) $line->description,
                'quantity' => number_format((float) $line->quantity, 2, '.', ''),
                'rate' => number_format((float) $line->rate, 2, '.', ''),
                'amount' => number_format((float) $line->amount, 2, '.', ''),
                'taxable' => (bool) $line->taxable,
                'class' => '',
            ];
        }
        $this->ensureLineCapacity(10);
    }

    public function render()
    {
        $customer = $this->customer_id
            ? Customer::query()->with(['taxCode', 'creditMemos' => fn ($q) => $q->latest('credit_date')->limit(5)])->find($this->customer_id)
            : null;

        $subtotal = $this->linesSubtotal();
        $taxRate = (float) ($customer?->taxCode?->rate
            ?? TaxCode::query()->find($this->tax_code_id)?->rate
            ?? 0);
        $taxTotal = '0.00';
        foreach ($this->lines as $line) {
            if (! ($line['taxable'] ?? true) || blank($line['item_id'] ?? null)) {
                continue;
            }
            $taxTotal = bcadd(
                $taxTotal,
                number_format((float) bcmul((string) ($line['amount'] ?? 0), bcdiv((string) $taxRate, '100', 6), 6), 2, '.', ''),
                2
            );
        }
        $total = bcadd($subtotal, $taxTotal, 2);

        return view('livewire.sales.credit-memo-form', [
            'customers' => Customer::query()
                ->active()
                ->orderBy('display_name')
                ->get(['id', 'display_name', 'customer_number']),
            'taxCodes' => TaxCode::query()->where('is_active', true)->orderBy('code')->pluck('name', 'id')->all(),
            'itemOptions' => ItemCatalog::optionsForLineItems($this->lines),
            'selectedCustomer' => $customer,
            'subtotal' => $subtotal,
            'taxRate' => $taxRate,
            'taxTotal' => $taxTotal,
            'total' => $total,
            'recentCredits' => $customer?->creditMemos ?? collect(),
            'attachmentCount' => count($this->pendingAttachments),
        ])->layoutData([
            'title' => 'Create Credit Memo',
            'windowTitle' => 'Create Credit Memos / Refunds',
        ]);
    }
}
