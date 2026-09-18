<?php

namespace App\Livewire\Concerns;

use App\Support\CsvExporter;
use App\Support\Workspace\WorkspaceCatalog;
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

    /**
     * Open a named workspace route for edit (used by list double-click / Edit).
     *
     * @param  array<string, mixed>  $params
     */
    public function openWorkspaceEdit(string $routeName, array $params, ?string $title = null): void
    {
        $this->js(
            'if(window.parent&&window.parent!==window){window.parent.postMessage({type:"be-workspace-open",route:'.json_encode($routeName).',params:'.json_encode($params).',title:'.json_encode($title).'},"*");}'
            .'else if(window.beWorkspace&&typeof window.beWorkspace.open==="function"){window.beWorkspace.open('.json_encode($routeName).','.json_encode($params).','.json_encode($title).');}'
            .'else if(typeof beOpenWorkspace==="function"){beOpenWorkspace('.json_encode($routeName).','.json_encode($params).','.json_encode($title).');}'
            .'else{window.location.assign('.json_encode(WorkspaceCatalog::embedUrl($routeName, $params)).');}'
        );
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
