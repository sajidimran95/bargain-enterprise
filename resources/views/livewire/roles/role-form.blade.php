<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="save" type="button">Save</x-erp.button>
        <x-erp.workspace-link route="roles.index" class="be-btn">Role List</x-erp.workspace-link>
        <x-erp.workspace-link route="users.index" class="be-btn">Users</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">{{ $pageTitle }}</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">{{ $pageTitle }}</h1>
            <p class="text-[11px] text-gray-600">Click menu / submenu / permission boxes to grant access. Admin &amp; Owner always have full access.</p>
        </div>

        <div class="be-entity-dialog__body space-y-4">
            <div class="grid gap-3 md:grid-cols-3 max-w-4xl">
                <div class="be-field">
                    <label class="be-field__label">Role key *</label>
                    <x-erp.input wire:model="name" :disabled="$isSystemAdmin" placeholder="sales_representative" />
                    @error('name') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Display name *</label>
                    <x-erp.input wire:model="label" />
                    @error('label') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field md:col-span-1">
                    <label class="be-field__label">Description</label>
                    <x-erp.input wire:model="description" />
                </div>
            </div>

            @if ($isSystemAdmin)
                <div class="be-panel border px-3 py-2 text-[12px]" style="border-color:#c9a227;background:#fff8dc;">
                    Admin / Owner roles keep every system permission automatically.
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="be-btn" wire:click="selectAll">Select all menus</button>
                    <button type="button" class="be-btn" wire:click="clearAll">Clear all</button>
                </div>
            @endif

            <div class="space-y-3">
                @foreach ($sections as $menu => $section)
                    @php
                        $menuNames = collect($section['submenus'])->flatMap(fn ($items) => collect($items)->pluck('name'))->all();
                        $menuChecked = $menuNames !== [] && collect($menuNames)->every(fn ($n) => in_array($n, $selectedPermissions, true));
                        $menuPartial = ! $menuChecked && collect($menuNames)->contains(fn ($n) => in_array($n, $selectedPermissions, true));
                    @endphp
                    <div class="be-panel" wire:key="menu-{{ $menu }}">
                        <div class="be-panel__body space-y-3">
                            <label class="flex cursor-pointer items-center gap-2 text-[13px] font-semibold">
                                <input
                                    type="checkbox"
                                    class="be-invoice-tax"
                                    @checked($menuChecked)
                                    @if ($menuPartial) data-indeterminate="1" @endif
                                    wire:click.prevent="toggleMenu({{ json_encode($menu) }})"
                                    @disabled($isSystemAdmin)
                                >
                                <span>{{ $section['label'] }}</span>
                                <span class="text-[11px] font-normal text-gray-500">menu</span>
                            </label>

                            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($section['submenus'] as $submenu => $items)
                                    @php
                                        $subNames = collect($items)->pluck('name')->all();
                                        $subChecked = $subNames !== [] && collect($subNames)->every(fn ($n) => in_array($n, $selectedPermissions, true));
                                    @endphp
                                    <div class="rounded border border-gray-200 p-2" wire:key="sub-{{ $menu }}-{{ $submenu }}">
                                        <label class="mb-2 flex cursor-pointer items-center gap-2 text-[12px] font-semibold">
                                            <input
                                                type="checkbox"
                                                class="be-invoice-tax"
                                                @checked($subChecked)
                                                wire:click.prevent="toggleSubmenu({{ json_encode($menu) }}, {{ json_encode($submenu) }})"
                                                @disabled($isSystemAdmin)
                                            >
                                            <span>{{ $submenu }}</span>
                                            <span class="text-[10px] font-normal text-gray-500">submenu</span>
                                        </label>
                                        <div class="space-y-1 pl-1">
                                            @foreach ($items as $item)
                                                <label class="flex cursor-pointer items-start gap-2 text-[12px]">
                                                    <input
                                                        type="checkbox"
                                                        class="be-invoice-tax mt-0.5"
                                                        @checked(in_array($item['name'], $selectedPermissions, true))
                                                        wire:click.prevent="togglePermission({{ json_encode($item['name']) }})"
                                                        @disabled($isSystemAdmin)
                                                    >
                                                    <span>
                                                        <span class="block">{{ $item['label'] }}</span>
                                                        <code class="text-[10px] text-gray-500">{{ $item['name'] }}</code>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
