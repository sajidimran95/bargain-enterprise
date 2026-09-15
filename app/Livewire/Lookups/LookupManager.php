<?php

namespace App\Livewire\Lookups;

use App\Actions\Lookups\DeleteLookupAction;
use App\Actions\Lookups\UpsertLookupAction;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use RuntimeException;

#[Layout('layouts.app')]
#[Title('Lookups')]
class LookupManager extends Component
{
    use AuthorizesRequests;
    use WithErpListActions;

    #[Url]
    public string $activeType = 'categories';

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('item.manage') || auth()->user()?->hasPermission('settings.manage'), 403);
        $this->resetForm();
    }

    public function setType(string $type): void
    {
        $this->activeType = $type;
        $this->editingId = null;
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $this->editingId = $id;
        $this->resetForm();
        $record = $this->findRecord($id);

        foreach (array_keys($this->form) as $key) {
            if (array_key_exists($key, $record->getAttributes())) {
                $this->form[$key] = $record->{$key};
            }
        }
    }

    public function save(UpsertLookupAction $action): void
    {
        try {
            $action->handle($this->activeType, $this->form, $this->editingId);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError('form.'.$field, $message);
                }
            }

            return;
        }

        $this->editingId = null;
        $this->resetForm();
        $this->dispatch('be-toast', message: 'Lookup saved.');
    }

    public function toggleActive(int $id): void
    {
        $record = $this->findRecord($id);
        $record->update(['is_active' => ! $record->is_active]);
        $this->dispatch('be-toast', message: $record->is_active ? 'Activated.' : 'Deactivated.');
    }

    public function delete(int $id, DeleteLookupAction $action): void
    {
        try {
            $action->handle($this->activeType, $id);
        } catch (RuntimeException $e) {
            $this->addError('form', $e->getMessage());
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        if ($this->editingId === $id) {
            $this->editingId = null;
            $this->resetForm();
        }

        $this->dispatch('be-toast', message: 'Lookup deleted.');
    }

    public function resetForm(): void
    {
        $this->form = match ($this->activeType) {
            'price_levels' => ['name' => '', 'description' => '', 'adjustment_percent' => 0, 'is_active' => true],
            'tax_codes' => ['code' => '', 'name' => '', 'rate' => 0, 'is_active' => true],
            'units' => ['name' => '', 'abbreviation' => '', 'is_active' => true],
            'item_types' => ['name' => '', 'label' => '', 'description' => '', 'is_active' => true],
            default => ['code' => '', 'name' => '', 'description' => '', 'is_active' => true],
        };
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetForm();
    }

    protected function findRecord(int $id): PriceLevel|TaxCode|UnitOfMeasure|ItemCategory|ItemType
    {
        return match ($this->activeType) {
            'price_levels' => PriceLevel::query()->findOrFail($id),
            'tax_codes' => TaxCode::query()->findOrFail($id),
            'units' => UnitOfMeasure::query()->findOrFail($id),
            'item_types' => ItemType::query()->findOrFail($id),
            default => ItemCategory::query()->findOrFail($id),
        };
    }

    public function render()
    {
        $records = match ($this->activeType) {
            'price_levels' => PriceLevel::query()->orderBy('name')->get(),
            'tax_codes' => TaxCode::query()->orderBy('code')->get(),
            'units' => UnitOfMeasure::query()->orderBy('name')->get(),
            'item_types' => ItemType::query()->orderBy('label')->get(),
            default => ItemCategory::query()->orderBy('code')->get(),
        };

        return view('livewire.lookups.lookup-manager', [
            'records' => $records,
        ])->layoutData([
            'title' => 'Lookups',
            'windowTitle' => 'Lookups',
        ]);
    }
}
