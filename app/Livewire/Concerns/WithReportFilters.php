<?php

namespace App\Livewire\Concerns;

use App\Models\Setting;
use App\Support\ErpReportsCatalog;
use App\Support\QbDatePresets;
use App\Support\XlsxExporter;
use Carbon\Carbon;
use Illuminate\Support\Js;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait WithReportFilters
{
    public string $datePreset = 'this_month';

    public string $from = '';

    public string $to = '';

    public string $basis = 'accrual';

    public string $sortBy = 'default';

    public bool $hideHeader = false;

    public bool $showExtraFilters = false;

    public bool $showCommentModal = false;

    public bool $showShareModal = false;

    public bool $showMemorizeModal = false;

    public string $reportComment = '';

    public string $shareUrl = '';

    public string $memorizeName = '';

    public int $refreshTick = 0;

    public function bootWithReportFilters(): void
    {
        if ($this->from === '' || $this->to === '') {
            $this->applyDatePreset($this->datePreset);
        }
    }

    public function mountWithReportFilters(): void
    {
        $this->restoreMemorizedSettings();
        $this->applySharedTemplateQuery();
        $this->reportComment = (string) Setting::getValue($this->reportCommentStorageKey(), '');
        $this->memorizeName = $this->defaultMemorizeName();
        $this->shareUrl = $this->buildShareUrl();
    }

    public function updatedDatePreset(string $value): void
    {
        $this->applyDatePreset($value);
        $this->shareUrl = $this->buildShareUrl();
    }

    public function updatedFrom(): void
    {
        $this->datePreset = 'custom';
        $this->shareUrl = $this->buildShareUrl();
    }

    public function updatedTo(): void
    {
        $this->datePreset = 'custom';
        $this->shareUrl = $this->buildShareUrl();
    }

    public function updatedBasis(): void
    {
        $this->shareUrl = $this->buildShareUrl();
    }

    public function updatedSortBy(): void
    {
        $this->shareUrl = $this->buildShareUrl();
    }

    public function applyDatePreset(?string $preset = null): void
    {
        $preset ??= $this->datePreset;
        $this->datePreset = $preset;

        if ($preset === 'custom') {
            if ($this->from === '' || $this->to === '') {
                [$from, $to] = QbDatePresets::dateStrings('this_month');
                $this->from = $from;
                $this->to = $to;
            }

            return;
        }

        [$from, $to] = QbDatePresets::dateStrings($preset);
        $this->from = $from;
        $this->to = $to;
    }

    public function refreshReport(): void
    {
        $this->refreshTick++;
        $this->shareUrl = $this->buildShareUrl();
        $this->dispatch('be-toast', message: 'Report refreshed.');
    }

    public function toggleHideHeader(): void
    {
        $this->hideHeader = ! $this->hideHeader;
    }

    public function toggleShowFilters(): void
    {
        $this->showExtraFilters = ! $this->showExtraFilters;
    }

    public function customizeReport(): void
    {
        $this->showExtraFilters = true;
        $this->dispatch('be-toast', message: 'Customize Report — use Dates, Sort By, Basis, and filters below.');
    }

    public function commentOnReport(): void
    {
        $this->closeToolbarPopups();
        $this->reportComment = (string) Setting::getValue($this->reportCommentStorageKey(), '');
        $this->showCommentModal = true;
    }

    public function closeCommentModal(): void
    {
        $this->showCommentModal = false;
    }

    public function saveReportComment(): void
    {
        abort_unless(auth()->check(), 403);

        $this->validate([
            'reportComment' => ['nullable', 'string', 'max:2000'],
        ]);

        $comment = trim($this->reportComment);
        $this->reportComment = $comment;

        Setting::setValue($this->reportCommentStorageKey(), $comment, 'string', 'reports');
        session()->put($this->reportCommentStorageKey(), $comment);

        $this->showCommentModal = false;
        $this->dispatch('be-toast', message: $comment !== ''
            ? 'Report comment saved.'
            : 'Report comment cleared.');
    }

    public function shareReportTemplate(): void
    {
        $this->closeToolbarPopups();
        $this->shareUrl = $this->buildShareUrl();
        $this->showShareModal = true;
    }

    public function closeShareModal(): void
    {
        $this->showShareModal = false;
    }

    public function copyShareUrl(): void
    {
        $this->shareUrl = $this->buildShareUrl();
        $this->js('navigator.clipboard.writeText('.Js::from($this->shareUrl).').then(() => {}).catch(() => {})');
        $this->dispatch('be-toast', message: 'Share template link copied.');
    }

    public function memorizeReport(): void
    {
        $this->closeToolbarPopups();
        $this->memorizeName = $this->defaultMemorizeName();
        $this->showMemorizeModal = true;
    }

    public function closeMemorizeModal(): void
    {
        $this->showMemorizeModal = false;
    }

    public function saveMemorizedReport(): void
    {
        abort_unless(auth()->check(), 403);

        $this->validate([
            'memorizeName' => ['required', 'string', 'max:120'],
        ]);

        $payload = [
            'name' => trim($this->memorizeName),
            'datePreset' => $this->datePreset,
            'from' => $this->from,
            'to' => $this->to,
            'basis' => $this->basis,
            'sortBy' => $this->sortBy,
            'hideHeader' => $this->hideHeader,
            'showExtraFilters' => $this->showExtraFilters,
            'route' => request()->route()?->getName(),
            'saved_at' => now()->toIso8601String(),
        ];

        Setting::setValue($this->reportMemorizeStorageKey(), $payload, 'json', 'reports');
        session()->put($this->reportMemorizeStorageKey(), $payload);

        $this->addToReportCenterMemorized();

        $this->showMemorizeModal = false;
        $this->dispatch('be-toast', message: 'Memorized “'.$payload['name'].'”. Open Report Center → Memorized to find it.');
    }

    public function printReport(): void
    {
        $this->js('window.print()');
    }

    /**
     * Download a QuickBooks-style .xlsm report (not CSV).
     *
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, string|int|float|null>>  $rows
     */
    protected function exportReportCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $title = method_exists($this, 'reportPdfTitle') ? $this->reportPdfTitle() : null;
        $subtitle = method_exists($this, 'reportPdfSubtitle')
            ? $this->reportPdfSubtitle()
            : (method_exists($this, 'reportPeriodLabel') ? $this->reportPeriodLabel() : null);

        return app(XlsxExporter::class)->download(
            $filename,
            $headers,
            $rows,
            title: is_string($title) ? $title : null,
            subtitle: is_string($subtitle) ? $subtitle : null,
        );
    }

    /**
     * @return array<string, string>
     */
    protected function datePresetOptions(): array
    {
        return QbDatePresets::options();
    }

    /**
     * @return array<string, string>
     */
    protected function sortByOptions(): array
    {
        return [
            'default' => 'Default',
            'date' => 'Date',
            'amount' => 'Amount',
            'num' => 'Num',
        ];
    }

    protected function reportPeriodLabel(): string
    {
        $from = Carbon::parse($this->from)->startOfDay();
        $to = Carbon::parse($this->to)->startOfDay();

        if ($from->isSameDay($to)) {
            return $from->format('F j, Y');
        }

        if ($from->day === 1 && $to->isSameDay($to->copy()->endOfMonth()->startOfDay()) && $from->isSameMonth($to)) {
            return $from->format('F Y');
        }

        if (
            $from->month === 1 && $from->day === 1
            && $to->month === 12 && $to->day === 31
            && $from->year === $to->year
        ) {
            return 'January through December '.$from->year;
        }

        if ($from->year === $to->year && $from->day === 1 && $to->isSameDay($to->copy()->endOfMonth()->startOfDay())) {
            return $from->format('F').' through '.$to->format('F Y');
        }

        return $from->format('F j, Y').' through '.$to->format('F j, Y');
    }

    protected function reportSettingsKey(): string
    {
        return str(class_basename(static::class))->kebab()->toString();
    }

    protected function defaultMemorizeName(): string
    {
        $title = method_exists($this, 'reportPdfTitle')
            ? (string) $this->reportPdfTitle()
            : str(class_basename(static::class))->headline()->toString();

        return $title.' — '.$this->reportPeriodLabel();
    }

    protected function buildShareUrl(): string
    {
        $query = array_filter([
            'embed' => request()->boolean('embed') ? '1' : null,
            'tab_id' => request('tab_id'),
            'datePreset' => $this->datePreset,
            'from' => $this->from,
            'to' => $this->to,
            'basis' => $this->basis,
            'sortBy' => $this->sortBy,
        ], fn ($value) => $value !== null && $value !== '');

        $base = url()->current();

        return $query === [] ? $base : $base.'?'.http_build_query($query);
    }

    private function closeToolbarPopups(): void
    {
        $this->showCommentModal = false;
        $this->showShareModal = false;
        $this->showMemorizeModal = false;
        if (property_exists($this, 'showEmailModal')) {
            $this->showEmailModal = false;
        }
    }

    private function reportMemorizeStorageKey(): string
    {
        return 'report.memorized.'.(int) auth()->id().'.'.$this->reportSettingsKey();
    }

    private function reportCommentStorageKey(): string
    {
        return 'report.comment.'.(int) auth()->id().'.'.$this->reportSettingsKey();
    }

    private function addToReportCenterMemorized(): void
    {
        $routeName = request()->route()?->getName();
        if (! is_string($routeName) || $routeName === '') {
            return;
        }

        $catalogKey = collect(ErpReportsCatalog::flatReports())
            ->first(fn (array $report) => ($report['route'] ?? null) === $routeName)['key'] ?? null;

        if (! is_string($catalogKey) || $catalogKey === '') {
            return;
        }

        $userId = (int) auth()->id();
        $settingKey = "report_center.memorized.{$userId}";
        /** @var list<string> $keys */
        $keys = array_values(array_filter(Setting::getValue($settingKey, []) ?: []));

        if (! in_array($catalogKey, $keys, true)) {
            $keys[] = $catalogKey;
            Setting::setValue($settingKey, $keys, 'json', 'reports');
        }
    }

    private function restoreMemorizedSettings(): void
    {
        if (! auth()->check()) {
            return;
        }

        if (request()->filled('datePreset') || request()->filled('from') || request()->filled('to')) {
            return;
        }

        $key = $this->reportMemorizeStorageKey();
        /** @var array<string, mixed>|null $saved */
        $saved = session($key);
        if (! is_array($saved) || $saved === []) {
            $saved = Setting::getValue($key);
        }

        if (! is_array($saved) || $saved === []) {
            return;
        }

        $this->applyReportFilterState($saved);

        if (isset($saved['name']) && is_string($saved['name']) && $saved['name'] !== '') {
            $this->memorizeName = $saved['name'];
        }
    }

    private function applySharedTemplateQuery(): void
    {
        $query = array_filter([
            'datePreset' => request('datePreset'),
            'from' => request('from'),
            'to' => request('to'),
            'basis' => request('basis'),
            'sortBy' => request('sortBy'),
        ], fn ($value) => is_string($value) && $value !== '');

        if ($query === []) {
            return;
        }

        $this->applyReportFilterState($query);
    }

    /**
     * @param  array<string, mixed>  $state
     */
    private function applyReportFilterState(array $state): void
    {
        if (isset($state['datePreset']) && is_string($state['datePreset'])) {
            $this->datePreset = $state['datePreset'];
            $this->applyDatePreset($this->datePreset);
        }

        if (isset($state['from'], $state['to']) && is_string($state['from']) && is_string($state['to'])) {
            $this->from = $state['from'];
            $this->to = $state['to'];
            if (! isset($state['datePreset'])) {
                $this->datePreset = 'custom';
            }
        }

        if (isset($state['basis']) && is_string($state['basis']) && in_array($state['basis'], ['accrual', 'cash'], true)) {
            $this->basis = $state['basis'];
        }

        if (isset($state['sortBy']) && is_string($state['sortBy'])) {
            $this->sortBy = $state['sortBy'];
        }

        if (array_key_exists('hideHeader', $state)) {
            $this->hideHeader = (bool) $state['hideHeader'];
        }

        if (array_key_exists('showExtraFilters', $state)) {
            $this->showExtraFilters = (bool) $state['showExtraFilters'];
        }
    }
}
