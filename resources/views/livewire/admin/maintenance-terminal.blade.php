<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        @foreach ($scripts as $key => $script)
            @if ($key === 'migrate')
                <x-erp.button
                    variant="primary"
                    type="button"
                    wire:click="runScript('migrate')"
                    wire:confirm="Run pending database migrations on this database?"
                    wire:loading.attr="disabled"
                    wire:target="runScript"
                >{{ $script['label'] }}</x-erp.button>
            @else
                <x-erp.button
                    type="button"
                    wire:click="runScript('{{ $key }}')"
                    wire:loading.attr="disabled"
                    wire:target="runScript"
                >{{ $script['label'] }}</x-erp.button>
            @endif
        @endforeach
        <span class="be-doc-toolbar__title">Maintenance Terminal</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">Maintenance Terminal</h1>
            <p class="text-[11px] text-gray-600">Admin only. Cache clear, optimize clear, and pending migrations.</p>
        </div>

        <div class="be-entity-dialog__body max-w-3xl">
            <p class="text-[12px] text-gray-700">Open this page at <span class="font-semibold">/admin/terminal</span> after signing in as an admin.</p>

            <p class="be-section-title mt-3">Command result</p>
            <pre class="max-h-80 overflow-auto whitespace-pre-wrap bg-gray-50 p-3 text-[12px] text-gray-800">{{ $output !== '' ? $output : 'Run a command to see the result here.' }}</pre>
        </div>
    </div>
</div>
