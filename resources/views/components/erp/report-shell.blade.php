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
    'datePresetOptions' => [
        'today' => 'Today',
        'this_week' => 'This Week',
        'last_week' => 'Last Week',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        'this_year' => 'This Year',
        'last_year' => 'Last Year',
        'this_fiscal_year' => 'This Fiscal Year',
        'last_fiscal_year' => 'Last Fiscal Year',
        'all' => 'All Dates',
        'custom' => 'Custom',
    ],
    'sortByOptions' => [
        'default' => 'Default',
        'date' => 'Date',
        'amount' => 'Amount',
        'num' => 'Num',
    ],
])

@php
    $showFilterBar = $showFilterBar ?? ($showDates || $showBasis || isset($filters));
@endphp

<div class="be-page be-report">
    <div class="be-report__toolbar">
        <x-erp.button type="button" wire:click="customizeReport">Customize Report</x-erp.button>
        <x-erp.button type="button" wire:click="commentOnReport">Comment on Report</x-erp.button>
        <x-erp.button type="button" wire:click="shareReportTemplate">Share Template</x-erp.button>
        <x-erp.button type="button" wire:click="memorizeReport">Memorize</x-erp.button>
        <span class="be-report__toolbar-sep" aria-hidden="true"></span>
        <x-erp.button type="button" onclick="window.print()">Print ▾</x-erp.button>
        <x-erp.button type="button" wire:click="openEmailModal">E-mail ▾</x-erp.button>
        @if (isset($excel))
            {{ $excel }}
        @endif
        <span class="be-report__toolbar-sep" aria-hidden="true"></span>
        <x-erp.button type="button" wire:click="toggleHideHeader">{{ $hideHeader ? 'Show Header' : 'Hide Header' }}</x-erp.button>
        <x-erp.button type="button" wire:click="refreshReport">Refresh</x-erp.button>
        <div class="be-report__toolbar-right">
            <div class="be-field be-report__view">
                <label class="be-field__label">View</label>
                <x-erp.select wire:model.live="sortBy" :options="$sortByOptions" />
            </div>
            <a href="{{ route('reports.index') }}" class="be-btn">Report Center</a>
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
                    <div class="be-report__company">{{ config('bargain.company_name', config('app.name')) }}</div>
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

    <x-erp.email-modal :show="$showEmailModal" />
</div>
