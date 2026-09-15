<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class DocumentNumbers
{
    /**
     * @param  class-string<Model>  $modelClass
     */
    public static function next(string $modelClass, string $column, string $prefix): string
    {
        $latest = $modelClass::query()->orderByDesc('id')->value($column);
        $sequence = 1;

        if ($latest !== null && preg_match('/(\d+)$/', (string) $latest, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return $prefix.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }
}
