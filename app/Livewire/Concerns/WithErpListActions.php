<?php

namespace App\Livewire\Concerns;

use App\Support\CsvExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait WithErpListActions
{
    public string $search = '';

    public ?int $selectedLineId = null;

    public function updatingSearch(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function selectLine(int $id): void
    {
        $this->selectedLineId = $id;
    }

    public function focusSearch(): void
    {
        $this->dispatch('be-focus-list-search');
    }

    /**
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, string|int|float|null>>  $rows
     */
    protected function csvDownload(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return app(CsvExporter::class)->download($filename, $headers, $rows);
    }
}
