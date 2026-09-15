<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function record(
        string $action,
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null,
    ): AuditLog {
        return AuditLog::query()->create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues ?? $this->snapshot($model),
        ]);
    }

    /**
     * @param  list<string>|null  $only
     * @return array<string, mixed>
     */
    public function snapshot(Model $model, ?array $only = null): array
    {
        $attributes = $model->attributesToArray();

        if ($only !== null) {
            $attributes = array_intersect_key($attributes, array_flip($only));
        }

        unset($attributes['password'], $attributes['remember_token']);

        return $attributes;
    }

    /**
     * @param  list<string>|null  $only
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    public function diff(Model $before, Model $after, ?array $only = null): array
    {
        $old = $this->snapshot($before, $only);
        $new = $this->snapshot($after, $only);

        $changedOld = [];
        $changedNew = [];

        foreach (array_unique([...array_keys($old), ...array_keys($new)]) as $key) {
            $left = $old[$key] ?? null;
            $right = $new[$key] ?? null;

            if ($left != $right) {
                $changedOld[$key] = $left;
                $changedNew[$key] = $right;
            }
        }

        return [$changedOld, $changedNew];
    }
}
