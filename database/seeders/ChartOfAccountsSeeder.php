<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
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

        foreach ($accounts as $account) {
            Account::query()->updateOrCreate(
                ['number' => $account['number']],
                $account + ['is_active' => true, 'is_system' => true]
            );
        }

        BankAccount::query()->updateOrCreate(
            ['name' => 'Operating Account'],
            [
                'account_id' => Account::query()->where('number', '1010')->value('id'),
                'bank_name' => 'Demo First Bank',
                'account_number_mask' => '****4521',
                'opening_balance' => 25000,
                'is_active' => true,
            ]
        );

        BankAccount::query()->updateOrCreate(
            ['name' => 'Business Checking'],
            [
                'account_id' => Account::query()->where('number', '1020')->value('id'),
                'bank_name' => 'Demo First Bank',
                'account_number_mask' => '****8890',
                'opening_balance' => 8500,
                'is_active' => true,
            ]
        );
    }
}
