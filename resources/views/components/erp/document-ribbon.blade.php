@props([
    'findRouteName',
    'newRouteName',
    'listRouteName' => null,
    'listLabel' => 'List',
    'activeTab' => 'main',
    'isPending' => false,
    'showList' => false,
    'showApplyCredits' => false,
    'showReceivePayments' => true,
    'showRefundCredit' => true,
    'showAddTimeCosts' => true,
    'applyCreditsRouteName' => 'credit-memos.index',
    'receivePaymentsRouteName' => 'payments.create',
    'refundCreditRouteName' => 'credit-memos.create',
    'attachmentCount' => 0,
    'formattingFontSize' => '12',
    'formattingBold' => false,
])

<div class="be-ribbon">
    <div class="be-ribbon__tabs">
        <button type="button" class="be-ribbon__tab {{ $activeTab === 'main' ? 'is-active' : '' }}" wire:click="$set('ribbonTab', 'main')">Main</button>
        <button type="button" class="be-ribbon__tab {{ $activeTab === 'formatting' ? 'is-active' : '' }}" wire:click="$set('ribbonTab', 'formatting')">Formatting</button>
        <button type="button" class="be-ribbon__tab {{ $activeTab === 'send' ? 'is-active' : '' }}" wire:click="$set('ribbonTab', 'send')">Send/Ship</button>
        <button type="button" class="be-ribbon__tab {{ $activeTab === 'reports' ? 'is-active' : '' }}" wire:click="$set('ribbonTab', 'reports')">Reports</button>
    </div>

    @if ($activeTab === 'main')
        <div class="be-ribbon__actions">
            <div class="be-ribbon__find">
                <x-erp.workspace-link :route="$findRouteName" class="be-ribbon__btn" title="Find">
                    <span class="be-ribbon__icon">🔎</span><span>Find</span>
                </x-erp.workspace-link>
                <div class="be-ribbon__nav">
                    <button type="button" class="be-ribbon__nav-btn" title="Previous" wire:click="findPreviousDocument">◀</button>
                    <button type="button" class="be-ribbon__nav-btn" title="Next" wire:click="findNextDocument">▶</button>
                </div>
            </div>
            <x-erp.workspace-link :route="$newRouteName" class="be-ribbon__btn" title="New">
                <span class="be-ribbon__icon">📄+</span><span>New</span>
            </x-erp.workspace-link>
            <button type="button" class="be-ribbon__btn" wire:click="saveAndClose" title="Save">
                <span class="be-ribbon__icon">💾</span><span>Save</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="clearForm" wire:confirm="Clear this form?" title="Delete / Clear">
                <span class="be-ribbon__icon">✖</span><span>Delete</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="createCopy" title="Create a Copy">
                <span class="be-ribbon__icon">⧉</span><span>Create a Copy</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="memorize" title="Memorize">
                <span class="be-ribbon__icon">★</span><span>Memorize</span>
            </button>
            <button type="button" class="be-ribbon__btn {{ $isPending ? 'is-active' : '' }}" wire:click="togglePending" title="Mark As Pending">
                <span class="be-ribbon__icon">⏱</span><span>Mark As Pending</span>
            </button>

            <span class="be-ribbon__sep"></span>

            <button type="button" class="be-ribbon__btn" wire:click="printDocument" title="Print">
                <span class="be-ribbon__icon">🖨▾</span><span>Print</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="emailDocument" title="Email">
                <span class="be-ribbon__icon">✉▾</span><span>Email</span>
            </button>
            <div class="be-ribbon__later">
                <label class="be-ribbon__check"><input type="checkbox" wire:model.live="print_later"> Print Later</label>
                <label class="be-ribbon__check"><input type="checkbox" wire:model.live="email_later"> Email Later</label>
            </div>

            <span class="be-ribbon__sep"></span>

            <button type="button" class="be-ribbon__btn" wire:click="attachFile" title="Attach File">
                <span class="be-ribbon__icon">📎</span><span>Attach File{{ $attachmentCount ? ' ('.$attachmentCount.')' : '' }}</span>
            </button>

            @if ($showAddTimeCosts)
                <button type="button" class="be-ribbon__btn" wire:click="openTimeCostsModal" title="Add Time/Costs">
                    <span class="be-ribbon__icon">⏱</span><span>Add Time/Costs</span>
                </button>
            @endif

            {{ $extra ?? '' }}

            @if ($showApplyCredits)
                <x-erp.workspace-link :route="$applyCreditsRouteName" class="be-ribbon__btn">
                    <span class="be-ribbon__icon">⇄</span><span>Apply Credits</span>
                </x-erp.workspace-link>
            @endif
            @if ($showReceivePayments)
                <x-erp.workspace-link :route="$receivePaymentsRouteName" class="be-ribbon__btn">
                    <span class="be-ribbon__icon">💵</span><span>Receive Payments</span>
                </x-erp.workspace-link>
            @endif
            <button type="button" class="be-ribbon__btn" wire:click="createBatch" title="Create a Batch">
                <span class="be-ribbon__icon">☰</span><span>Create a Batch</span>
            </button>
            @if ($showRefundCredit)
                <x-erp.workspace-link :route="$refundCreditRouteName" class="be-ribbon__btn">
                    <span class="be-ribbon__icon">↩</span><span>Refund/Credit</span>
                </x-erp.workspace-link>
            @endif

            @if ($showList && $listRouteName)
                <x-erp.workspace-link :route="$listRouteName" class="be-ribbon__btn be-ribbon__btn--list">
                    <span class="be-ribbon__icon">☰</span><span>{{ $listLabel }}</span>
                </x-erp.workspace-link>
            @endif
        </div>
    @elseif ($activeTab === 'formatting')
        <div class="be-ribbon__actions">
            <label class="be-ribbon__check">Font size
                <select wire:model.live="formattingFontSize" class="be-input be-input--combo ml-1 w-16">
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="14">14</option>
                    <option value="16">16</option>
                </select>
            </label>
            <button type="button" class="be-ribbon__btn {{ $formattingBold ? 'is-active' : '' }}" wire:click="toggleFormattingBold">
                <span class="be-ribbon__icon font-bold">B</span><span>Bold message</span>
            </button>
            <span class="self-center px-2 text-[11px] text-gray-500">Applies to Customer Message / Memo text.</span>
        </div>
    @elseif ($activeTab === 'send')
        <div class="be-ribbon__actions">
            <button type="button" class="be-ribbon__btn" wire:click="emailDocument">
                <span class="be-ribbon__icon">✉</span><span>Email</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="printDocument">
                <span class="be-ribbon__icon">🖨</span><span>Print</span>
            </button>
            <label class="be-ribbon__check"><input type="checkbox" wire:model.live="email_later"> Email Later</label>
            <label class="be-ribbon__check"><input type="checkbox" wire:model.live="print_later"> Print Later</label>
            <button type="button" class="be-ribbon__btn" wire:click="createBatch">
                <span>Batch Print/Email Later</span>
            </button>
        </div>
    @else
        <div class="be-ribbon__actions">
            <x-erp.workspace-link route="reports.open-balance" class="be-ribbon__btn"><span>Customer Open Balance</span></x-erp.workspace-link>
            <x-erp.workspace-link route="reports.sales-by-item" class="be-ribbon__btn"><span>Sales by Item</span></x-erp.workspace-link>
            <x-erp.workspace-link route="reports.inventory" class="be-ribbon__btn"><span>Inventory Stock</span></x-erp.workspace-link>
            <x-erp.workspace-link route="invoices.index" class="be-ribbon__btn"><span>Invoice List</span></x-erp.workspace-link>
        </div>
    @endif
</div>
