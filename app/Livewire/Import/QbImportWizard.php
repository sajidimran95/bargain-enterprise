<?php

namespace App\Livewire\Import;

use App\Models\ImportBatch;
use App\Services\QbImportService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Import from JapsPOS')]
class QbImportWizard extends Component
{
    use WithFileUploads;

    public string $entity = 'items';

    /** @var mixed */
    public $csv;

    public ?int $batchId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('import.manage') || auth()->user()?->hasPermission('settings.manage'), 403);
    }

    public function updatedCsv(): void
    {
        $this->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
            'entity' => ['required', 'in:categories,items,customers,vendors'],
        ]);
    }

    public function upload(QbImportService $service): void
    {
        abort_unless(auth()->user()?->hasPermission('import.manage') || auth()->user()?->hasPermission('settings.manage'), 403);

        $this->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
            'entity' => ['required', 'in:categories,items,customers,vendors'],
        ]);

        $batch = $service->ingestCsv($this->csv, $this->entity, auth()->id());
        $this->batchId = $batch->id;
        $this->csv = null;
        $this->dispatch('be-toast', message: 'Raw import loaded: '.$batch->row_count.' rows.');
    }

    public function runStage(string $stage, QbImportService $service): void
    {
        abort_unless(auth()->user()?->hasPermission('import.manage') || auth()->user()?->hasPermission('settings.manage'), 403);
        $batch = $this->batch();

        $batch = match ($stage) {
            'normalize' => $service->normalize($batch),
            'validate' => $service->validate($batch),
            'transform' => $service->transform($batch),
            'produce' => $service->produce($batch),
            'pipeline' => $service->runPipeline($batch),
            default => throw new \InvalidArgumentException('Unknown stage'),
        };

        $this->batchId = $batch->id;
        $this->dispatch('be-toast', message: 'Stage "'.$stage.'" complete. Status: '.$batch->status);
    }

    public function downloadSample(): mixed
    {
        $samples = [
            'categories' => "code,name,description,is_active\nTOB,Tobacco,Tobacco products,1\nVAPE,Vape,Vape products,1\n",
            'items' => "sku,name,barcode,type,sales_price,purchase_cost,on_hand,category,promotion,is_active\nSKU-100,Sample Cigars,012345678901,inventory_part,12.50,8.00,25,Tobacco,,1\nSKU-200,Energy Drink 16oz,,inventory_part,2.25,1.10,100,Drinks,Summer,1\n",
            'customers' => "display_name,company_name,email,phone,bill_to_street1,bill_to_city,bill_to_state,bill_to_zip,balance,is_active\nABC Wholesale,ABC Wholesale LLC,abc@example.com,555-0100,100 Main St,Dallas,TX,75001,0,1\n",
            'vendors' => "display_name,company_name,email,phone,balance,is_active\nAcme Supply,Acme Supply Co,vendor@example.com,555-0200,0,1\n",
        ];

        $csv = $samples[$this->entity] ?? $samples['items'];
        $filename = 'qb-import-sample-'.$this->entity.'.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function batch(): ImportBatch
    {
        abort_unless($this->batchId, 404);

        return ImportBatch::query()->with('rows')->findOrFail($this->batchId);
    }

    public function render()
    {
        $batch = $this->batchId
            ? ImportBatch::query()->with(['rows' => fn ($q) => $q->orderBy('line_number')->limit(50)])->find($this->batchId)
            : null;

        $recent = ImportBatch::query()->latest()->limit(10)->get();

        return view('livewire.import.qb-import-wizard', [
            'batch' => $batch,
            'recent' => $recent,
            'entities' => [
                'categories' => 'Categories (lookups)',
                'items' => 'Items / Inventory',
                'customers' => 'Customers',
                'vendors' => 'Vendors',
            ],
        ])->layoutData([
            'title' => 'Import from JapsPOS',
            'windowTitle' => 'QB Import',
        ]);
    }
}
