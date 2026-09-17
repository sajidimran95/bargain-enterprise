<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CreateSalesReceiptAction;
use App\Livewire\Concerns\WithLineItems;
use App\Models\Customer;
use App\Models\SalesReceipt;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Sales Receipt')]
class SalesReceiptForm extends Component
{
    use WithLineItems;

    public string $number = '';

    public string $customer_id = '';

    public string $receipt_date = '';

    public string $payment_method = 'cash';

    public string $memo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);
        $this->number = DocumentNumbers::next(SalesReceipt::class, 'number', 'SR-');
        $this->receipt_date = now()->toDateString();
        $this->addLine();
    }

    public function save(CreateSalesReceiptAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'number' => ['required', 'string', 'max:50', 'unique:sales_receipts,number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'receipt_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $receipt = $action->handle([
                'number' => $this->number,
                'customer_id' => (int) $this->customer_id,
                'receipt_date' => $this->receipt_date,
                'payment_method' => $this->payment_method,
                'memo' => $this->memo ?: null,
                'created_by' => auth()->id(),
            ], $this->validatedLinePayload());
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            return null;
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Sales receipt '.$receipt->number.' saved.');

        return $this->redirect(route('sales-receipts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => 'Create Sales Receipt',
            'cancelRoute' => 'sales-receipts.index',
            'partyLabel' => 'Customer',
            'partyOptions' => Customer::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'partyField' => 'customer_id',
            'dateField' => 'receipt_date',
            'dateLabel' => 'Receipt Date',
            'numberField' => 'number',
            'numberLabel' => 'Receipt #',
            'showPaymentMethod' => true,
            'itemOptions' => ItemCatalog::selectOptions(),
        ])->layoutData([
            'title' => 'Create Sales Receipt',
            'windowTitle' => 'Create Sales Receipt',
        ]);
    }
}
