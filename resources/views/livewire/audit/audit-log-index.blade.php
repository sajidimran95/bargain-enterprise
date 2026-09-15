<div class="be-page">
    <div class="be-toolbar">
        <div>
            <h1 class="be-toolbar__heading">Audit Log</h1>
            <p class="text-[11px] text-gray-500">{{ $logs->total() }} entries</p>
        </div>
    </div>

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-3 grid gap-2 md:grid-cols-3 lg:grid-cols-6">
                <div class="be-field lg:col-span-2">
                    <label class="be-field__label">Search</label>
                    <x-erp.input wire:model.live.debounce.300ms="search" placeholder="Action, user, model…" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">From</label>
                    <x-erp.input type="date" wire:model.live="from" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">To</label>
                    <x-erp.input type="date" wire:model.live="to" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Action</label>
                    <x-erp.select wire:model.live="action" :options="$actionOptions" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Model</label>
                    <x-erp.select wire:model.live="modelType" :options="$modelTypeOptions" />
                </div>
                <div class="be-field lg:col-span-2">
                    <label class="be-field__label">User</label>
                    <x-erp.select wire:model.live="userId" :options="$userOptions" />
                </div>
            </div>

            <div class="grid gap-3 lg:grid-cols-5">
                <div class="lg:col-span-3 overflow-x-auto">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Model</th>
                                <th>ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr
                                    wire:key="audit-{{ $log->id }}"
                                    wire:click="selectEntry({{ $log->id }})"
                                    class="cursor-pointer {{ $selectedId === $log->id ? 'is-selected' : '' }}"
                                >
                                    <td>{{ $log->created_at?->format('m/d/Y g:i A') }}</td>
                                    <td>{{ $log->user?->name ?? 'System' }}</td>
                                    <td><span class="be-badge">{{ $log->action }}</span></td>
                                    <td>{{ class_basename($log->model_type) }}</td>
                                    <td>{{ $log->model_id }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"><x-erp.empty-state title="No audit entries" message="Changes will appear here as users create and update records." /></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        <x-erp.pagination :paginator="$logs" />
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="border p-3 text-[12px]" style="border-color: var(--be-border); min-height: 240px;">
                        @if ($selected)
                            <div class="mb-2 flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-semibold">{{ $selected->action }}</div>
                                    <div class="text-gray-500">
                                        {{ class_basename($selected->model_type) }} #{{ $selected->model_id }}
                                        · {{ $selected->user?->name ?? 'System' }}
                                        · {{ $selected->created_at?->format('m/d/Y g:i:s A') }}
                                    </div>
                                </div>
                                <button type="button" class="be-link-btn" wire:click="clearSelection">Close</button>
                            </div>

                            <div class="mb-3">
                                <div class="mb-1 font-semibold">Old values</div>
                                <pre class="max-h-48 overflow-auto whitespace-pre-wrap rounded bg-gray-50 p-2 text-[11px]">{{ $selected->old_values ? json_encode($selected->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '—' }}</pre>
                            </div>
                            <div>
                                <div class="mb-1 font-semibold">New values</div>
                                <pre class="max-h-48 overflow-auto whitespace-pre-wrap rounded bg-gray-50 p-2 text-[11px]">{{ $selected->new_values ? json_encode($selected->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '—' }}</pre>
                            </div>
                        @else
                            <p class="text-gray-500">Select a row to inspect old/new values.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
