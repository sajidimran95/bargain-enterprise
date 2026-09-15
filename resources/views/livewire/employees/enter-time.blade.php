<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="saveEntry" type="button">Save</x-erp.button>
        <x-erp.workspace-link route="dashboard.home" class="be-btn">Home</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Enter Time</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">Weekly Timesheet</h1>
            <p class="text-[11px] text-gray-600">Record billable or payroll hours for employees.</p>
        </div>

        <div class="be-entity-dialog__body max-w-3xl">
            <div class="mb-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="be-field">
                    <label class="be-field__label">Date *</label>
                    <input type="date" class="be-input" wire:model="work_date">
                    @error('work_date') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Name *</label>
                    <input type="text" class="be-input" wire:model="worker_name">
                    @error('worker_name') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Hours *</label>
                    <input type="number" step="0.25" min="0.25" max="24" class="be-input" wire:model="hours">
                    @error('hours') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Memo</label>
                    <input type="text" class="be-input" wire:model="memo" placeholder="Job / notes">
                    @error('memo') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
            </div>

            <table class="be-table w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th class="text-right">Hours</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entries as $entry)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse($entry['date'])->format('n/j/Y') }}</td>
                            <td>{{ $entry['name'] }}</td>
                            <td class="text-right">{{ $entry['hours'] }}</td>
                            <td>{{ $entry['memo'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-gray-500">No time entries yet. Save a row above.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
