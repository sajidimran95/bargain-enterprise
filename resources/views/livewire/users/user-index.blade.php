<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="User List" new-route="users.create" new-label="New User" :title="$users->total().' users'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Name / email…" />

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr
                            wire:key="user-{{ $user->id }}"
                            wire:click="selectLine({{ $user->id }})"
                            wire:dblclick="openEdit({{ $user->id }})"
                            class="{{ $selectedLineId === $user->id ? 'is-selected' : '' }} cursor-pointer"
                        >
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('label')->join(', ') ?: '—' }}</td>
                            <td class="whitespace-nowrap">
                                <x-erp.workspace-link route="users.edit" :params="['user' => $user->id]" :title="'User: '.$user->name" class="be-link-btn" @click.stop>Edit</x-erp.workspace-link>
                                @if ($user->canBeDeletedBy(auth()->user()))
                                    <button
                                        type="button"
                                        class="be-link-btn"
                                        wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Delete user {{ $user->name }}? This cannot be undone."
                                        @click.stop
                                    >Delete</button>
                                @else
                                    <span class="text-[11px] text-gray-400" @click.stop title="Protected account">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><x-erp.empty-state title="No users" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$users" />
        </div>
    </div>
</div>
