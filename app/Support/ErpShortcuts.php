<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class ErpShortcuts
{
    /**
     * @return list<array{key: string, label: string, route: string, icon: string, match: string|list<string>, active: bool}>
     */
    public static function forUser(?User $user): array
    {
        $catalog = config('erp_shortcuts.catalog', []);
        $pinned = self::pinnedKeys($user);

        $items = [];
        foreach ($pinned as $key) {
            if (! isset($catalog[$key])) {
                continue;
            }
            $item = $catalog[$key];
            $permission = $item['permission'] ?? null;
            if ($permission && $user && ! $user->hasPermission($permission)) {
                continue;
            }
            if (! Route::has($item['route'])) {
                continue;
            }

            $match = $item['match'] ?? $item['route'];
            $items[] = [
                'key' => $key,
                'label' => $item['label'],
                'route' => $item['route'],
                'icon' => $item['icon'],
                'match' => $match,
                'active' => self::routeMatches($match),
            ];
        }

        return $items;
    }

    /**
     * Shortcuts not currently pinned that the user may add.
     *
     * @return list<array{key: string, label: string, route: string, icon: string}>
     */
    public static function availableToAdd(?User $user): array
    {
        $catalog = config('erp_shortcuts.catalog', []);
        $pinned = self::pinnedKeys($user);
        $available = [];

        foreach ($catalog as $key => $item) {
            if (in_array($key, $pinned, true)) {
                continue;
            }
            $permission = $item['permission'] ?? null;
            if ($permission && $user && ! $user->hasPermission($permission)) {
                continue;
            }
            if (! Route::has($item['route'])) {
                continue;
            }
            $available[] = [
                'key' => $key,
                'label' => $item['label'],
                'route' => $item['route'],
                'icon' => $item['icon'],
            ];
        }

        usort($available, fn (array $a, array $b) => strcasecmp($a['label'], $b['label']));

        return $available;
    }

    /**
     * @return list<string>
     */
    public static function pinnedKeys(?User $user): array
    {
        $defaults = config('erp_shortcuts.defaults', []);
        if (! $user) {
            return $defaults;
        }

        $stored = Setting::getValue(self::settingKey($user), null);
        if (! is_array($stored) || $stored === []) {
            return $defaults;
        }

        return array_values(array_filter($stored, 'is_string'));
    }

    /**
     * @param  list<string>  $keys
     */
    public static function savePinned(User $user, array $keys): void
    {
        $catalog = config('erp_shortcuts.catalog', []);
        $clean = [];
        foreach ($keys as $key) {
            if (is_string($key) && isset($catalog[$key]) && ! in_array($key, $clean, true)) {
                $clean[] = $key;
            }
        }

        Setting::setValue(self::settingKey($user), $clean, 'json', 'shortcuts');
    }

    public static function add(User $user, string $key): void
    {
        $keys = self::pinnedKeys($user);
        if (! in_array($key, $keys, true)) {
            $keys[] = $key;
        }
        self::savePinned($user, $keys);
    }

    public static function remove(User $user, string $key): void
    {
        self::savePinned($user, array_values(array_filter(
            self::pinnedKeys($user),
            fn (string $k) => $k !== $key
        )));
    }

    public static function reset(User $user): void
    {
        Setting::query()->where('key', self::settingKey($user))->delete();
        Cache::forget('setting.value.'.self::settingKey($user));
        Cache::forget('setting.'.self::settingKey($user));
    }

    protected static function settingKey(User $user): string
    {
        return 'user.'.$user->id.'.sidebar_shortcuts';
    }

    /**
     * @param  string|list<string>  $match
     */
    protected static function routeMatches(string|array $match): bool
    {
        foreach ((array) $match as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }
}
