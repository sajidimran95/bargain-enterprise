<div class="be-page be-home-page">
    <?php if (isset($component)) { $__componentOriginal00d054dba68bdcc1c55fe1e4603ea575 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.erp.home-insights-tabs','data' => ['active' => 'insights']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('erp.home-insights-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active' => 'insights']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575)): ?>
<?php $attributes = $__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575; ?>
<?php unset($__attributesOriginal00d054dba68bdcc1c55fe1e4603ea575); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal00d054dba68bdcc1c55fe1e4603ea575)): ?>
<?php $component = $__componentOriginal00d054dba68bdcc1c55fe1e4603ea575; ?>
<?php unset($__componentOriginal00d054dba68bdcc1c55fe1e4603ea575); ?>
<?php endif; ?>

    <div class="be-insights-bar">
        <div class="be-insights-bar__tabs" role="tablist">
            <button type="button" role="tab" wire:click="setInsightsTab('company')" class="<?php echo e($insightsTab === 'company' ? 'is-active' : ''); ?>" aria-selected="<?php echo e($insightsTab === 'company' ? 'true' : 'false'); ?>">Company</button>
            <button type="button" role="tab" wire:click="setInsightsTab('payments')" class="<?php echo e($insightsTab === 'payments' ? 'is-active' : ''); ?>" aria-selected="<?php echo e($insightsTab === 'payments' ? 'true' : 'false'); ?>">Payments</button>
            <button type="button" role="tab" wire:click="setInsightsTab('customer')" class="<?php echo e($insightsTab === 'customer' ? 'is-active' : ''); ?>" aria-selected="<?php echo e($insightsTab === 'customer' ? 'true' : 'false'); ?>">Customer</button>
        </div>
        <div class="be-insights-bar__tools">
            <button type="button" class="be-link-btn text-[11px]" wire:click="toggleAddContent">Add Content &gt;</button>
            <button type="button" class="be-link-btn text-[11px]" wire:click="restoreDefaultWidgets">Restore Default</button>
            <span class="text-[11px] text-gray-500">Live data · <?php echo e($year); ?></span>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAddContent && $insightsTab === 'company'): ?>
        <div class="be-panel mx-3 mb-2">
            <div class="be-panel__header">
                <h2 class="be-panel__title text-[12px]">Add Content</h2>
            </div>
            <div class="be-panel__body flex flex-wrap gap-3 py-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $widgetLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center gap-1.5 text-[11px] text-gray-700">
                        <input type="checkbox" <?php if($widgets[$key] ?? false): echo 'checked'; endif; ?> wire:click="toggleWidget('<?php echo e($key); ?>')">
                        <?php echo e($label); ?>

                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($insightsTab === 'company'): ?>
        <div class="be-widget-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['income_expense'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Income and Expense Trend</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($m = 1; $m <= 12; $m++): ?>
                        <?php
                            $income = (float) ($monthlyIncome[$m] ?? 0);
                            $expense = (float) ($expenseByMonth[$m] ?? 0);
                            $max = max(1, (float) $monthlyIncome->max(), (float) $expenseByMonth->max());
                        ?>
                        <div class="mb-1 flex items-center gap-2 text-[11px]">
                            <span class="w-8 text-gray-500"><?php echo e(date('M', mktime(0, 0, 0, $m, 1))); ?></span>
                            <div class="flex h-3 flex-1 gap-0.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#5cb85c]" style="width: <?php echo e(min(50, $income > 0 ? max(2, ($income / $max) * 50) : 0)); ?>%"></div>
                                <div class="h-full bg-[#e67e22]" style="width: <?php echo e(min(50, $expense > 0 ? max(2, ($expense / $max) * 50) : 0)); ?>%"></div>
                            </div>
                            <span class="w-20 text-right tabular-nums text-[10px]"><?php echo e(number_format($income, 0)); ?></span>
                        </div>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="mt-2 flex gap-3 text-[10px] text-gray-500">
                        <span><span class="inline-block h-2 w-2 bg-[#5cb85c]"></span> Income</span>
                        <span><span class="inline-block h-2 w-2 bg-[#e67e22]"></span> Expenses</span>
                    </div>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['account_balances'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Account Balances</span>
                    <a href="<?php echo e(route('accounting.chart')); ?>" class="be-widget__meta be-link-btn">Go to Chart of Accounts</a>
                </div>
                <div class="be-widget__body">
                    <div class="be-kv"><span class="be-kv__label">Accounts Receivable</span><span class="num"><?php echo e(number_format($arBalance, 2)); ?></span></div>
                    <div class="be-kv"><span class="be-kv__label">Accounts Payable</span><span class="num"><?php echo e(number_format($apBalance, 2)); ?></span></div>
                    <div class="be-kv"><span class="be-kv__label">Inventory Value</span><span class="num"><?php echo e(number_format($inventoryValue, 2)); ?></span></div>
                    <div class="be-kv"><span class="be-kv__label">Open Invoices</span><span><?php echo e($openInvoiceCount); ?></span></div>
                    <div class="be-kv"><span class="be-kv__label">Open Vendor Bills</span><span><?php echo e($openBillCount); ?></span></div>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['expense_breakdown'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Expense Breakdown</span>
                    <span class="be-widget__meta">This year-to-date</span>
                </div>
                <div class="be-widget__body">
                    <?php $expenseTotal = (float) $expenseByMonth->sum(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expenseTotal > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($m = 1; $m <= 12; $m++): ?>
                            <?php $val = (float) ($expenseByMonth[$m] ?? 0); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($val > 0): ?>
                                <div class="mb-1 flex items-center gap-2 text-[11px]">
                                    <span class="w-8 text-gray-500"><?php echo e(date('M', mktime(0, 0, 0, $m, 1))); ?></span>
                                    <div class="h-3 flex-1 border border-gray-200 bg-white">
                                        <div class="h-full bg-[#e67e22]" style="width: <?php echo e(min(100, max(4, ($val / $expenseTotal) * 100))); ?>%"></div>
                                    </div>
                                    <span class="w-16 text-right tabular-nums"><?php echo e(number_format($val, 0)); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <div class="flex min-h-[120px] items-center justify-center">
                            <p class="text-[12px] text-gray-500">There is no data for this graph.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['customers_owe'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Customers Who Owe Money</span>
                    <a href="<?php echo e(route('payments.create')); ?>" class="be-widget__meta be-link-btn">Receive Payments</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-right">Amt Due</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customersWhoOwe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr
                                    @click="selectedLine = 'cust-<?php echo e($customer->id); ?>'"
                                    :class="selectedLine === 'cust-<?php echo e($customer->id); ?>' ? 'is-selected' : ''"
                                >
                                    <td><?php echo e($customer->display_name); ?></td>
                                    <td class="num text-red-700"><?php echo e(number_format((float) $customer->balance, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="2">No open balances.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['top_customers'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Top Customers by Sales</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $sales = (float) $row->sales; ?>
                        <div class="mb-1.5">
                            <div class="mb-0.5 flex justify-between text-[11px]">
                                <span class="truncate pr-2"><?php echo e($row->customer?->display_name); ?></span>
                                <span class="num shrink-0"><?php echo e(number_format($sales, 0)); ?></span>
                            </div>
                            <div class="h-2.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#4a90d9]" style="width: <?php echo e(min(100, $sales > 0 ? max(4, ($sales / max(1, (float) $topCustomers->max('sales'))) * 100) : 0)); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-[11px] text-gray-500">No sales yet.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['best_sellers'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Best-Selling Items</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bestSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $sales = (float) $row->sales; ?>
                        <div class="mb-1.5">
                            <div class="mb-0.5 flex justify-between text-[11px]">
                                <span class="truncate pr-2"><?php echo e($row->item?->sku); ?> — <?php echo e(\Illuminate\Support\Str::limit($row->item?->name, 28)); ?></span>
                                <span class="num shrink-0"><?php echo e(number_format($sales, 0)); ?></span>
                            </div>
                            <div class="h-2.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#5cb85c]" style="width: <?php echo e(min(100, $sales > 0 ? max(4, ($sales / max(1, (float) $bestSellers->max('sales'))) * 100) : 0)); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-[11px] text-gray-500">No item sales yet.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($widgets['pop'] ?? true): ?>
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Prev Year Income Comparison</span>
                    <span class="be-widget__meta">Weekly · this vs last year</span>
                </div>
                <div class="be-widget__body">
                    <?php
                        $popMax = max(1, (float) collect($popWeeks)->max('this_year'), (float) collect($popWeeks)->max('last_year'));
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $popWeeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-1 flex items-center gap-2 text-[11px]">
                            <span class="w-12 shrink-0 text-gray-500"><?php echo e($week['label']); ?></span>
                            <div class="flex h-3 flex-1 gap-0.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#5cb85c]" style="width: <?php echo e(min(50, $week['this_year'] > 0 ? max(2, ($week['this_year'] / $popMax) * 50) : 0)); ?>%"></div>
                                <div class="h-full bg-[#9b59b6]" style="width: <?php echo e(min(50, $week['last_year'] > 0 ? max(2, ($week['last_year'] / $popMax) * 50) : 0)); ?>%"></div>
                            </div>
                            <span class="w-24 text-right tabular-nums text-[10px]"><?php echo e(number_format($week['this_year'], 0)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="mt-2 flex gap-3 text-[10px] text-gray-500">
                        <span><span class="inline-block h-2 w-2 bg-[#5cb85c]"></span> This year</span>
                        <span><span class="inline-block h-2 w-2 bg-[#9b59b6]"></span> Last year</span>
                    </div>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php elseif($insightsTab === 'payments'): ?>
        <div class="be-widget-grid">
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Payment Summary</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    <div class="be-kv"><span class="be-kv__label">Payments Received YTD</span><span class="num"><?php echo e(number_format($paymentsReceivedYtd, 2)); ?></span></div>
                    <div class="be-kv"><span class="be-kv__label">Undeposited Funds</span><span class="num text-red-700"><?php echo e(number_format($undepositedTotal, 2)); ?></span></div>
                    <div class="be-kv"><span class="be-kv__label">Open AR</span><span class="num"><?php echo e(number_format($arBalance, 2)); ?></span></div>
                    <div class="mt-3 flex gap-2">
                        <a href="<?php echo e(route('payments.create')); ?>" class="be-btn be-btn--primary">Receive Payments</a>
                        <a href="<?php echo e(route('deposits.create')); ?>" class="be-btn">Make Deposits</a>
                    </div>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Undeposited Payments</span>
                    <a href="<?php echo e(route('deposits.create')); ?>" class="be-widget__meta be-link-btn">Make Deposits</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $undeposited; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr
                                    @click="selectedLine = 'udp-<?php echo e($payment->id); ?>'"
                                    :class="selectedLine === 'udp-<?php echo e($payment->id); ?>' ? 'is-selected' : ''"
                                >
                                    <td><?php echo e($payment->payment_date?->format('m/d/Y')); ?></td>
                                    <td><?php echo e($payment->customer?->display_name); ?></td>
                                    <td class="num"><?php echo e(number_format((float) $payment->amount, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3">No undeposited payments.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Recent Payments</span>
                    <a href="<?php echo e(route('payments.index')); ?>" class="be-widget__meta be-link-btn">Payment List</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Num</th>
                                <th>Customer</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr
                                    @click="selectedLine = 'pmt-<?php echo e($payment->id); ?>'"
                                    :class="selectedLine === 'pmt-<?php echo e($payment->id); ?>' ? 'is-selected' : ''"
                                >
                                    <td><?php echo e($payment->payment_date?->format('m/d/Y')); ?></td>
                                    <td><?php echo e($payment->payment_number); ?></td>
                                    <td><?php echo e($payment->customer?->display_name); ?></td>
                                    <td class="num"><?php echo e(number_format((float) $payment->amount, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4">No payments yet.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="be-widget-grid">
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Customers Who Owe Money</span>
                    <a href="<?php echo e(route('payments.create')); ?>" class="be-widget__meta be-link-btn">Receive Payments</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-right">Amt Due</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customersWhoOwe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr
                                    @click="selectedLine = 'owe-<?php echo e($customer->id); ?>'"
                                    :class="selectedLine === 'owe-<?php echo e($customer->id); ?>' ? 'is-selected' : ''"
                                >
                                    <td>
                                        <a href="<?php echo e(route('customers.index', ['selectedId' => $customer->id])); ?>" class="be-link-btn" @click.stop>
                                            <?php echo e($customer->display_name); ?>

                                        </a>
                                    </td>
                                    <td class="num text-red-700"><?php echo e(number_format((float) $customer->balance, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="2">No open balances.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Top Customers by Sales</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $topCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $sales = (float) $row->sales; ?>
                        <div class="mb-1.5">
                            <div class="mb-0.5 flex justify-between text-[11px]">
                                <span class="truncate pr-2"><?php echo e($row->customer?->display_name); ?></span>
                                <span class="num shrink-0"><?php echo e(number_format($sales, 0)); ?></span>
                            </div>
                            <div class="h-2.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#4a90d9]" style="width: <?php echo e(min(100, $sales > 0 ? max(4, ($sales / max(1, (float) $topCustomers->max('sales'))) * 100) : 0)); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-[11px] text-gray-500">No sales yet.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Open Invoices</span>
                    <a href="<?php echo e(route('invoices.index')); ?>" class="be-widget__meta be-link-btn">Invoice List</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Due</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $openInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr
                                    @click="selectedLine = 'inv-<?php echo e($invoice->id); ?>'"
                                    :class="selectedLine === 'inv-<?php echo e($invoice->id); ?>' ? 'is-selected' : ''"
                                >
                                    <td><?php echo e($invoice->customer?->display_name); ?></td>
                                    <td class="<?php echo e($invoice->due_date && $invoice->due_date->isPast() ? 'text-red-700' : ''); ?>">
                                        <?php echo e($invoice->due_date?->format('m/d/Y') ?: '—'); ?>

                                    </td>
                                    <td class="num"><?php echo e(number_format((float) $invoice->balance_due, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="3">No open invoices.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH F:\laragon\www\bargain-enterprise\resources\views/livewire/dashboard/company-snapshot.blade.php ENDPATH**/ ?>