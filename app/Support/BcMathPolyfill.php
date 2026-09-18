<?php

namespace App\Support;

/**
 * Float-backed BCMath stand-in used only when ext-bcmath is unavailable.
 */
class BcMathPolyfill
{
    protected static int $scale = 0;

    public static function scale(?int $scale = null): int
    {
        if ($scale !== null) {
            self::$scale = max(0, $scale);
        }

        return self::$scale;
    }

    public static function add(string $num1, string $num2, ?int $scale = null): string
    {
        return self::format((float) $num1 + (float) $num2, $scale);
    }

    public static function sub(string $num1, string $num2, ?int $scale = null): string
    {
        return self::format((float) $num1 - (float) $num2, $scale);
    }

    public static function mul(string $num1, string $num2, ?int $scale = null): string
    {
        return self::format((float) $num1 * (float) $num2, $scale);
    }

    public static function div(string $num1, string $num2, ?int $scale = null): ?string
    {
        if ((float) $num2 == 0.0) {
            return null;
        }

        return self::format((float) $num1 / (float) $num2, $scale);
    }

    public static function mod(string $num1, string $num2, ?int $scale = null): ?string
    {
        if ((float) $num2 == 0.0) {
            return null;
        }

        return self::format(fmod((float) $num1, (float) $num2), $scale);
    }

    public static function pow(string $num, string $exponent, ?int $scale = null): string
    {
        return self::format(((float) $num) ** (float) $exponent, $scale);
    }

    public static function comp(string $num1, string $num2, ?int $scale = null): int
    {
        $scale ??= self::$scale;
        $left = self::format((float) $num1, $scale);
        $right = self::format((float) $num2, $scale);

        return $left <=> $right;
    }

    protected static function format(float $value, ?int $scale = null): string
    {
        $scale ??= self::$scale;

        return number_format($value, $scale, '.', '');
    }
}
