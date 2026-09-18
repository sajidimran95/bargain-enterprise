<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Role List" new-route="roles.create" new-label="New Role" :title="$roles->total().' roles'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Role name…" />

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Key</th>
                        <th>Description</th>
                        <th class="text-right">Users</th>
                        <th class="text-right">Permissions</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr
                            wire:key="role-{{ $role->id }}"
                            wire:click="selectLine({{ $role->id }})"
                            wire:dblclick="openEdit({{ $role->id }})"
                            class="{{ $selectedLineId === $role->id ? 'is-selected' : '' }} cursor-pointer"
                        >
                            <td>{{ $role->label }}</td>
                            <td><code class="text-[11px]">{{ $role->name }}</code></td>
                            <td>{{ $role->description }}</td>
                            <td class="num">{{ $role->users_count }}</td>
                            <td class="num">{{ $role->permissions_count }}</td>
                            <td class="whitespace-nowrap">
                                <x-erp.workspace-link route="roles.edit" :params="['role' => $role->id]" :title="'Role: '.$role->label" class="be-link-btn" @click.stop>Edit</x-erp.workspace-link>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No roles" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$roles" />
        </div>
    </div>
</div>
