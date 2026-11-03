<?php

namespace App\Support;

use App\Models\Account;

class SystemAccounts
{
    /**
     * Default GL accounts required for sales / purchasing / banking posts.
     *
     * @return list<array{number: string, name: string, type: string, subtype: string}>
     */
    public static function catalog(): array
    {
        return [
            ['number' => '1000', 'name' => 'Cash', 'type' => 'asset', 'subtype' => 'cash'],
            ['number' => '1010', 'name' => 'Operating Checking', 'type' => 'asset', 'subtype' => 'bank'],
            ['number' => '1020', 'name' => 'Business Checking', 'type' => 'asset', 'subtype' => 'bank'],
            ['number' => '1050', 'name' => 'Undeposited Funds', 'type' => 'asset', 'subtype' => 'other_current'],
            ['number' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'subtype' => 'ar'],
            ['number' => '1300', 'name' => 'Inventory Asset', 'type' => 'asset', 'subtype' => 'inventory'],
            ['number' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'subtype' => 'ap'],
            ['number' => '2200', 'name' => 'Sales Tax Payable', 'type' => 'liability', 'subtype' => 'tax'],
            ['number' => '3000', 'name' => 'Owner Equity', 'type' => 'equity', 'subtype' => 'equity'],
            ['number' => '4000', 'name' => 'Sales Revenue', 'type' => 'income', 'subtype' => 'sales'],
            ['number' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'cogs', 'subtype' => 'cogs'],
            ['number' => '6000', 'name' => 'Operating Expenses', 'type' => 'expense', 'subtype' => 'expense'],
        ];
    }

    public static function ensureAll(): void
    {
        foreach (self::catalog() as $account) {
            self::ensure($account['number']);
        }
    }

    public static function ensure(string $number): Account
    {
        $existing = Account::query()->where('number', $number)->first();
        if ($existing !== null) {
            return $existing;
        }

        $definition = collect(self::catalog())->firstWhere('number', $number);
        if ($definition === null) {
            throw new \RuntimeException("Unknown system account [{$number}].");
        }

        return Account::query()->create($definition + [
            'is_active' => true,
            'is_system' => true,
        ]);
    }

    public static function findOrEnsure(string $numberOrName): ?Account
    {
        $account = Account::query()
            ->where(function ($q) use ($numberOrName) {
                $q->where('number', $numberOrName)->orWhere('name', $numberOrName);
            })
            ->first();

        if ($account !== null) {
            return $account;
        }

        $byNumber = collect(self::catalog())->firstWhere('number', $numberOrName);
        if ($byNumber !== null) {
            return self::ensure($byNumber['number']);
        }

        $byName = collect(self::catalog())->firstWhere('name', $numberOrName);
        if ($byName !== null) {
            return self::ensure($byName['number']);
        }

        return null;
    }
}
