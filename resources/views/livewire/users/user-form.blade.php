<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="save" type="button">Save</x-erp.button>
        <x-erp.workspace-link route="users.index" class="be-btn">User List</x-erp.workspace-link>
        <x-erp.workspace-link route="roles.index" class="be-btn">Roles</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">{{ $pageTitle }}</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">{{ $pageTitle }}</h1>
            <p class="text-[11px] text-gray-600">Assign one role. Menu access comes from that role’s permissions.</p>
        </div>

        <div class="be-entity-dialog__body max-w-xl space-y-3">
            <div class="be-field">
                <label class="be-field__label">Name *</label>
                <x-erp.input wire:model="name" />
                @error('name') <span class="be-field__error">{{ $message }}</span> @enderror
            </div>
            <div class="be-field">
                <label class="be-field__label">Email *</label>
                <x-erp.input type="email" wire:model="email" />
                @error('email') <span class="be-field__error">{{ $message }}</span> @enderror
            </div>
            <div class="be-field">
                <label class="be-field__label">Role *</label>
                <x-erp.select wire:model="role" :options="$roleOptions" />
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
    </div>
</div>
