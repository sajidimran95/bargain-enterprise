<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="save" type="button">Save</x-erp.button>
        @if ($editingId && $canDelete)
            <x-erp.button
                type="button"
                wire:click="deleteUser"
                wire:confirm="Delete this user? This cannot be undone."
            >Delete</x-erp.button>
        @elseif ($editingId && $isPrimaryAdmin)
            <span class="text-[11px] text-gray-500 self-center">Main admin — cannot delete</span>
        @endif
        <x-erp.workspace-link route="users.index" class="be-btn">User List</x-erp.workspace-link>
        <x-erp.workspace-link route="roles.index" class="be-btn">Roles</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">{{ $pageTitle }}</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">{{ $pageTitle }}</h1>
            <p class="text-[11px] text-gray-600">
                Pick a role to load its permissions as a starting point. Then add/remove permissions for
                <strong>this user only</strong> — the role itself is not changed.
            </p>
        </div>

        <div class="be-entity-dialog__body space-y-4">
            <div class="grid gap-3 md:grid-cols-2 max-w-3xl">
                <div class="be-field">
                    <label class="be-field__label">Name *</label>
                    <x-erp.input wire:model="name" />
                    @error('name') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Email *</label>
                    <x-erp.input type="email" wire:model="email" :disabled="$isPrimaryAdmin" />
                    @if ($isPrimaryAdmin)
                        <span class="text-[11px] text-gray-500">Main admin email is locked.</span>
                    @endif
                    @error('email') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Role template *</label>
                    <x-erp.select wire:model.live="role" :options="$roleOptions" />
                    @error('role') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">{{ $editingId ? 'New Password (optional)' : 'Password *' }}</label>
                    <x-erp.input type="password" wire:model="password" autocomplete="new-password" />
                    @error('password') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Confirm Password</label>
                    <x-erp.input type="password" wire:model="password_confirmation" autocomplete="new-password" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="be-btn" wire:click="resetToRole" @disabled($role === '')>Reset to role defaults</button>
                <button type="button" class="be-btn" wire:click="selectAll">Select all</button>
                <button type="button" class="be-btn" wire:click="clearAll">Clear all</button>
                <span class="text-[11px] text-gray-500">{{ count($selectedPermissions) }} permission(s) for this user</span>
            </div>

            <div class="space-y-3">
                @foreach ($sections as $menu => $section)
                    @php
                        $menuNames = collect($section['submenus'])->flatMap(fn ($items) => collect($items)->pluck('name'))->all();
                        $menuChecked = $menuNames !== [] && collect($menuNames)->every(fn ($n) => in_array($n, $selectedPermissions, true));
                        $menuPartial = ! $menuChecked && collect($menuNames)->contains(fn ($n) => in_array($n, $selectedPermissions, true));
                    @endphp
                    <div class="be-panel" wire:key="user-menu-{{ $menu }}">
                        <div class="be-panel__body space-y-3">
                            <label class="flex cursor-pointer items-center gap-2 text-[13px] font-semibold">
                                <input
                                    type="checkbox"
                                    class="be-invoice-tax"
                                    @checked($menuChecked)
                                    @if ($menuPartial) data-indeterminate="1" @endif
                                    wire:click.prevent="toggleMenu({{ json_encode($menu) }})"
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
                                    <div class="rounded border border-gray-200 p-2" wire:key="user-sub-{{ $menu }}-{{ $submenu }}">
                                        <label class="mb-2 flex cursor-pointer items-center gap-2 text-[12px] font-semibold">
                                            <input
                                                type="checkbox"
                                                class="be-invoice-tax"
                                                @checked($subChecked)
                                                wire:click.prevent="toggleSubmenu({{ json_encode($menu) }}, {{ json_encode($submenu) }})"
                                            >
                                            <span>{{ $submenu }}</span>
                                            <span class="text-[10px] font-normal text-gray-500">submenu</span>
                                        </label>
                                        <div class="space-y-1 pl-1">
                                            @foreach ($items as $item)
                                                @php
                                                    $fromRole = in_array($item['name'], $rolePermissionNames, true);
                                                    $checked = in_array($item['name'], $selectedPermissions, true);
                                                @endphp
                                                <label class="flex cursor-pointer items-start gap-2 text-[12px]">
                                                    <input
                                                        type="checkbox"
                                                        class="be-invoice-tax mt-0.5"
                                                        @checked($checked)
                                                        wire:click.prevent="togglePermission({{ json_encode($item['name']) }})"
                                                    >
                                                    <span>
                                                        <span class="block">
                                                            {{ $item['label'] }}
                                                            @if ($fromRole && ! $checked)
                                                                <span class="text-[10px] text-amber-700">(removed from role)</span>
                                                            @elseif (! $fromRole && $checked)
                                                                <span class="text-[10px] text-emerald-700">(added for user)</span>
                                                            @endif
                                                        </span>
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
