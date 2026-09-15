<?php

namespace App\Actions\Accounting;

use App\Models\Account;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;

class UpsertAccountAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, ?int $id = null): Account
    {
        $validated = $this->validate($data, $id);

        if ($id) {
            $account = Account::query()->findOrFail($id);

            if ($account->is_system) {
                throw new RuntimeException('System accounts cannot be modified.');
            }

            $account->update($validated);

            return $account->refresh();
        }

        return Account::query()->create($validated + ['is_system' => false]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validate(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'number' => ['required', 'string', 'max:20', Rule::unique('accounts', 'number')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['asset', 'liability', 'equity', 'income', 'expense', 'cogs'])],
            'subtype' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }
}
