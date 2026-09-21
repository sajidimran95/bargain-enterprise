<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CreateInvoiceAction;
use App\Actions\Sales\ReceivePaymentAction;
use App\Actions\Sales\UpdateInvoiceAction;
use App\Livewire\Concerns\WithDocumentRibbon;
use App\Livewire\Concerns\WithLineItems;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TaxCode;
use App\Support\DocumentNumbers;
use App\Support\PaymentMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
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

    public bool $receive_payment_now = false;

    public string $payment_method = '';

    public string $payment_amount = '';

    public string $payment_reference = '';

    public string $ribbonTab = 'main';

    public string $inspectorTab = 'name';

    public bool $inspectorOpen = true;

    public string $saveMode = 'close';

    public function toggleInspector(): void
    {
        $this->inspectorOpen = ! $this->inspectorOpen;
    }

    public function mount(?Invoice $invoice = null): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        if ($invoice?->exists) {
            $this->loadDocumentIntoForm($invoice);
            $this->navigatorId = (int) $invoice->id;

            return;
        }

        $this->invoice_number = DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-');
        $this->invoice_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->payment_method = PaymentMethods::defaultCode('cash');
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
        $this->receive_payment_now = false;
        $this->payment_method = PaymentMethods::defaultCode('cash');
        $this->payment_amount = '';
        $this->payment_reference = '';
    }

    public function updatedReceivePaymentNow(bool $value): void
    {
        if ($value) {
            $this->payment_amount = $this->currentInvoiceTotal();
            if ($this->payment_method === '') {
                $this->payment_method = PaymentMethods::defaultCode('cash');
            }
        }
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

    public function saveAndClose(): mixed
    {
        $this->saveMode = 'close';

        return $this->save();
    }

    public function saveAndNew(): mixed
    {
        $this->saveMode = 'new';

        return $this->save();
    }

    public function save(): mixed
    {
        $invoice = $this->persistInvoice();
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

        return $this->persistInvoice();
    }

    protected function persistInvoice(): ?Invoice
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'invoice_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('invoices', 'invoice_number')->ignore($this->navigatorId),
            ],
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
            'customer_message' => ['nullable', 'string', 'max:255'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'class' => ['nullable', 'string', 'max:100'],
            'template' => ['nullable', 'string', 'max:100'],
        ]);

        $header = [
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
            'allow_negative_inventory' => auth()->user()?->hasPermission('inventory.override') ?? false,
        ];

        try {
            if ($this->navigatorId) {
                $result = app(UpdateInvoiceAction::class)->handle(
                    Invoice::query()->findOrFail($this->navigatorId),
                    $header + ['updated_by' => auth()->id()],
                    $this->validatedLinePayload()
                );
                $invoice = $result['invoice'];
                $editNote = '';
                if (bccomp($result['overpayment'], '0', 2) > 0) {
                    $creditNo = $result['overpayment_credit']?->credit_number ?? '';
                    $editNote = ' Overpayment '.$result['overpayment'].' returned as credit '.$creditNo.'.';
                } elseif (bccomp($result['amount_still_due'], '0', 2) > 0) {
                    $editNote = ' Amount still to collect: '.$result['amount_still_due'].'.';
                }
            } else {
                $invoice = app(CreateInvoiceAction::class)->handle(
                    $header + ['created_by' => auth()->id()],
                    $this->validatedLinePayload()
                );
                $editNote = '';
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $paymentNote = '';
        if ($this->receive_payment_now && ! $this->is_pending && ! $this->navigatorId) {
            abort_unless(auth()->user()?->hasPermission('payment.create'), 403);

            $payAmount = number_format((float) ($this->payment_amount !== '' ? $this->payment_amount : $invoice->total), 2, '.', '');
            if (bccomp($payAmount, '0', 2) <= 0) {
                $this->dispatch('be-toast', message: 'Invoice saved, but payment amount must be greater than 0.');
            } elseif (bccomp($payAmount, (string) $invoice->balance_due, 2) > 0) {
                $this->dispatch('be-toast', message: 'Invoice saved, but payment cannot exceed invoice total.');
            } else {
                try {
                    app(ReceivePaymentAction::class)->handle([
                        'customer_id' => (int) $invoice->customer_id,
                        'payment_number' => DocumentNumbers::next(Payment::class, 'payment_number', 'PMT-'),
                        'payment_date' => $this->invoice_date,
                        'amount' => $payAmount,
                        'method' => $this->payment_method ?: 'cash',
                        'reference' => $this->payment_reference ?: null,
                        'memo' => 'Paid with invoice '.$invoice->invoice_number,
                        'created_by' => auth()->id(),
                    ], [[
                        'invoice_id' => $invoice->id,
                        'amount' => $payAmount,
                    ]]);
                    $invoice->refresh();
                    $paymentNote = ' Payment '.$payAmount.' applied ('.$this->payment_method.').';
                } catch (\Throwable $e) {
                    $this->dispatch('be-toast', message: 'Invoice saved, payment failed: '.$e->getMessage());

                    return $invoice;
                }
            }
        }

        $this->navigatorId = (int) $invoice->id;
        $stored = $this->storePendingAttachmentsFor('attachments/invoices/'.$invoice->invoice_number);
        $this->dispatch('be-toast', message: 'Invoice '.$invoice->invoice_number.' saved.'.$editNote.$paymentNote
            .($stored ? ' '.$stored.' attachment(s) stored.' : ''));

        return $invoice;
    }

    protected function currentInvoiceTotal(): string
    {
        $subtotal = $this->linesSubtotal();
        $taxRate = '0';
        if ($this->tax_code_id !== '') {
            $taxRate = (string) (TaxCode::query()->whereKey($this->tax_code_id)->value('rate') ?? 0);
        }

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

        return bcadd($subtotal, $taxTotal, 2);
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
        return 'invoices.batch';
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
        $this->invoice_number = (string) $document->invoice_number;
        $this->receive_payment_now = false;

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

        $savedInvoice = $this->navigatorId
            ? Invoice::query()->with(['createdBy', 'updatedBy'])->find($this->navigatorId)
            : null;

        $auditTrail = $savedInvoice
            ? AuditLog::query()
                ->with('user')
                ->where('model_type', Invoice::class)
                ->where('model_id', $savedInvoice->id)
                ->latest('id')
                ->limit(40)
                ->get()
            : collect();

        return view('livewire.sales.invoice-form', [
            'customers' => Customer::query()
                ->active()
                ->orderBy('display_name')
                ->get(['id', 'display_name', 'customer_number']),
            'taxCodes' => TaxCode::query()->where('is_active', true)->orderBy('code')->pluck('name', 'id')->all(),
            'selectedCustomer' => $customer,
            'subtotal' => $subtotal,
            'taxRate' => $taxRate,
            'taxTotal' => $taxTotal,
            'total' => $total,
            'recentInvoices' => $customer?->invoices ?? collect(),
            'attachmentCount' => count($this->pendingAttachments),
            'savedInvoice' => $savedInvoice,
            'auditTrail' => $auditTrail,
            'paymentMethodOptions' => PaymentMethods::options(),
        ])->layoutData([
            'title' => $savedInvoice ? 'Edit Invoice '.$savedInvoice->invoice_number : 'Create Invoices',
            'windowTitle' => $savedInvoice ? 'Edit Invoice '.$savedInvoice->invoice_number : 'Create Invoices',
        ]);
    }
}
