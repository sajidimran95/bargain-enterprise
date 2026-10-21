<?php

namespace App\Support;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\PriceLevel;
use App\Models\Role;
use App\Models\TaxCode;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SalesReportFilterCatalog
{
    /**
     * QuickBooks "Choose Filter" list, in the same order as Nashville.
     *
     * @return array<string, array{label: string, type: string, help?: string, all?: string, options?: array<string, string>}>
     */
    public static function fields(): array
    {
        $yesNo = ['yes' => 'Yes', 'no' => 'No'];

        return [
            'account' => ['label' => 'Account', 'type' => 'multi', 'all' => 'All accounts', 'help' => 'Only sales with a payment deposited to the selected payment accounts.'],
            'aging' => ['label' => 'Aging', 'type' => 'number', 'help' => 'Days past due as of the report end date. Only unpaid invoices have an age.'],
            'amount' => ['label' => 'Amount', 'type' => 'number', 'help' => 'Line amount (quantity x sales price).'],
            'billing_status' => ['label' => 'Billing Status', 'type' => 'choice', 'all' => 'Billed (completed sales)', 'options' => ['unbilled' => 'Unbilled (suspended sales)', 'all' => 'Billed and unbilled'], 'help' => 'Draft and pending invoices stay out of the report unless chosen here.'],
            'class' => ['label' => 'Class', 'type' => 'builtin'],
            'cleared' => ['label' => 'Cleared', 'type' => 'choice', 'all' => 'All', 'options' => ['yes' => 'Cleared (payment received)', 'no' => 'Uncleared (no payment yet)'], 'help' => 'Whether any payment has been received against the invoice.'],
            'group' => ['label' => 'Customer Group', 'type' => 'builtin'],
            'customer_type' => ['label' => 'Customer Type', 'type' => 'multi', 'all' => 'All customer types', 'help' => 'Customer price level.'],
            'date' => ['label' => 'Date', 'type' => 'builtin'],
            'detail_level' => ['label' => 'Detail Level', 'type' => 'choice', 'all' => 'All', 'options' => ['summary' => 'Summary only', 'detail' => 'All except summary'], 'help' => 'Summary rows are the group headings and totals.'],
            'due_date' => ['label' => 'Due Date', 'type' => 'range', 'help' => 'Invoice due date.'],
            'entered_modified' => ['label' => 'Entered/Modified', 'type' => 'range', 'help' => 'Date the sale was last entered or modified.'],
            'estimate_active' => ['label' => 'Estimate Active', 'type' => 'choice', 'all' => 'All', 'options' => $yesNo, 'help' => 'Sales reports list invoices. Yes matches no sales.'],
            'fob' => ['label' => 'FOB', 'type' => 'text', 'help' => 'This company does not store FOB. A value here matches no sales.'],
            'inactive_items' => ['label' => 'Inactive Inventory Items', 'type' => 'choice', 'all' => 'All items', 'options' => ['active' => 'Active items only', 'inactive' => 'Inactive items only']],
            'is_adjustment' => ['label' => 'Is Adjustment', 'type' => 'choice', 'all' => 'All', 'options' => $yesNo, 'help' => 'Invoices are not adjustments. Yes matches no sales.'],
            'item' => ['label' => 'Item', 'type' => 'builtin'],
            'job_status' => ['label' => 'Job Status', 'type' => 'multi', 'all' => 'All', 'help' => 'This company does not store a customer life stage. A choice here matches no sales.'],
            'job_type' => ['label' => 'Job Type', 'type' => 'multi', 'all' => 'All', 'help' => 'Item type on the sale.'],
            'memo' => ['label' => 'Memo', 'type' => 'text', 'help' => 'Words that must be in the invoice note or line description.'],
            'name' => ['label' => 'Name', 'type' => 'builtin'],
            'name_account' => ['label' => 'Name Acc.Num/Note', 'type' => 'text', 'help' => 'Customer account number.'],
            'name_address' => ['label' => 'Name Address', 'type' => 'text', 'help' => 'Words that must be in the customer address.'],
            'name_city' => ['label' => 'Name City', 'type' => 'text'],
            'name_email' => ['label' => 'Name E-Mail', 'type' => 'text'],
            'name_fax' => ['label' => 'Name Fax Number', 'type' => 'text', 'help' => 'Customer fax number or tax ID.'],
            'name_phone' => ['label' => 'Name Phone Number', 'type' => 'text'],
            'state' => ['label' => 'Name State', 'type' => 'builtin'],
            'name_street1' => ['label' => 'Name Street1', 'type' => 'text'],
            'name_street2' => ['label' => 'Name Street2', 'type' => 'text'],
            'name_zip' => ['label' => 'Name Zip', 'type' => 'text'],
            'number' => ['label' => 'Number', 'type' => 'text', 'help' => 'Invoice number (Num).'],
            'online_status' => ['label' => 'Online Status', 'type' => 'multi', 'all' => 'All', 'help' => 'This company does not store where the sale came from. A choice here matches no sales.'],
            'other_1' => ['label' => 'Other 1', 'type' => 'text', 'help' => 'This company does not store sale custom field 1. A value here matches no sales.'],
            'other_2' => ['label' => 'Other 2', 'type' => 'text', 'help' => 'This company does not store sale custom field 2. A value here matches no sales.'],
            'po_number' => ['label' => 'P.O. Number', 'type' => 'text', 'help' => 'This company does not store a P.O. number on the invoice. A value here matches no sales.'],
            'paid_status' => ['label' => 'Paid Status', 'type' => 'multi', 'all' => 'Either', 'help' => 'Payment status of the sale. Open = Due or Partial.'],
            'paid_through' => ['label' => 'Paid Through', 'type' => 'range', 'help' => 'Date a payment was received.'],
            'pay_period_begin' => ['label' => 'Pay Period Begin Date', 'type' => 'range', 'help' => 'This company does not store attendance shifts. A date here matches no sales.'],
            'pay_period_end' => ['label' => 'Pay Period End Date', 'type' => 'range', 'help' => 'This company does not store attendance shifts. A date here matches no sales.'],
            'payment_method' => ['label' => 'Payment Method', 'type' => 'multi', 'all' => 'All payment methods'],
            'payroll_item' => ['label' => 'Payroll Item', 'type' => 'multi', 'all' => 'All payroll items', 'help' => 'This company does not store sales commission or attendance. A choice here matches no sales.'],
            'posting_status' => ['label' => 'Posting Status', 'type' => 'choice', 'all' => 'Either', 'options' => ['posting' => 'Posting', 'non_posting' => 'Non-Posting'], 'help' => 'Final sales post to the books; drafts and pending invoices do not.'],
            'delivery_method' => ['label' => 'Preferred Delivery Method', 'type' => 'choice', 'all' => 'All', 'options' => ['email' => 'E-mail', 'none' => 'None'], 'help' => 'E-mail when the customer has an e-mail address.'],
            'preferred_vendor' => ['label' => 'Preferred Vendor', 'type' => 'multi', 'all' => 'All vendors', 'help' => 'Preferred vendor on the item, or a vendor who billed or ordered that item.'],
            'printed_status' => ['label' => 'Printed Status', 'type' => 'choice', 'all' => 'Either', 'options' => ['printed' => 'Printed', 'not_printed' => 'Not printed'], 'help' => 'This company does not keep a print log. Printed matches no sales. Not printed includes every sale.'],
            'received' => ['label' => 'Received', 'type' => 'multi', 'all' => 'All', 'help' => 'This company does not store a shipping status. A choice here matches no sales.'],
            'rep' => ['label' => 'Rep', 'type' => 'multi', 'all' => 'All reps', 'help' => 'The user who made the sale.'],
            'sales_tax_code' => ['label' => 'Sales Tax Code', 'type' => 'multi', 'all' => 'All'],
            'ship_date' => ['label' => 'Ship Date', 'type' => 'range', 'help' => 'This company does not store a ship date. A date here matches no sales.'],
            'ship_via' => ['label' => 'Ship Via', 'type' => 'multi', 'all' => 'All', 'help' => 'This company does not store a shipping method. A choice here matches no sales.'],
            'template' => ['label' => 'Template', 'type' => 'multi', 'all' => 'All templates', 'help' => 'Invoice template stored on the sale.'],
            'terms' => ['label' => 'Terms', 'type' => 'multi', 'all' => 'All terms'],
            'transaction_type' => ['label' => 'TransactionType', 'type' => 'choice', 'all' => 'All', 'options' => ['invoice' => 'Invoice', 'sales_receipt' => 'Sales Receipt'], 'help' => 'This report lists invoices. Sales Receipt matches no invoice rows.'],
            'vendor' => ['label' => 'Vendor', 'type' => 'multi', 'all' => 'All vendors', 'help' => 'Items bought from the vendor on a bill or purchase order.'],
            'vendor_type' => ['label' => 'Vendor Type', 'type' => 'multi', 'all' => 'All vendor types', 'help' => 'Business when the vendor has a company name, otherwise Individual.'],
            'voided' => ['label' => 'Voided', 'type' => 'choice', 'all' => 'All', 'options' => $yesNo, 'help' => 'This company does not mark sales lines void. Yes matches no sales.'],
            'workers_comp' => ['label' => 'Workers Comp Code', 'type' => 'multi', 'all' => 'All codes', 'help' => "Seller's staff role from User Management."],
            'zero_qoh' => ['label' => 'Zero QOH', 'type' => 'choice', 'all' => 'All', 'options' => $yesNo, 'help' => 'Items with no quantity on hand.'],
            'zero_reorder' => ['label' => 'Zero Reorder Qty', 'type' => 'choice', 'all' => 'All', 'options' => $yesNo, 'help' => 'Items with no reorder quantity.'],
        ];
    }

    /**
     * @return array{
     *     class: list<string>,
     *     group: list<string>,
     *     item: list<string>,
     *     item_type: string,
     *     name: string,
     *     state: string,
     *     extra: array<string, mixed>
     * }
     */
    public static function blank(): array
    {
        $extra = [];
        foreach (self::fields() as $key => $field) {
            if ($field['type'] === 'builtin') {
                continue;
            }

            $extra[$key] = self::emptyExtra($field['type']);
        }

        return [
            'class' => [],
            'group' => [],
            'item' => [],
            'item_type' => '',
            'name' => '',
            'state' => '',
            'extra' => $extra,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{
     *     class: list<string>,
     *     group: list<string>,
     *     item: list<string>,
     *     item_type: string,
     *     name: string,
     *     state: string,
     *     extra: array<string, mixed>
     * }
     */
    public static function normalize(array $input): array
    {
        $filters = self::blank();
        $filters['class'] = self::idList($input['class'] ?? []);
        $filters['group'] = array_values(array_intersect(
            ['inside_tn', 'outside_tn', 'no_state'],
            array_map('strval', (array) ($input['group'] ?? [])),
        ));
        $filters['item'] = self::idList($input['item'] ?? []);
        $itemType = (string) ($input['item_type'] ?? '');
        $filters['item_type'] = in_array($itemType, ['inventory', 'service'], true) ? $itemType : '';
        $name = $input['name'] ?? '';
        $filters['name'] = is_numeric($name) && (int) $name > 0 ? (string) (int) $name : '';
        $filters['state'] = trim((string) ($input['state'] ?? ''));

        $raw = is_array($input['extra'] ?? null) ? $input['extra'] : [];
        foreach (self::fields() as $key => $field) {
            if ($field['type'] === 'builtin') {
                continue;
            }

            $filters['extra'][$key] = self::cleanExtra($field, $raw[$key] ?? null);
        }

        return $filters;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public static function clear(array $filters, string $key): array
    {
        $filters = self::normalize($filters);
        $blank = self::blank();

        if (array_key_exists($key, $blank) && $key !== 'extra') {
            $filters[$key] = $blank[$key];
            if ($key === 'item') {
                $filters['item_type'] = '';
            }

            return $filters;
        }

        if (array_key_exists($key, $blank['extra'])) {
            $filters['extra'][$key] = $blank['extra'][$key];
        }

        return $filters;
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array<string, mixed>  $options
     */
    public static function selectAll(array $filters, string $key, array $options): array
    {
        $filters = self::normalize($filters);

        if ($key === 'class') {
            $filters['class'] = array_map('strval', array_keys($options['classes'] ?? []));

            return $filters;
        }

        if ($key === 'group') {
            $filters['group'] = array_map('strval', array_keys($options['groups'] ?? []));

            return $filters;
        }

        $field = self::fields()[$key] ?? null;
        if (($field['type'] ?? '') === 'multi') {
            $filters['extra'][$key] = array_map('strval', array_keys($options['fields'][$key] ?? []));
        }

        return $filters;
    }

    /**
     * @param  list<string>  $selectedIds
     * @return array{
     *     classes: array<string, string>,
     *     groups: array<string, string>,
     *     items: array{selected: array<string, string>, matches: array<string, string>},
     *     customers: array<string, string>,
     *     fields: array<string, array<string, string>>
     * }
     */
    public static function options(string $itemLookup = '', array $selectedIds = []): array
    {
        $selectedIds = self::idList($selectedIds);

        return [
            'classes' => self::stringKeys(ItemCategory::query()->orderBy('name')->pluck('name', 'id')->all()),
            'groups' => [
                'inside_tn' => 'Inside TN',
                'outside_tn' => 'Outside TN',
                'no_state' => 'No state on file',
            ],
            'items' => self::itemChoices($itemLookup, $selectedIds),
            'customers' => self::customerChoices(),
            'fields' => [
                'account' => self::accountChoices(),
                'customer_type' => self::stringKeys(PriceLevel::query()->orderBy('name')->pluck('name', 'id')->all()),
                'job_status' => [],
                'job_type' => self::stringKeys(ItemType::query()->orderBy('label')->pluck('label', 'id')->all()),
                'online_status' => [],
                'paid_status' => self::paidStatusChoices(),
                'received' => [],
                'payment_method' => self::stringKeys(PaymentMethods::options()),
                'payroll_item' => [
                    'commission' => 'Sales Commission',
                    'hourly' => 'Hourly (attendance clock in)',
                ],
                'preferred_vendor' => self::vendorChoices(),
                'rep' => self::stringKeys(User::query()->orderBy('name')->pluck('name', 'id')->all()),
                'sales_tax_code' => ['__none__' => 'Non-taxable'] + self::stringKeys(TaxCode::query()->orderBy('name')->pluck('name', 'id')->all()),
                'ship_via' => [],
                'template' => self::templateChoices(),
                'terms' => self::termChoices(),
                'vendor' => self::vendorChoices(),
                'vendor_type' => [
                    'individual' => 'Individual',
                    'business' => 'Business',
                ],
                'workers_comp' => self::stringKeys(Role::query()->orderBy('name')->pluck('name', 'id')->all()),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array<string, mixed>  $options
     * @return array<string, array{0: string, 1: string, 2: string, 3: bool}>
     */
    public static function choices(array $filters, array $options, string $from, string $to): array
    {
        $filters = self::normalize($filters);
        $classNames = self::labelsFor($options['classes'] ?? [], $filters['class']);
        $groupNames = self::labelsFor($options['groups'] ?? [], $filters['group']);
        $itemNames = self::labelsFor($options['items']['selected'] ?? [], $filters['item']);
        $itemTypeLabel = [
            '' => 'All sales items',
            'inventory' => 'All inventory items',
            'service' => 'All services',
        ][$filters['item_type']] ?? 'All sales items';
        $range = trim($from) !== '' && trim($to) !== ''
            ? Carbon::parse($from)->format('m/d/Y').' - '.Carbon::parse($to)->format('m/d/Y')
            : '';

        $builtin = [
            'class' => ['Class', $classNames === [] ? 'All classes' : (count($classNames) === 1 ? $classNames[0] : 'Multiple classes...'), implode(', ', $classNames), $classNames !== []],
            'group' => ['Customer Group', $groupNames === [] ? 'All customer groups' : (count($groupNames) === 1 ? $groupNames[0] : 'Multiple groups...'), implode(', ', $groupNames), $groupNames !== []],
            'date' => ['Date', 'Custom', $range, true],
            'item' => ['Item', $itemNames === [] ? $itemTypeLabel : (count($itemNames) === 1 ? $itemNames[0] : 'Multiple items...'), implode(', ', $itemNames), true],
            'name' => ['Name', $filters['name'] !== '' ? (string) ($options['customers'][$filters['name']] ?? 'Customer') : 'All customers/jobs', '', true],
            'state' => ['Name State', $filters['state'] !== '' ? $filters['state'] : 'All', '', $filters['state'] !== ''],
        ];

        $choices = [];
        foreach (self::fields() as $key => $field) {
            if ($field['type'] === 'builtin') {
                $choices[$key] = $builtin[$key];

                continue;
            }

            $value = $filters['extra'][$key] ?? null;
            $summary = $field['all'] ?? 'All';
            $title = '';
            $active = self::extraActive($field, $value);
            if ($active) {
                [$summary, $title] = self::extraSummary($field, $value, $options['fields'][$key] ?? []);
            }

            $choices[$key] = [$field['label'], $summary, $title, $active];
        }

        return $choices;
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array<string, mixed>  $filters
     */
    public static function apply(Builder $query, array $filters, string $from, string $to, string $basis): void
    {
        $filters = self::normalize($filters);
        $extra = $filters['extra'];

        self::applyInvoiceWindow(
            $query,
            $from,
            $to,
            $basis,
            (string) $extra['billing_status'],
            (string) $extra['posting_status'],
        );
        self::applyBuiltin($query, $filters);
        self::applyExtra($query, $extra, $to);
    }

    /**
     * @param  list<list<string|float|int|null>>  $rows
     * @return list<list<string|float|int|null>>
     */
    public static function filterDetailLevel(array $rows, string $level): array
    {
        if (! in_array($level, ['summary', 'detail'], true)) {
            return $rows;
        }

        return array_values(array_filter(
            $rows,
            fn (array $row): bool => $level === 'summary' ? self::isSummaryRow($row) : ! self::isSummaryRow($row),
        ));
    }

    /**
     * @param  list<string|float|int|null>  $row
     */
    public static function isSummaryRow(array $row): bool
    {
        $filled = array_filter($row, fn (mixed $cell): bool => trim((string) $cell) !== '');
        $first = trim((string) ($row[0] ?? ''));

        return (count($filled) === 1 && $first !== '')
            || $first === 'TOTAL'
            || str_starts_with($first, 'Total ');
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function applyInvoiceWindow(Builder $query, string $from, string $to, string $basis, string $billing, string $posting): void
    {
        $query->whereHas('invoice', function (Builder $invoice) use ($from, $to, $basis, $billing, $posting): void {
            $invoice->whereDate('invoice_date', '>=', $from)
                ->whereDate('invoice_date', '<=', $to);

            if ($billing === 'unbilled' || $posting === 'non_posting') {
                $invoice->where(function (Builder $draft): void {
                    $draft->whereIn('status', ['draft', 'pending'])->orWhere('is_pending', true);
                });
            } elseif ($billing !== 'all') {
                $invoice->whereNotIn('status', ['draft', 'pending'])->where('is_pending', false);
            }

            if ($posting === 'posting') {
                $invoice->whereNotIn('status', ['draft', 'pending'])->where('is_pending', false);
            }

            if ($basis === 'cash') {
                $invoice->where(function (Builder $settled): void {
                    $settled->where('balance_due', '<=', 0)->orWhere('status', 'paid');
                });
            }
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array<string, mixed>  $filters
     */
    private static function applyBuiltin(Builder $query, array $filters): void
    {
        if ($filters['class'] !== []) {
            $query->whereHas('item', fn (Builder $item) => $item->whereIn('item_category_id', self::ints($filters['class'])));
        }

        if ($filters['group'] !== []) {
            $query->whereHas('invoice.customer', function (Builder $customer) use ($filters): void {
                $customer->where(function (Builder $groups) use ($filters): void {
                    $state = "UPPER(TRIM(COALESCE(bill_to_state, '')))";
                    foreach ($filters['group'] as $group) {
                        match ($group) {
                            'inside_tn' => $groups->orWhereRaw("{$state} IN ('TN', 'TENNESSEE')"),
                            'outside_tn' => $groups->orWhereRaw("{$state} NOT IN ('', 'TN', 'TENNESSEE')"),
                            'no_state' => $groups->orWhereRaw("{$state} = ''"),
                            default => null,
                        };
                    }
                });
            });
        }

        if ($filters['item_type'] !== '') {
            $type = $filters['item_type'];
            $query->whereHas('item', function (Builder $item) use ($type): void {
                $item->where(function (Builder $match) use ($type): void {
                    $match->whereRaw('LOWER(COALESCE(type, \'\')) = ?', [$type])
                        ->orWhereHas('itemType', function (Builder $types) use ($type): void {
                            $types->whereRaw('LOWER(name) = ?', [$type])
                                ->orWhereRaw('LOWER(label) = ?', [$type]);
                        });
                });
            });
        }

        if ($filters['item'] !== []) {
            $query->whereIn('item_id', self::ints($filters['item']));
        }

        if ($filters['name'] !== '') {
            $query->whereHas('invoice', fn (Builder $invoice) => $invoice->where('customer_id', (int) $filters['name']));
        }

        if ($filters['state'] !== '') {
            $query->whereHas('invoice.customer', fn (Builder $customer) => $customer->where('bill_to_state', 'like', '%'.$filters['state'].'%'));
        }
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array<string, mixed>  $extra
     */
    private static function applyExtra(Builder $query, array $extra, string $endDate): void
    {
        foreach ($extra as $key => $value) {
            if (! self::extraActive(self::fields()[$key] ?? ['type' => ''], $value)) {
                continue;
            }

            match ($key) {
                'account' => $query->whereHas('invoice.paymentAllocations.payment', fn (Builder $payment) => $payment->whereIn('deposit_to_account_id', self::ints($value))),
                'aging' => self::applyAging($query, $value, $endDate),
                'amount' => $query->where('amount', $value['op'], $value['value']),
                'cleared' => $value === 'yes'
                    ? $query->whereHas('invoice.paymentAllocations')
                    : $query->whereDoesntHave('invoice.paymentAllocations'),
                'customer_type' => $query->whereHas('invoice.customer', fn (Builder $customer) => $customer->whereIn('price_level_id', self::ints($value))),
                'due_date' => self::applyDateExpression($query, 'COALESCE(due_date, invoice_date)', $value),
                'entered_modified' => self::applyInvoiceDates($query, 'updated_at', $value),
                'estimate_active' => $value === 'yes' ? self::matchNone($query) : null,
                'inactive_items' => $query->whereHas('item', fn (Builder $item) => $item->where('is_active', $value === 'active')),
                'is_adjustment' => $value === 'yes' ? self::matchNone($query) : null,
                'job_type' => $query->whereHas('item', fn (Builder $item) => $item->whereIn('item_type_id', self::ints($value))),
                'memo' => self::applyMemo($query, (string) $value),
                'name_account' => self::customerLike($query, ['customer_number'], (string) $value),
                'name_address' => self::applyAddress($query, (string) $value),
                'name_city' => self::customerLike($query, ['bill_to_city', 'bill_to_street1'], (string) $value),
                'name_email' => self::customerLike($query, ['email'], (string) $value),
                'name_fax' => self::applyFax($query, (string) $value),
                'name_phone' => self::customerLike($query, ['phone', 'alt_phone'], (string) $value),
                'name_street1' => self::customerLike($query, ['bill_to_street1'], (string) $value),
                'name_street2' => self::customerLike($query, ['bill_to_street2'], (string) $value),
                'name_zip' => self::customerLike($query, ['bill_to_zip'], (string) $value),
                'number' => $query->whereHas('invoice', fn (Builder $invoice) => $invoice->where('invoice_number', 'like', '%'.$value.'%')),
                'paid_status' => $query->whereHas('invoice', fn (Builder $invoice) => $invoice->whereIn('status', $value)),
                'paid_through' => self::applyPaidThrough($query, $value),
                'payment_method' => $query->whereHas('invoice.paymentAllocations.payment', fn (Builder $payment) => $payment->whereIn('method', $value)),
                'posting_status', 'billing_status', 'detail_level' => null,
                'delivery_method' => self::applyDelivery($query, (string) $value),
                'preferred_vendor', 'vendor' => self::applyVendors($query, self::ints($value)),
                'printed_status' => $value === 'printed' ? self::matchNone($query) : null,
                'rep' => $query->whereHas('invoice', fn (Builder $invoice) => $invoice->whereIn('created_by', self::ints($value))),
                'sales_tax_code' => self::applySalesTax($query, $value),
                'template' => $query->whereHas('invoice', fn (Builder $invoice) => $invoice->whereIn('template', $value)),
                'terms' => self::applyTerms($query, $value),
                'transaction_type' => $value === 'sales_receipt' ? self::matchNone($query) : null,
                'vendor_type' => self::applyVendors($query, self::vendorIdsForType($value)),
                'voided' => $value === 'yes' ? self::matchNone($query) : null,
                'workers_comp' => $query->whereHas('invoice.createdBy.roles', fn (Builder $roles) => $roles->whereIn('roles.id', self::ints($value))),
                'zero_qoh' => self::applyZeroQuantity($query, 'on_hand', (string) $value),
                'zero_reorder' => self::applyZeroQuantity($query, 'reorder_min', (string) $value),
                'fob', 'job_status', 'online_status', 'other_1', 'other_2', 'po_number', 'pay_period_begin', 'pay_period_end', 'payroll_item', 'received', 'ship_date', 'ship_via' => self::matchNone($query),
                default => null,
            };
        }
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array{op: string, value: float|int|string}  $value
     */
    private static function applyAging(Builder $query, array $value, string $endDate): void
    {
        $op = $value['op'];
        $query->whereHas('invoice', function (Builder $invoice) use ($op, $value, $endDate): void {
            $invoice->where('balance_due', '>', 0)
                ->where('status', '!=', 'paid');

            $age = $invoice->getConnection()->getDriverName() === 'sqlite'
                ? 'CAST(julianday(?) - julianday(COALESCE(due_date, invoice_date)) AS INTEGER)'
                : 'DATEDIFF(?, COALESCE(due_date, invoice_date))';

            $invoice->whereRaw($age.' '.$op.' ?', [$endDate, $value['value']]);
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array{from: string, to: string}  $range
     */
    private static function applyDateExpression(Builder $query, string $expression, array $range): void
    {
        $query->whereHas('invoice', function (Builder $invoice) use ($expression, $range): void {
            if ($range['from'] !== '') {
                $invoice->whereRaw('DATE('.$expression.') >= ?', [$range['from']]);
            }
            if ($range['to'] !== '') {
                $invoice->whereRaw('DATE('.$expression.') <= ?', [$range['to']]);
            }
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array{from: string, to: string}  $range
     */
    private static function applyInvoiceDates(Builder $query, string $column, array $range): void
    {
        $query->whereHas('invoice', function (Builder $invoice) use ($column, $range): void {
            if ($range['from'] !== '') {
                $invoice->whereDate($column, '>=', $range['from']);
            }
            if ($range['to'] !== '') {
                $invoice->whereDate($column, '<=', $range['to']);
            }
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  array{from: string, to: string}  $range
     */
    private static function applyPaidThrough(Builder $query, array $range): void
    {
        $query->whereHas('invoice.paymentAllocations.payment', function (Builder $payment) use ($range): void {
            if ($range['from'] !== '') {
                $payment->whereDate('payment_date', '>=', $range['from']);
            }
            if ($range['to'] !== '') {
                $payment->whereDate('payment_date', '<=', $range['to']);
            }
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function applyMemo(Builder $query, string $text): void
    {
        $like = '%'.$text.'%';
        $query->where(function (Builder $lines) use ($like): void {
            $lines->where('description', 'like', $like)
                ->orWhereHas('invoice', function (Builder $invoice) use ($like): void {
                    $invoice->where('memo', 'like', $like)->orWhere('customer_message', 'like', $like);
                });
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  list<string>  $columns
     */
    private static function customerLike(Builder $query, array $columns, string $text): void
    {
        $like = '%'.$text.'%';
        $query->whereHas('invoice.customer', function (Builder $customer) use ($columns, $like): void {
            $customer->where(function (Builder $match) use ($columns, $like): void {
                foreach ($columns as $column) {
                    $match->orWhere($column, 'like', $like);
                }
            });
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function applyAddress(Builder $query, string $text): void
    {
        $query->whereHas('invoice.customer', function (Builder $customer) use ($text): void {
            $expression = $customer->getConnection()->getDriverName() === 'sqlite'
                ? "COALESCE(bill_to_street1, '') || ' ' || COALESCE(bill_to_street2, '') || ' ' || COALESCE(bill_to_city, '') || ' ' || COALESCE(bill_to_state, '') || ' ' || COALESCE(bill_to_zip, '')"
                : "CONCAT_WS(' ', bill_to_street1, bill_to_street2, bill_to_city, bill_to_state, bill_to_zip)";
            $customer->whereRaw($expression.' LIKE ?', ['%'.$text.'%']);
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function applyFax(Builder $query, string $text): void
    {
        $like = '%'.$text.'%';
        $query->whereHas('invoice.customer', function (Builder $customer) use ($like): void {
            $customer->where('fax', 'like', $like)
                ->orWhereHas('taxCode', function (Builder $tax) use ($like): void {
                    $tax->where('name', 'like', $like)->orWhere('code', 'like', $like);
                });
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function applyDelivery(Builder $query, string $value): void
    {
        $query->whereHas('invoice.customer', function (Builder $customer) use ($value): void {
            if ($value === 'email') {
                $customer->whereNotNull('email')->where('email', '!=', '');

                return;
            }

            $customer->where(function (Builder $blank): void {
                $blank->whereNull('email')->orWhere('email', '');
            });
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  list<int>  $vendorIds
     */
    private static function applyVendors(Builder $query, array $vendorIds): void
    {
        if ($vendorIds === []) {
            self::matchNone($query);

            return;
        }

        $lineTable = $query->getModel()->getTable();
        $query->where(function (Builder $lines) use ($vendorIds, $lineTable): void {
            $lines->whereHas('item', fn (Builder $item) => $item->whereIn('preferred_vendor_id', $vendorIds))
                ->orWhereExists(function ($sub) use ($vendorIds, $lineTable): void {
                    $sub->selectRaw('1')
                        ->from('vendor_bill_lines')
                        ->join('vendor_bills', 'vendor_bills.id', '=', 'vendor_bill_lines.vendor_bill_id')
                        ->whereColumn('vendor_bill_lines.item_id', $lineTable.'.item_id')
                        ->whereIn('vendor_bills.vendor_id', $vendorIds);
                })
                ->orWhereExists(function ($sub) use ($vendorIds, $lineTable): void {
                    $sub->selectRaw('1')
                        ->from('purchase_order_lines')
                        ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_lines.purchase_order_id')
                        ->whereColumn('purchase_order_lines.item_id', $lineTable.'.item_id')
                        ->whereIn('purchase_orders.vendor_id', $vendorIds);
                });
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  list<string>  $values
     */
    private static function applySalesTax(Builder $query, array $values): void
    {
        $ids = self::ints(array_values(array_diff($values, ['__none__'])));
        $includeNone = in_array('__none__', $values, true);

        $query->where(function (Builder $lines) use ($ids, $includeNone): void {
            if ($ids !== []) {
                $lines->whereHas('invoice', fn (Builder $invoice) => $invoice->whereIn('tax_code_id', $ids))
                    ->orWhereHas('item', fn (Builder $item) => $item->whereIn('tax_code_id', $ids));
            }

            if ($includeNone) {
                $lines->orWhere(function (Builder $none): void {
                    $none->whereHas('invoice', fn (Builder $invoice) => $invoice->whereNull('tax_code_id'))
                        ->where(function (Builder $item): void {
                            $item->whereDoesntHave('item')
                                ->orWhereHas('item', fn (Builder $blank) => $blank->whereNull('tax_code_id'));
                        });
                });
            }
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     * @param  list<string>  $values
     */
    private static function applyTerms(Builder $query, array $values): void
    {
        $terms = array_values(array_diff($values, ['__none__']));
        $includeNone = in_array('__none__', $values, true);

        $query->whereHas('invoice.customer', function (Builder $customer) use ($terms, $includeNone): void {
            $customer->where(function (Builder $match) use ($terms, $includeNone): void {
                if ($terms !== []) {
                    $match->whereIn('terms', $terms);
                }
                if ($includeNone) {
                    $match->orWhereNull('terms')->orWhere('terms', '');
                }
            });
        });
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function applyZeroQuantity(Builder $query, string $column, string $value): void
    {
        $query->whereHas('item', function (Builder $item) use ($column, $value): void {
            if ($value === 'yes') {
                $item->where(function (Builder $zero) use ($column): void {
                    $zero->where($column, '<=', 0)->orWhereNull($column);
                });

                return;
            }

            $item->where($column, '>', 0);
        });
    }

    /**
     * @param  list<string>  $types
     * @return list<int>
     */
    private static function vendorIdsForType(array $types): array
    {
        return Vendor::query()
            ->where(function (Builder $vendors) use ($types): void {
                if (in_array('business', $types, true)) {
                    $vendors->orWhere(fn (Builder $business) => $business->whereNotNull('company_name')->where('company_name', '!=', ''));
                }
                if (in_array('individual', $types, true)) {
                    $vendors->orWhere(fn (Builder $individual) => $individual->whereNull('company_name')->orWhere('company_name', ''));
                }
            })
            ->pluck('id')
            ->map(fn (mixed $id): int => (int) $id)
            ->all();
    }

    /**
     * @param  Builder<InvoiceLine>  $query
     */
    private static function matchNone(Builder $query): void
    {
        $query->whereRaw('0 = 1');
    }

    /**
     * @param  array{type?: string, options?: array<string, string>}  $field
     */
    private static function extraActive(array $field, mixed $value): bool
    {
        return match ($field['type'] ?? '') {
            'multi' => is_array($value) && $value !== [],
            'choice', 'text' => is_string($value) && $value !== '',
            'number' => is_array($value) && in_array($value['op'] ?? '', ['>=', '<=', '='], true) && is_numeric($value['value'] ?? null) && (string) $value['value'] !== '',
            'range' => is_array($value) && ((($value['from'] ?? '') !== '') || (($value['to'] ?? '') !== '')),
            default => false,
        };
    }

    /**
     * @param  array{type: string, options?: array<string, string>}  $field
     * @param  array<string, string>  $optionLabels
     * @return array{0: string, 1: string}
     */
    private static function extraSummary(array $field, mixed $value, array $optionLabels): array
    {
        return match ($field['type']) {
            'multi' => self::multiSummary($value, $optionLabels),
            'choice' => [(string) ($field['options'][$value] ?? $value), ''],
            'number' => [$value['op'].' '.rtrim(rtrim(number_format((float) $value['value'], 2, '.', ''), '0'), '.'), ''],
            'range' => [self::rangeSummary($value), ''],
            default => [(string) $value, ''],
        };
    }

    /**
     * @param  list<string>  $values
     * @param  array<string, string>  $optionLabels
     * @return array{0: string, 1: string}
     */
    private static function multiSummary(array $values, array $optionLabels): array
    {
        $names = [];
        foreach ($values as $value) {
            $names[] = (string) ($optionLabels[$value] ?? $value);
        }

        return [count($names) === 1 ? $names[0] : 'Multiple...', implode(', ', $names)];
    }

    /**
     * @param  array{from?: string, to?: string}  $value
     */
    private static function rangeSummary(array $value): string
    {
        $from = ($value['from'] ?? '') !== '' ? Carbon::parse($value['from'])->format('m/d/Y') : '';
        $to = ($value['to'] ?? '') !== '' ? Carbon::parse($value['to'])->format('m/d/Y') : '';

        if ($from !== '' && $to !== '') {
            return $from.' - '.$to;
        }

        return $from !== '' ? '>= '.$from : '<= '.$to;
    }

    /**
     * @param  array<string, string>  $options
     * @param  list<string>  $ids
     * @return list<string>
     */
    private static function labelsFor(array $options, array $ids): array
    {
        $labels = [];
        foreach ($ids as $id) {
            if (isset($options[$id])) {
                $labels[] = $options[$id];
            }
        }

        return $labels;
    }

    /**
     * @return array<string, string>
     */
    private static function customerChoices(): array
    {
        $customers = Customer::query()->orderBy('display_name')->limit(1000)->pluck('display_name', 'id')->all();

        return self::stringKeys($customers);
    }

    /**
     * @return array<string, string>
     */
    private static function accountChoices(): array
    {
        $accounts = [];
        foreach (Account::query()->orderBy('name')->get(['id', 'name', 'number']) as $account) {
            $number = trim((string) $account->number);
            $accounts[(string) $account->id] = $number === '' ? (string) $account->name : $account->name.' ('.$number.')';
        }

        return $accounts;
    }

    /**
     * @return array<string, string>
     */
    private static function vendorChoices(): array
    {
        return self::stringKeys(Vendor::query()->orderBy('display_name')->pluck('display_name', 'id')->all());
    }

    /**
     * @return array<string, string>
     */
    private static function paidStatusChoices(): array
    {
        $statuses = [
            'open' => 'Open',
            'paid' => 'Paid',
            'partial' => 'Partial',
            'pending' => 'Pending',
            'draft' => 'Draft',
        ];

        foreach (Invoice::query()->distinct()->pluck('status') as $status) {
            $status = (string) $status;
            if ($status === '') {
                continue;
            }

            $statuses[$status] ??= ucwords(str_replace('_', ' ', $status));
        }

        return $statuses;
    }

    /**
     * @return array<string, string>
     */
    private static function templateChoices(): array
    {
        $templates = [];
        foreach (Invoice::query()->whereNotNull('template')->where('template', '!=', '')->distinct()->orderBy('template')->pluck('template') as $template) {
            $template = (string) $template;
            $templates[$template] = $template;
        }

        return $templates;
    }

    /**
     * @return array<string, string>
     */
    private static function termChoices(): array
    {
        $terms = ['__none__' => 'Due on receipt'];
        foreach (Customer::query()->whereNotNull('terms')->where('terms', '!=', '')->distinct()->orderBy('terms')->pluck('terms') as $term) {
            $term = (string) $term;
            $terms[$term] = ucwords($term);
        }

        return $terms;
    }

    /**
     * @param  list<string>  $selectedIds
     * @return array{selected: array<string, string>, matches: array<string, string>}
     */
    private static function itemChoices(string $lookup, array $selectedIds): array
    {
        $ids = self::ints($selectedIds);
        $selected = $ids === []
            ? collect()
            : Item::query()->whereIn('id', $ids)->orderBy('name')->get(['id', 'name', 'sku']);

        $lookup = trim($lookup);
        $matches = $lookup === ''
            ? collect()
            : Item::query()
                ->where(function (Builder $items) use ($lookup): void {
                    $like = '%'.$lookup.'%';
                    $items->where('name', 'like', $like)
                        ->orWhere('sku', 'like', $like)
                        ->orWhere('barcode', 'like', $like);
                })
                ->when($ids !== [], fn (Builder $items) => $items->whereNotIn('id', $ids))
                ->orderBy('name')
                ->limit(30)
                ->get(['id', 'name', 'sku']);

        return [
            'selected' => self::itemLabels($selected),
            'matches' => self::itemLabels($matches),
        ];
    }

    /**
     * @param  Collection<int, Item>  $items
     * @return array<string, string>
     */
    private static function itemLabels($items): array
    {
        $labels = [];
        foreach ($items as $item) {
            $sku = trim((string) $item->sku);
            $labels[(string) $item->id] = $sku === '' ? (string) $item->name : $item->name.' ('.$sku.')';
        }

        return $labels;
    }

    /**
     * @return array<string, string>|list<string>|array{op: string, value: string}|array{from: string, to: string}
     */
    private static function emptyExtra(string $type): array|string
    {
        return match ($type) {
            'multi' => [],
            'number' => ['op' => '', 'value' => ''],
            'range' => ['from' => '', 'to' => ''],
            default => '',
        };
    }

    /**
     * @param  array{type: string, options?: array<string, string>}  $field
     */
    private static function cleanExtra(array $field, mixed $value): mixed
    {
        return match ($field['type']) {
            'multi' => array_values(array_filter(array_map(
                fn (mixed $item): string => is_scalar($item) ? trim((string) $item) : '',
                (array) $value,
            ), fn (string $item): bool => $item !== '')),
            'choice' => is_string($value) && isset($field['options'][$value]) ? $value : '',
            'text' => is_scalar($value) ? trim((string) $value) : '',
            'number' => self::cleanNumber($value),
            'range' => self::cleanRange($value),
            default => '',
        };
    }

    /**
     * @return array{op: string, value: string}
     */
    private static function cleanNumber(mixed $value): array
    {
        $op = is_array($value) ? (string) ($value['op'] ?? '') : '';
        $number = is_array($value) ? (string) ($value['value'] ?? '') : '';
        if (! in_array($op, ['>=', '<=', '='], true) || ! is_numeric($number)) {
            return ['op' => '', 'value' => ''];
        }

        return ['op' => $op, 'value' => (string) (float) $number];
    }

    /**
     * @return array{from: string, to: string}
     */
    private static function cleanRange(mixed $value): array
    {
        $from = is_array($value) ? (string) ($value['from'] ?? '') : '';
        $to = is_array($value) ? (string) ($value['to'] ?? '') : '';

        return [
            'from' => self::isDate($from) ? $from : '',
            'to' => self::isDate($to) ? $to : '',
        ];
    }

    private static function isDate(string $value): bool
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1;
    }

    /**
     * @return list<string>
     */
    private static function idList(mixed $values): array
    {
        return array_values(array_unique(array_filter(array_map(
            fn (mixed $id): string => is_numeric($id) && (int) $id > 0 ? (string) (int) $id : '',
            (array) $values,
        ))));
    }

    /**
     * @param  list<string>  $values
     * @return list<int>
     */
    private static function ints(array $values): array
    {
        return array_values(array_unique(array_filter(array_map('intval', $values))));
    }

    /**
     * @param  array<int|string, mixed>  $options
     * @return array<string, string>
     */
    private static function stringKeys(array $options): array
    {
        $keyed = [];
        foreach ($options as $key => $label) {
            $keyed[(string) $key] = (string) $label;
        }

        return $keyed;
    }
}
