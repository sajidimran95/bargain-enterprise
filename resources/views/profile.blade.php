<x-app-layout>
    <x-slot name="title">Profile</x-slot>
    <x-slot name="windowTitle">Profile</x-slot>

    <div class="be-page">
        <div class="be-panel">
            <div class="be-panel__header">
                <h1 class="be-panel__title">{{ __('Profile') }}</h1>
            </div>
            <div class="be-panel__body space-y-4">
                <div class="border p-3" style="border-color: var(--be-border)">
                    <livewire:profile.update-profile-information-form />
                </div>
                <div class="border p-3" style="border-color: var(--be-border)">
                    <livewire:profile.update-password-form />
                </div>
                <div class="border p-3" style="border-color: var(--be-border)">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
