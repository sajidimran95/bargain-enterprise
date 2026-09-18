<?php

use App\Support\BcMathPolyfill;

/**
 * Polyfill for PHP BCMath when the extension is not loaded (common on some Laragon/web SAPIs).
 * Loaded early via Composer so Livewire pages never hit "undefined function bcadd()".
 */
if (! function_exists('bcadd')) {
    function bcadd(string $num1, string $num2, ?int $scale = null): string
    {
        return BcMathPolyfill::add($num1, $num2, $scale);
    }
}

if (! function_exists('bcsub')) {
    function bcsub(string $num1, string $num2, ?int $scale = null): string
    {
        return BcMathPolyfill::sub($num1, $num2, $scale);
    }
}

if (! function_exists('bcmul')) {
    function bcmul(string $num1, string $num2, ?int $scale = null): string
    {
        return BcMathPolyfill::mul($num1, $num2, $scale);
    }
}

if (! function_exists('bcdiv')) {
    function bcdiv(string $num1, string $num2, ?int $scale = null): ?string
    {
        return BcMathPolyfill::div($num1, $num2, $scale);
    }
}

if (! function_exists('bccomp')) {
    function bccomp(string $num1, string $num2, ?int $scale = null): int
    {
        return BcMathPolyfill::comp($num1, $num2, $scale);
    }
}

if (! function_exists('bcmod')) {
    function bcmod(string $num1, string $num2, ?int $scale = null): ?string
    {
        return BcMathPolyfill::mod($num1, $num2, $scale);
    }
}

if (! function_exists('bcpow')) {
    function bcpow(string $num, string $exponent, ?int $scale = null): string
    {
        return BcMathPolyfill::pow($num, $exponent, $scale);
    }
}

if (! function_exists('bcscale')) {
    function bcscale(?int $scale = null): int
    {
        return BcMathPolyfill::scale($scale);
    }
}
