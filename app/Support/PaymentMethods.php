<?php

namespace App\Support;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;

class PaymentMethods
{
    /**
     * Active payment methods for selects: code => name.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return Cache::remember('payment_methods.options', 300, function () {
            return PaymentMethod::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->pluck('name', 'code')
                ->all();
        });
    }

    /**
     * Select options with a blank prompt when methods exist.
     *
     * @return array<string, string>
     */
    public static function selectOptions(string $blank = 'Select method…'): array
    {
        $options = self::options();

        if ($options === []) {
            return ['' => 'No methods — open Payment Method List'];
        }

        return ['' => $blank] + $options;
    }

    public static function defaultCode(?string $preferred = 'cash'): string
    {
        $options = self::options();

        if ($preferred !== null && array_key_exists($preferred, $options)) {
            return $preferred;
        }

        return (string) (array_key_first($options) ?? ($preferred ?? 'cash'));
    }

    public static function labelFor(?string $code): string
    {
        if ($code === null || $code === '') {
            return '—';
        }

        return self::options()[$code] ?? $code;
    }

    public static function isValid(?string $code): bool
    {
        if ($code === null || $code === '') {
            return false;
        }

        return array_key_exists($code, self::options());
    }

    public static function forgetCachedOptions(): void
    {
        Cache::forget('payment_methods.options');
    }
}
