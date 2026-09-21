<?php

namespace App\Livewire\Lookups;

use App\Actions\Lookups\DeleteLookupAction;
use App\Actions\Lookups\UpsertLookupAction;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\PaymentMethod;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use RuntimeException;

#[Layout('layouts.app')]
#[Title('Payment Methods')]
class PaymentMethodList extends Component
{
    use WithErpListActions;

    #[Url]
    public bool $includeInactive = false;

    public ?int $editingId = null;

    public string $code = '';

    public string $name = '';

    public string $sort_order = '0';

    public bool $is_active = true;

    public function mount(): void
    {
        abort_unless(
            auth()->user()?->hasPermission('item.manage')
            || auth()->user()?->hasPermission('settings.manage'),
            403
        );
    }

    public function openEdit(int $id): void
    {
        $method = PaymentMethod::query()->findOrFail($id);
        $this->selectedLineId = $id;
        $this->editingId = $id;
        $this->code = (string) $method->code;
        $this->name = (string) $method->name;
        $this->sort_order = (string) $method->sort_order;
        $this->is_active = (bool) $method->is_active;
    }

    public function newMethod(): void
    {
        $this->resetForm();
        $this->selectedLineId = null;
    }

    public function save(UpsertLookupAction $action): void
    {
        abort_unless(
            auth()->user()?->hasPermission('item.manage')
            || auth()->user()?->hasPermission('settings.manage'),
            403
        );

        try {
            $action->handle('payment_methods', [
                'code' => $this->code,
                'name' => $this->name,
                'sort_order' => (int) $this->sort_order,
                'is_active' => $this->is_active,
            ], $this->editingId);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            return;
        }

        $this->resetForm();
        $this->dispatch('be-toast', message: 'Payment method saved. It will appear on bills, invoices, and payments.');
    }

    public function toggleActive(int $id): void
    {
        $method = PaymentMethod::query()->findOrFail($id);
        $method->update(['is_active' => ! $method->is_active]);
        $this->dispatch('be-toast', message: $method->is_active ? 'Activated.' : 'Deactivated.');
    }

    public function delete(int $id, DeleteLookupAction $action): void
    {
        try {
            $action->handle('payment_methods', $id);
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        $this->dispatch('be-toast', message: 'Payment method deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->code = '';
        $this->name = '';
        $this->sort_order = (string) ((int) PaymentMethod::query()->max('sort_order') + 10);
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $methods = PaymentMethod::query()
            ->when(! $this->includeInactive, fn ($q) => $q->where('is_active', true))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('code', 'like', $like)
                        ->orWhere('name', 'like', $like);
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('livewire.lookups.payment-method-list', [
            'methods' => $methods,
        ])->layoutData([
            'title' => 'Payment Methods',
            'windowTitle' => 'Payment Methods',
        ]);
    }
}
