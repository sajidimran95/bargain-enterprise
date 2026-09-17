@props([
    'title',
    'subtitle' => null,
    'showDates' => true,
    'showBasis' => false,
    'showFilterBar' => null,
    'basis' => 'accrual',
    'hideHeader' => false,
    'showExtraFilters' => false,
    'sortBy' => 'default',
    'showEmailModal' => false,
    'showCommentModal' => false,
    'showShareModal' => false,
    'showMemorizeModal' => false,
    'reportComment' => '',
    'shareUrl' => '',
    'memorizeName' => '',
    'emailSubject' => '',
    'datePresetOptions' => null,
    'sortByOptions' => [
        'default' => 'Default',
        'date' => 'Date',
        'amount' => 'Amount',
        'num' => 'Num',
    ],
])

@php
    $showFilterBar = $showFilterBar ?? ($showDates || $showBasis || isset($filters));
    $datePresetOptions = $datePresetOptions ?? \App\Support\QbDatePresets::options();
    $companyName = \App\Models\Setting::getValue('company.name', config('bargain.company_name'));
    $showAnyPopup = $showCommentModal || $showShareModal || $showMemorizeModal || $showEmailModal;
@endphp

<div class="be-page be-report">
    <div class="be-report__toolbar">
        <x-erp.button type="button" wire:click="customizeReport">Customize Report</x-erp.button>
        <x-erp.button type="button" wire:click="commentOnReport">Comment on Report</x-erp.button>
        <x-erp.button type="button" wire:click="shareReportTemplate">Share Template</x-erp.button>
        <x-erp.button type="button" wire:click="memorizeReport">Memorize</x-erp.button>
        <span class="be-report__toolbar-sep" aria-hidden="true"></span>

        <div class="be-report__split" x-data="{ open: false }" @click.outside="open = false">
            <x-erp.button type="button" @click="open = !open">Print ▾</x-erp.button>
            <div class="be-report__menu" x-show="open" x-cloak style="display: none;" @click="open = false">
                <button type="button" class="be-report__menu-item" wire:click="printReport">Print</button>
                <button type="button" class="be-report__menu-item" wire:click="exportPdf">Save as PDF</button>
            </div>
        </div>

        <x-erp.button type="button" wire:click="openEmailModal">E-mail ▾</x-erp.button>

        @if (isset($excel))
            {{ $excel }}
        @else
            <x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button>
        @endif

        <span class="be-report__toolbar-sep" aria-hidden="true"></span>
        <x-erp.button type="button" wire:click="toggleHideHeader">{{ $hideHeader ? 'Show Header' : 'Hide Header' }}</x-erp.button>
        <x-erp.button type="button" wire:click="refreshReport">Refresh</x-erp.button>
        <div class="be-report__toolbar-right">
            <div class="be-field be-report__view">
                <label class="be-field__label">View</label>
                <x-erp.select wire:model.live="sortBy" :options="$sortByOptions" />
            </div>
            <x-erp.workspace-link route="reports.index" class="be-btn" title="Report Center">Report Center</x-erp.workspace-link>
        </div>
    </div>

    @if ($showFilterBar)
        <div class="be-report__filters">
            <div class="be-report__filters-row">
                @if ($showDates)
                    <div class="be-field be-report__dates">
                        <label class="be-field__label">Dates</label>
                        <x-erp.select
                            class="be-report__date-preset"
                            wire:model.live="datePreset"
                            :options="$datePresetOptions"
                        />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">From</label>
                        <x-erp.input type="date" wire:model.live="from" />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">To</label>
                        <x-erp.input type="date" wire:model.live="to" />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Sort By</label>
                        <x-erp.select wire:model.live="sortBy" :options="$sortByOptions" />
                    </div>
                @endif

                @if ($showBasis)
                    <div class="be-field be-report__basis">
                        <label class="be-field__label">Report Basis</label>
                        <div class="be-report__basis-options">
                            <label class="be-report__radio">
                                <input type="radio" wire:model.live="basis" value="accrual"> Accrual
                            </label>
                            <label class="be-report__radio">
                                <input type="radio" wire:model.live="basis" value="cash"> Cash
                            </label>
                        </div>
                    </div>
                @endif

                <button type="button" class="be-report__show-filters" wire:click="toggleShowFilters">
                    {{ $showExtraFilters ? 'Hide Filters' : 'Show Filters' }}
                </button>
            </div>

            @if ($showExtraFilters)
                <div class="be-report__filters-extra">
                    {{ $filters ?? 'No additional filters for this report.' }}
                    @if (filled($reportComment))
                        <p class="be-report__saved-comment"><strong>Comment:</strong> {{ $reportComment }}</p>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div class="be-report__sheet">
        @unless ($hideHeader)
            <div class="be-report__header">
                <div class="be-report__meta">
                    <div>{{ now()->format('g:i A') }}</div>
                    <div>{{ now()->format('m/d/y') }}</div>
                    @if ($showBasis)
                        <div>{{ ucfirst($basis) }} Basis</div>
                    @endif
                </div>
                <div class="be-report__titles">
                    <div class="be-report__company">{{ $companyName }}</div>
                    <h1 class="be-report__title">{{ $title }}</h1>
                    @if ($subtitle)
                        <div class="be-report__subtitle">{{ $subtitle }}</div>
                    @endif
                </div>
            </div>
        @endunless

        <div class="be-report__grid">
            {{ $slot }}
        </div>
    </div>

    @if ($showAnyPopup)
        <div class="be-report-popup" role="dialog" aria-modal="true">
            <div
                class="be-report-popup__backdrop"
                @if ($showCommentModal) wire:click="closeCommentModal"
                @elseif ($showShareModal) wire:click="closeShareModal"
                @elseif ($showMemorizeModal) wire:click="closeMemorizeModal"
                @elseif ($showEmailModal) wire:click="closeEmailModal"
                @endif
            ></div>

            <div class="be-report-popup__panel {{ ($showShareModal || $showEmailModal) ? 'be-report-popup__panel--wide' : '' }}">
                @if ($showCommentModal)
                    <div class="be-report-popup__title">Comment on Report</div>
                    <textarea class="be-input be-report-popup__input" rows="4" wire:model="reportComment" placeholder="Add a note…" autofocus></textarea>
                    @error('reportComment') <p class="be-report-popup__error">{{ $message }}</p> @enderror
                    <div class="be-report-popup__actions">
                        <x-erp.button type="button" wire:click="closeCommentModal">Cancel</x-erp.button>
                        <x-erp.button type="button" variant="primary" wire:click="saveReportComment">OK</x-erp.button>
                    </div>
                @elseif ($showShareModal)
                    <div class="be-report-popup__title">Share Template</div>
                    <p class="be-report-popup__hint">{{ $title }}@if($subtitle) — {{ $subtitle }}@endif</p>
                    <label class="be-field__label">Link (current filters)</label>
                    <input class="be-input be-report-popup__input" type="text" readonly value="{{ $shareUrl }}" onclick="this.select()">
                    <div class="be-report-popup__actions">
                        <x-erp.button type="button" wire:click="closeShareModal">Close</x-erp.button>
                        <x-erp.button type="button" variant="primary" wire:click="copyShareUrl">Copy Link</x-erp.button>
                    </div>
                @elseif ($showMemorizeModal)
                    <div class="be-report-popup__title">Memorize Report</div>
                    <label class="be-field__label">Name</label>
                    <input class="be-input be-report-popup__input" type="text" wire:model="memorizeName" autofocus>
                    @error('memorizeName') <p class="be-report-popup__error">{{ $message }}</p> @enderror
                    <p class="be-report-popup__hint">Saves dates, sort, and basis. Appears under Report Center → Memorized.</p>
                    <div class="be-report-popup__actions">
                        <x-erp.button type="button" wire:click="closeMemorizeModal">Cancel</x-erp.button>
                        <x-erp.button type="button" variant="primary" wire:click="saveMemorizedReport">OK</x-erp.button>
                    </div>
                @elseif ($showEmailModal)
                    <div class="be-report-popup__title">E-mail Report</div>
                    <label class="be-field__label">To</label>
                    <input class="be-input be-report-popup__input" type="email" wire:model="emailTo" placeholder="name@example.com" autofocus>
                    @error('emailTo') <p class="be-report-popup__error">{{ $message }}</p> @enderror
                    <label class="be-field__label">Subject</label>
                    <input class="be-input be-report-popup__input" type="text" wire:model="emailSubject">
                    @error('emailSubject') <p class="be-report-popup__error">{{ $message }}</p> @enderror
                    <p class="be-report-popup__hint">PDF of {{ $title }} with current filters will be attached.</p>
                    <div class="be-report-popup__actions">
                        <x-erp.button type="button" wire:click="closeEmailModal">Cancel</x-erp.button>
                        <x-erp.button type="button" variant="primary" wire:click="sendReportEmail">Send</x-erp.button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
