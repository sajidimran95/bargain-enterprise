<?php

namespace App\Support;

use App\Models\PaymentMethod;

class PaymentMethods
{
    /**
     * Active payment methods for selects: code => name.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return PaymentMethod::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name', 'code')
            ->all();
    }

    public static function defaultCode(?string $preferred = 'cash'): string
    {
        $options = self::options();

        if ($preferred !== null && array_key_exists($preferred, $options)) {
            return $preferred;
        }

        return (string) (array_key_first($options) ?? ($preferred ?? 'cash'));
    }
}
