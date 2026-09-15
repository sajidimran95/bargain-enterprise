<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CreateInvoiceAction;
use App\Livewire\Concerns\WithDocumentRibbon;
use App\Livewire\Concerns\WithLineItems;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\TaxCode;
use App\Support\DocumentNumbers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Invoices')]
class InvoiceForm extends Component
{
    use WithDocumentRibbon;
    use WithLineItems;

    public string $invoice_number = '';

    public string $customer_id = '';

    public string $invoice_date = '';

    public string $due_date = '';

    public string $memo = '';

    public string $customer_message = '';

    public string $tax_code_id = '';

    public string $class = '';

    public string $template = 'Intuit Product Invoice';

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
        $this->invoice_number = DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-');
        $this->invoice_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->ensureLineCapacity(10);
        $this->restoreMemorizedIfEmpty();
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
        $this->invoice_number = DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-');
        $this->invoice_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->lines = [];
        $this->ensureLineCapacity(10);
        $this->scanCode = '';
        $this->class = '';
        $this->template = 'Intuit Product Invoice';
        $this->print_later = false;
        $this->email_later = false;
        $this->is_pending = false;
        $this->pendingAttachments = [];
    }

    public function createCopy(): void
    {
        $this->navigatorId = null;
        $this->invoice_number = DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-');
        $this->invoice_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->print_later = false;
        $this->email_later = false;
        $this->is_pending = false;
        $this->dispatch('be-toast', message: 'Copied — new invoice number assigned. Save when ready.');
    }

    public function memorize(): void
    {
        session()->put('memorized.invoice', [
            'customer_id' => $this->customer_id,
            'class' => $this->class,
            'template' => $this->template,
            'memo' => $this->memo,
            'customer_message' => $this->customer_message,
            'tax_code_id' => $this->tax_code_id,
            'lines' => $this->lines,
        ]);
        $this->dispatch('be-toast', message: 'Invoice memorized for this session.');
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

    protected function restoreMemorizedIfEmpty(): void
    {
        $memorized = session('memorized.invoice');
        if (! is_array($memorized) || filled($this->customer_id)) {
            return;
        }

        $this->customer_id = (string) ($memorized['customer_id'] ?? '');
        $this->class = (string) ($memorized['class'] ?? '');
        $this->template = (string) ($memorized['template'] ?? 'Intuit Product Invoice');
        $this->memo = (string) ($memorized['memo'] ?? '');
        $this->customer_message = (string) ($memorized['customer_message'] ?? '');
        $this->tax_code_id = (string) ($memorized['tax_code_id'] ?? '');
        if (is_array($memorized['lines'] ?? null) && $memorized['lines'] !== []) {
            $this->lines = $memorized['lines'];
        }
    }

    protected function ensureLineCapacity(int $min): void
    {
        while (count($this->lines) < $min) {
            $this->addLine();
        }
    }

    public function saveAndClose(CreateInvoiceAction $action): mixed
    {
        $this->saveMode = 'close';

        return $this->save($action);
    }

    public function saveAndNew(CreateInvoiceAction $action): mixed
    {
        $this->saveMode = 'new';

        return $this->save($action);
    }

    public function save(CreateInvoiceAction $action): mixed
    {
        $invoice = $this->persistInvoice($action);
        if (! $invoice) {
            return null;
        }

        if ($this->saveMode === 'stay') {
            return $invoice;
        }

        if ($this->saveMode === 'new') {
            $this->clearForm();

            return null;
        }

        return $this->redirect(route('invoices.index'), navigate: true);
    }

    protected function saveForRibbon(): ?Model
    {
        $this->saveMode = 'stay';

        return $this->persistInvoice(app(CreateInvoiceAction::class));
    }

    protected function persistInvoice(CreateInvoiceAction $action): ?Invoice
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
            'customer_message' => ['nullable', 'string', 'max:255'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'class' => ['nullable', 'string', 'max:100'],
            'template' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $invoice = $action->handle([
                'customer_id' => (int) $this->customer_id,
                'invoice_number' => $this->invoice_number,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date ?: null,
                'status' => $this->is_pending ? 'pending' : 'open',
                'tax_code_id' => $this->tax_code_id ?: null,
                'memo' => $this->memo ?: null,
                'customer_message' => $this->customer_message ?: null,
                'class' => $this->class ?: null,
                'template' => $this->template ?: null,
                'print_later' => $this->print_later,
                'email_later' => $this->email_later,
                'is_pending' => $this->is_pending,
                'created_by' => auth()->id(),
                'allow_negative_inventory' => auth()->user()?->hasPermission('inventory.override') ?? false,
            ], $this->validatedLinePayload());
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->navigatorId = (int) $invoice->id;
        $stored = $this->storePendingAttachmentsFor('attachments/invoices/'.$invoice->invoice_number);
        $this->dispatch('be-toast', message: 'Invoice '.$invoice->invoice_number.' saved.'
            .($stored ? ' '.$stored.' attachment(s) stored.' : ''));

        return $invoice;
    }

    protected function documentModelClass(): string
    {
        return Invoice::class;
    }

    protected function documentNumberColumn(): string
    {
        return 'invoice_number';
    }

    protected function documentPdfRouteName(): string
    {
        return 'invoices.pdf';
    }

    protected function documentBatchListRouteName(): string
    {
        return 'invoices.index';
    }

    protected function documentPdfView(): string
    {
        return 'pdf.invoice';
    }

    protected function documentPdfData(Model $document): array
    {
        return ['invoice' => $document];
    }

    protected function loadDocumentIntoForm(Model $document): void
    {
        /** @var Invoice $document */
        $document->loadMissing('lines.item');

        $this->customer_id = (string) $document->customer_id;
        $this->invoice_date = $document->invoice_date?->toDateString() ?: now()->toDateString();
        $this->due_date = $document->due_date?->toDateString() ?: now()->addDays(30)->toDateString();
        $this->memo = (string) ($document->memo ?? '');
        $this->customer_message = (string) ($document->customer_message ?? '');
        $this->tax_code_id = (string) ($document->tax_code_id ?? '');
        $this->class = (string) ($document->class ?? '');
        $this->template = (string) ($document->template ?: 'Intuit Product Invoice');
        $this->print_later = (bool) $document->print_later;
        $this->email_later = (bool) $document->email_later;
        $this->is_pending = (bool) $document->is_pending;
        $this->invoice_number = DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-');

        $this->lines = [];
        foreach ($document->lines as $line) {
            $this->lines[] = [
                'item_id' => (string) $line->item_id,
                'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                'description' => (string) $line->description,
                'quantity' => number_format((float) $line->quantity, 4, '.', ''),
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
            ? Customer::query()->with(['taxCode', 'invoices' => fn ($q) => $q->latest('invoice_date')->limit(5)])->find($this->customer_id)
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

        return view('livewire.sales.invoice-form', [
            'customers' => Customer::query()->active()->orderBy('display_name')->get(),
            'taxCodes' => TaxCode::query()->where('is_active', true)->orderBy('code')->pluck('name', 'id')->all(),
            'itemOptions' => Item::query()->active()->orderBy('sku')->limit(500)->get()
                ->mapWithKeys(fn (Item $i) => [$i->id => ($i->barcode ?: $i->sku).' — '.($i->sales_description ?: $i->name)])
                ->all(),
            'selectedCustomer' => $customer,
            'subtotal' => $subtotal,
            'taxRate' => $taxRate,
            'taxTotal' => $taxTotal,
            'total' => $total,
            'recentInvoices' => $customer?->invoices ?? collect(),
            'attachmentCount' => count($this->pendingAttachments),
        ])->layoutData([
            'title' => 'Create Invoices',
            'windowTitle' => 'Create Invoices',
        ]);
    }
}
