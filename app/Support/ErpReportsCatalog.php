<?php

namespace App\Support;

class ErpReportsCatalog
{
    /**
     * @return array{tools: list<array<string, mixed>>, categories: list<array<string, mixed>>, footer: list<array<string, mixed>>}
     */
    private static function catalog(): array
    {
        /** @var array{tools: list<array<string, mixed>>, categories: list<array<string, mixed>>, footer: list<array<string, mixed>>} $catalog */
        $catalog = require config_path('erp_reports_catalog.php');

        return $catalog;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function menubarItems(): array
    {
        $catalog = self::catalog();
        $items = [];

        foreach ($catalog['tools'] as $entry) {
            $items[] = self::normalizeEntry($entry);
        }

        $items[] = ['separator' => true];

        foreach ($catalog['categories'] as $category) {
            $children = [];
            foreach ($category['groups'] as $groupIndex => $group) {
                if ($groupIndex > 0) {
                    $children[] = ['separator' => true];
                }
                foreach ($group['reports'] as $report) {
                    $children[] = self::normalizeEntry($report);
                }
            }

            $items[] = [
                'label' => $category['label'],
                'children' => $children,
            ];
        }

        $items[] = ['separator' => true];

        foreach ($catalog['footer'] as $entry) {
            $items[] = self::normalizeEntry($entry);
        }

        return $items;
    }

    /**
     * @return list<array{id: string, label: string, short: string}>
     */
    public static function sidebarCategories(): array
    {
        return collect(self::catalog()['categories'])
            ->map(fn (array $category) => [
                'id' => $category['id'],
                'label' => $category['label'],
                'short' => $category['short'] ?? $category['label'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return array{id: string, label: string, short: string, groups: list<array{title?: string, reports: list<array<string, mixed>}>}|null
     */
    public static function category(string $id): ?array
    {
        $category = collect(self::catalog()['categories'])
            ->firstWhere('id', $id);

        if (! is_array($category)) {
            return null;
        }

        return [
            'id' => $category['id'],
            'label' => $category['label'],
            'short' => $category['short'] ?? $category['label'],
            'groups' => $category['groups'],
        ];
    }

    /**
     * Flat list of Manufacturing & Wholesale reports for the Mfg & Whsle menubar.
     *
     * @return list<array<string, mixed>>
     */
    public static function manufacturingWholesaleMenu(): array
    {
        $category = self::category('mfg-wholesale');
        if ($category === null) {
            return [];
        }

        $children = [];
        foreach ($category['groups'] as $groupIndex => $group) {
            if ($groupIndex > 0) {
                $children[] = ['separator' => true];
            }
            foreach ($group['reports'] as $report) {
                $children[] = self::normalizeEntry($report);
            }
        }

        return $children;
    }

    /**
     * @param  array<string, mixed>  $entry
     * @return array<string, mixed>
     */
    private static function normalizeEntry(array $entry): array
    {
        if (! empty($entry['separator'])) {
            return ['separator' => true];
        }

        $normalized = ['label' => $entry['label']];

        if (! empty($entry['children'])) {
            $normalized['children'] = array_map(
                fn (array $child) => self::normalizeEntry($child),
                $entry['children']
            );

            return $normalized;
        }

        if (! empty($entry['disabled'])) {
            $normalized['disabled'] = true;
        }

        if (! empty($entry['shortcut'])) {
            $normalized['shortcut'] = $entry['shortcut'];
        }

        if (! empty($entry['route'])) {
            $normalized['route'] = $entry['route'];

            return $normalized;
        }

        if (! empty($entry['action'])) {
            $normalized['action'] = $entry['action'];
            if (! empty($entry['message'])) {
                $normalized['message'] = $entry['message'];
            }

            return $normalized;
        }

        $normalized['action'] = 'toast';
        $normalized['message'] = ($entry['label'] ?? 'Report').' — coming soon.';

        return $normalized;
    }
}
