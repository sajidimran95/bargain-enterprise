@if ($showEmailComposer)
    <div class="be-modal">
        <div class="be-modal__backdrop" wire:click="closeEmailComposer"></div>
        <div class="be-modal__panel be-modal__panel--sm" wire:click.stop>
            <div class="be-modal__header">
                <h3 class="be-modal__title">Email Document</h3>
                <button type="button" class="be-modal__close" wire:click="closeEmailComposer">×</button>
            </div>
            <div class="be-modal__body space-y-2">
                <div class="be-field">
                    <label class="be-field__label">To</label>
                    <x-erp.input type="email" wire:model="emailTo" />
                    @error('emailTo') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Subject</label>
                    <x-erp.input wire:model="emailSubject" />
                </div>
            </div>
            <div class="be-modal__footer">
                <button type="button" class="be-btn be-btn--primary" wire:click="sendRibbonEmail">Send</button>
                <button type="button" class="be-btn" wire:click="closeEmailComposer">Cancel</button>
            </div>
        </div>
    </div>
@endif

@if ($showAttachModal)
    <div class="be-modal">
        <div class="be-modal__backdrop" wire:click="closeAttachModal"></div>
        <div class="be-modal__panel be-modal__panel--sm" wire:click.stop>
            <div class="be-modal__header">
                <h3 class="be-modal__title">Attach File {{ count($pendingAttachments) ? '('.count($pendingAttachments).')' : '' }}</h3>
                <button type="button" class="be-modal__close" wire:click="closeAttachModal">×</button>
            </div>
            <div class="be-modal__body space-y-2">
                <input type="file" wire:model="pendingAttachments" multiple class="be-input">
                @error('pendingAttachments.*') <span class="be-field__error">{{ $message }}</span> @enderror
                <ul class="space-y-1 text-[11px]">
                    @foreach ($pendingAttachments as $i => $file)
                        <li class="flex justify-between gap-2">
                            <span>{{ method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : 'file' }}</span>
                            <button type="button" class="be-link-btn" wire:click="removePendingAttachment({{ $i }})">Remove</button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="be-modal__footer">
                <button type="button" class="be-btn be-btn--primary" wire:click="confirmAttachments">OK</button>
                <button type="button" class="be-btn" wire:click="closeAttachModal">Cancel</button>
            </div>
        </div>
    </div>
@endif

@if ($showTimeCostsModal)
    <div class="be-modal">
        <div class="be-modal__backdrop" wire:click="closeTimeCostsModal"></div>
        <div class="be-modal__panel be-modal__panel--sm" wire:click.stop>
            <div class="be-modal__header">
                <h3 class="be-modal__title">Add Time/Costs</h3>
                <button type="button" class="be-modal__close" wire:click="closeTimeCostsModal">×</button>
            </div>
            <div class="be-modal__body space-y-2">
                <div class="be-field">
                    <label class="be-field__label">Description</label>
                    <x-erp.input wire:model="timeCostDescription" />
                    @error('timeCostDescription') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-form-grid">
                    <div class="be-field">
                        <label class="be-field__label">Qty</label>
                        <x-erp.input type="number" step="0.0001" min="0" wire:model="timeCostQty" />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Amount / Rate</label>
                        <x-erp.input type="number" step="0.01" min="0" wire:model="timeCostAmount" />
                    </div>
                </div>
            </div>
            <div class="be-modal__footer">
                <button type="button" class="be-btn be-btn--primary" wire:click="applyTimeCosts">Add</button>
                <button type="button" class="be-btn" wire:click="closeTimeCostsModal">Cancel</button>
            </div>
        </div>
    </div>
@endif
