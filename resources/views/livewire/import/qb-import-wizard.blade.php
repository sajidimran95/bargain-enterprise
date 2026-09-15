<div class="be-page">
    <x-erp.toolbar>
        <span class="text-[12px] font-semibold text-gray-800">Import from JapsPOS</span>
        <span class="ml-2 text-[11px] text-gray-500">Raw → Normalize → Validate → Transform → Production</span>
        <x-erp.button type="button" class="ml-auto" wire:click="downloadSample">Download Sample CSV</x-erp.button>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">1. Raw import (CSV)</h1>
        </div>
        <div class="be-panel__body grid gap-3 md:grid-cols-3">
            <div>
                <label class="be-label">Entity</label>
                <x-erp.select wire:model.live="entity" :options="$entities" />
            </div>
            <div class="md:col-span-2">
                <label class="be-label">CSV file</label>
                <input type="file" wire:model="csv" accept=".csv,text/csv" class="be-input">
                @error('csv') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                <p class="mt-1 text-[11px] text-gray-500">Does not write production tables until you run Production. IIF support can be added when the client export format is confirmed.</p>
            </div>
            <div class="md:col-span-3">
                <x-erp.button type="button" variant="primary" wire:click="upload" wire:loading.attr="disabled">Upload to staging</x-erp.button>
            </div>
        </div>
    </div>

    @if ($batch)
        <div class="be-panel m-3">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Batch {{ $batch->uuid }}</h2>
                <span class="text-[11px] text-gray-600">{{ $batch->entity }} · {{ $batch->original_filename }} · status: <strong>{{ $batch->status }}</strong></span>
            </div>
            <div class="be-panel__body">
                <div class="mb-3 flex flex-wrap gap-2">
                    <x-erp.button type="button" wire:click="runStage('normalize')">Normalize</x-erp.button>
                    <x-erp.button type="button" wire:click="runStage('validate')">Validate</x-erp.button>
                    <x-erp.button type="button" wire:click="runStage('transform')">Transform</x-erp.button>
                    <x-erp.button type="button" variant="primary" wire:click="runStage('produce')">Write Production</x-erp.button>
                    <x-erp.button type="button" variant="primary" wire:click="runStage('pipeline')">Run full pipeline</x-erp.button>
                </div>
                <div class="mb-3 grid grid-cols-2 gap-2 text-[11px] md:grid-cols-4">
                    <div class="be-kv"><span class="be-kv__label">Rows</span><span>{{ $batch->row_count }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Errors</span><span class="text-red-700">{{ $batch->error_count }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Produced</span><span>{{ $batch->success_count }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Source</span><span>{{ strtoupper($batch->source) }}</span></div>
                </div>

                <table class="be-table be-table--line-select">
                    <thead>
                        <tr>
                            <th>Line</th>
                            <th>Stage</th>
                            <th>Payload / Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batch->rows as $row)
                            <tr wire:key="ir-{{ $row->id }}">
                                <td>{{ $row->line_number }}</td>
                                <td>{{ $row->stage }}</td>
                                <td class="text-[10px]">
                                    @if ($row->validation_errors)
                                        <span class="text-red-700">{{ json_encode($row->validation_errors) }}</span>
                                    @else
                                        {{ \Illuminate\Support\Str::limit(json_encode($row->normalized_payload ?? $row->raw_payload), 120) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h2 class="be-panel__title">Recent batches</h2>
        </div>
        <div class="be-panel__body p-0">
            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Entity</th>
                        <th>File</th>
                        <th>Status</th>
                        <th class="text-right">Rows</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $row)
                        <tr wire:click="$set('batchId', {{ $row->id }})" class="cursor-pointer">
                            <td>{{ $row->created_at?->format('m/d/Y H:i') }}</td>
                            <td>{{ $row->entity }}</td>
                            <td>{{ $row->original_filename }}</td>
                            <td>{{ $row->status }}</td>
                            <td class="num">{{ $row->row_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No import batches yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
