<div class="be-page be-home-page">
    <x-erp.home-insights-tabs active="home" />

    <div class="be-home">
        <div class="be-home__flows">
            {{-- VENDORS --}}
            <section class="be-flow-band be-flow-band--vendors" aria-labelledby="vendors-band">
                <h2 id="vendors-band" class="be-flow-band__title">Vendors</h2>
                <div class="be-flow-band__body">
                    <div class="be-flow-band__row">
                        <a class="be-flow-card" href="{{ route('purchase-orders.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--po" aria-hidden="true">@include('components.erp.icons.clipboard')</span>
                            <span class="be-flow-card__label">Purchase Orders</span>
                        </a>
                        <span class="be-flow-arrow" aria-hidden="true"></span>
                        <a class="be-flow-card" href="{{ route('goods-receipts.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--recv" aria-hidden="true">@include('components.erp.icons.truck')</span>
                            <span class="be-flow-card__label">Receive Inventory</span>
                        </a>
                        <span class="be-flow-arrow" aria-hidden="true"></span>
                        <a class="be-flow-card" href="{{ route('vendor-bills.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--bill" aria-hidden="true">@include('components.erp.icons.ledger')</span>
                            <span class="be-flow-card__label">Enter Bills Against Inventory</span>
                        </a>
                    </div>
                    <div class="be-flow-band__row be-flow-band__row--secondary">
                        <a class="be-flow-card" href="{{ route('vendor-bills.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--enter" aria-hidden="true">@include('components.erp.icons.receipt')</span>
                            <span class="be-flow-card__label">Enter Bills</span>
                        </a>
                        <span class="be-flow-arrow" aria-hidden="true"></span>
                        <a class="be-flow-card" href="{{ route('vendor-payments.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--pay" aria-hidden="true">@include('components.erp.icons.cash')</span>
                            <span class="be-flow-card__label">Pay Bills</span>
                        </a>
                        <a class="be-flow-card be-flow-card--muted" href="{{ route('vendors.index') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--vendor" aria-hidden="true">@include('components.erp.icons.users')</span>
                            <span class="be-flow-card__label">Vendor Center</span>
                        </a>
                    </div>
                </div>
            </section>

            {{-- CUSTOMERS --}}
            <section class="be-flow-band be-flow-band--customers" aria-labelledby="customers-band">
                <h2 id="customers-band" class="be-flow-band__title">Customers</h2>
                <div class="be-flow-band__body">
                    <div class="be-flow-band__row">
                        <a class="be-flow-card" href="{{ route('quotes.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--quote" aria-hidden="true">@include('components.erp.icons.file')</span>
                            <span class="be-flow-card__label">Quotes (Estimates)</span>
                        </a>
                        <span class="be-flow-arrow" aria-hidden="true"></span>
                        <a class="be-flow-card" href="{{ route('sales-orders.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--so" aria-hidden="true">@include('components.erp.icons.cart')</span>
                            <span class="be-flow-card__label">Sales Orders</span>
                        </a>
                        <span class="be-flow-arrow" aria-hidden="true"></span>
                        <a class="be-flow-card" href="{{ route('invoices.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--inv" aria-hidden="true">@include('components.erp.icons.ledger')</span>
                            <span class="be-flow-card__label">Create Invoices</span>
                        </a>
                        <span class="be-flow-arrow" aria-hidden="true"></span>
                        <a class="be-flow-card" href="{{ route('payments.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--rp" aria-hidden="true">@include('components.erp.icons.cash')</span>
                            <span class="be-flow-card__label">Receive Payments</span>
                        </a>
                    </div>
                    <div class="be-flow-band__row be-flow-band__row--secondary">
                        <a class="be-flow-card" href="{{ route('sales-receipts.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--sr" aria-hidden="true">@include('components.erp.icons.receipt')</span>
                            <span class="be-flow-card__label">Create Sales Receipts</span>
                        </a>
                        <a class="be-flow-card" href="{{ route('credit-memos.create') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--cm" aria-hidden="true">@include('components.erp.icons.credit')</span>
                            <span class="be-flow-card__label">Refunds &amp; Credits</span>
                        </a>
                        <a class="be-flow-card" href="{{ route('invoices.index') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--list" aria-hidden="true">@include('components.erp.icons.list')</span>
                            <span class="be-flow-card__label">Invoice List</span>
                        </a>
                        <a class="be-flow-card" href="{{ route('customers.index') }}">
                            <span class="be-flow-card__icon be-flow-card__icon--cust" aria-hidden="true">@include('components.erp.icons.users')</span>
                            <span class="be-flow-card__label">Customer Center</span>
                        </a>
                    </div>
                </div>
            </section>

            {{-- EMPLOYEES (out of scope v1 — shown for design parity) --}}
            <section class="be-flow-band be-flow-band--employees" aria-labelledby="employees-band">
                <h2 id="employees-band" class="be-flow-band__title">Employees</h2>
                <div class="be-flow-band__body">
                    <div class="be-flow-band__row">
                        <div class="be-flow-card be-flow-card--disabled" title="Out of scope for v1">
                            <span class="be-flow-card__icon be-flow-card__icon--time" aria-hidden="true">@include('components.erp.icons.clock')</span>
                            <span class="be-flow-card__label">Enter Time</span>
                        </div>
                        <div class="be-flow-card be-flow-card--disabled" title="Out of scope for v1">
                            <span class="be-flow-card__icon be-flow-card__icon--payroll" aria-hidden="true">@include('components.erp.icons.bank')</span>
                            <span class="be-flow-card__label">Payroll</span>
                        </div>
                        <p class="be-flow-band__note">Employees / Payroll is out of scope for v1 (see REQUIREMENTS Q3).</p>
                    </div>
                </div>
            </section>
        </div>

        <aside class="be-home__side">
            <section class="be-side-panel" aria-labelledby="company-panel">
                <h2 id="company-panel" class="be-side-panel__title">Company</h2>
                <div class="be-side-panel__grid">
                    <a href="{{ route('accounting.chart') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.chart')</span>
                        Chart of Accounts
                    </a>
                    <a href="{{ route('items.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.box')</span>
                        Items &amp; Services
                    </a>
                    <a href="{{ route('inventory.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.layers')</span>
                        Inventory Activities
                    </a>
                    <a href="{{ route('lookups.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.search')</span>
                        Lookups
                    </a>
                    <a href="{{ route('settings.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.gear')</span>
                        Company Settings
                    </a>
                    <a href="{{ route('dashboard.snapshots', request()->boolean('embed') ? ['embed' => 1] : []) }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.camera')</span>
                        Company Snapshot
                    </a>
                </div>
            </section>

            <section class="be-side-panel" aria-labelledby="banking-panel">
                <h2 id="banking-panel" class="be-side-panel__title">Banking</h2>
                <div class="be-side-panel__grid">
                    <a href="{{ route('deposits.create') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.cash')</span>
                        Record Deposits
                    </a>
                    <a href="{{ route('checks.create') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.checkbook')</span>
                        Write Checks
                    </a>
                    <a href="{{ route('checks.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.print')</span>
                        Print Checks
                    </a>
                    <a href="{{ route('reconciliation.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.check')</span>
                        Reconcile
                    </a>
                    <a href="{{ route('banking.index') }}">
                        <span class="be-side-panel__icon" aria-hidden="true">@include('components.erp.icons.bank')</span>
                        Check Register
                    </a>
                </div>
            </section>
        </aside>
    </div>
</div>
