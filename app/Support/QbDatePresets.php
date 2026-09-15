<?php

namespace App\Support;

use Carbon\Carbon;

class QbDatePresets
{
    /**
     * QuickBooks Desktop Report Center / report filter date list (exact labels).
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'all' => 'All',
            'today' => 'Today',
            'this_week' => 'This Week',
            'this_week_to_date' => 'This Week-to-date',
            'this_month' => 'This Month',
            'this_month_to_date' => 'This Month-to-date',
            'this_fiscal_quarter' => 'This Fiscal Quarter',
            'this_fiscal_quarter_to_date' => 'This Fiscal Quarter-to-date',
            'this_fiscal_year' => 'This Fiscal Year',
            'this_fiscal_year_to_last_month' => 'This Fiscal Year-to-Last Month',
            'this_fiscal_ytd' => 'This Fiscal Year-to-date',
            'yesterday' => 'Yesterday',
            'last_week' => 'Last Week',
            'last_week_to_date' => 'Last Week-to-date',
            'last_month' => 'Last Month',
            'last_month_to_date' => 'Last Month-to-date',
            'last_fiscal_quarter' => 'Last Fiscal Quarter',
            'last_fiscal_quarter_to_date' => 'Last Fiscal Quarter-to-date',
            'last_fiscal_year' => 'Last Fiscal Year',
            'last_fiscal_ytd' => 'Last Fiscal Year-to-date',
            'next_week' => 'Next Week',
            'next_4_weeks' => 'Next 4 Weeks',
            'next_month' => 'Next Month',
            'next_fiscal_quarter' => 'Next Fiscal Quarter',
            'next_fiscal_year' => 'Next Fiscal Year',
            'custom' => 'Custom',
        ];
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function range(string $preset, ?Carbon $now = null): array
    {
        $now ??= now();

        return match ($preset) {
            'all' => [Carbon::parse('2000-01-01')->startOfDay(), $now->copy()->endOfDay()],
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'this_week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'this_week_to_date' => [$now->copy()->startOfWeek(), $now->copy()->endOfDay()],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'this_month_to_date' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'this_fiscal_quarter' => [$now->copy()->firstOfQuarter(), $now->copy()->lastOfQuarter()->endOfDay()],
            'this_fiscal_quarter_to_date' => [$now->copy()->firstOfQuarter(), $now->copy()->endOfDay()],
            'this_fiscal_year', 'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'this_fiscal_year_to_last_month' => [
                $now->copy()->startOfYear(),
                $now->copy()->subMonthNoOverflow()->endOfMonth()->endOfDay(),
            ],
            'this_fiscal_ytd' => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            'last_week' => [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()],
            'last_week_to_date' => [
                $now->copy()->subWeek()->startOfWeek(),
                $now->copy()->subWeek()->endOfDay(),
            ],
            'last_month' => [
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ],
            'last_month_to_date' => [
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->day(
                    min($now->day, $now->copy()->subMonthNoOverflow()->daysInMonth)
                )->endOfDay(),
            ],
            'last_fiscal_quarter' => [
                $now->copy()->subQuarter()->firstOfQuarter(),
                $now->copy()->subQuarter()->lastOfQuarter()->endOfDay(),
            ],
            'last_fiscal_quarter_to_date' => [
                $now->copy()->subQuarter()->firstOfQuarter(),
                $now->copy()->subQuarter()->firstOfQuarter()
                    ->addDays($now->diffInDays($now->copy()->firstOfQuarter()))
                    ->endOfDay(),
            ],
            'last_fiscal_year', 'last_year' => [
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfYear(),
            ],
            'last_fiscal_ytd' => [
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfDay(),
            ],
            'next_week' => [$now->copy()->addWeek()->startOfWeek(), $now->copy()->addWeek()->endOfWeek()],
            'next_4_weeks' => [$now->copy()->startOfDay(), $now->copy()->addWeeks(4)->endOfDay()],
            'next_month' => [
                $now->copy()->addMonthNoOverflow()->startOfMonth(),
                $now->copy()->addMonthNoOverflow()->endOfMonth(),
            ],
            'next_fiscal_quarter' => [
                $now->copy()->addQuarter()->firstOfQuarter(),
                $now->copy()->addQuarter()->lastOfQuarter()->endOfDay(),
            ],
            'next_fiscal_year' => [
                $now->copy()->addYear()->startOfYear(),
                $now->copy()->addYear()->endOfYear(),
            ],
            default => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
        };
    }

    /**
     * @return array{0: string, 1: string}
     */
    public static function dateStrings(string $preset, ?Carbon $now = null): array
    {
        [$from, $to] = self::range($preset, $now);

        return [$from->toDateString(), $to->toDateString()];
    }

    public static function display(string $isoDate): string
    {
        return Carbon::parse($isoDate)->format('n/j/Y');
    }
}
