<div class="be-page be-home-page">
    <x-erp.home-insights-tabs active="home" />

    <div class="be-home">
        <div class="be-home__main">
            {{-- VENDORS — same as QB screenshot --}}
            <section class="be-home-band be-home-band--vendors" aria-labelledby="vendors-band">
                <h2 id="vendors-band" class="be-home-band__title">Vendors</h2>

                <div class="be-home-vendors">
                    <div class="be-home-vendors__row1">
                        <x-erp.home-tile route="purchase-orders.create" icon="clipboard" tone="po" :badge="$counts['purchase_orders'] ?: null">Purchase Orders</x-erp.home-tile>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <x-erp.home-tile route="goods-receipts.create" icon="truck" tone="recv">Receive Inventory</x-erp.home-tile>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <x-erp.home-tile route="vendor-bills.create" icon="ledger" tone="bill">Enter Bills Against Inventory</x-erp.home-tile>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <x-erp.home-tile route="vendor-payments.create" icon="cash" tone="pay" :badge="$counts['vendor_bills'] ?: null">Pay Bills</x-erp.home-tile>
                    </div>

                    <div class="be-home-vendors__row2">
                        <x-erp.home-tile route="vendor-bills.create" icon="receipt" tone="enter">Enter Bills</x-erp.home-tile>
                        <span class="be-home-arrow be-home-arrow--long" aria-hidden="true"></span>
                    </div>

                    <div class="be-home-vendors__extras">
                        <x-erp.home-tile icon="bank" tone="loan" toast="Business loans — connect your bank under Banking.">New Business Loans</x-erp.home-tile>
                        <x-erp.home-tile route="settings.index" icon="gear" tone="tax">Manage Sales Tax</x-erp.home-tile>
                    </div>
                </div>
            </section>

            {{-- CUSTOMERS — Quotes + SO feed Create Invoices (QB style) --}}
            <section class="be-home-band be-home-band--customers" aria-labelledby="customers-band">
                <h2 id="customers-band" class="be-home-band__title">Customers</h2>

                <div class="be-home-customers">
                    <div class="be-home-customers__grid">
                        <div class="be-home-customers__quote">
                            <x-erp.home-tile route="quotes.create" icon="file" tone="quote">Quotes (Estimates)</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                        </div>

                        <div class="be-home-customers__so">
                            <x-erp.home-tile route="sales-orders.create" icon="cart" tone="so" :badge="$counts['sales_orders'] ?: null">Sales Orders</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                        </div>

                        <div class="be-home-customers__invoice">
                            <x-erp.home-tile route="invoices.create" icon="ledger" tone="invoice" :badge="$counts['invoices'] ?: null">Create Invoices</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                            <x-erp.home-tile route="payments.create" icon="cash" tone="payment">Receive Payments</x-erp.home-tile>
                            <span class="be-home-arrow be-home-arrow--out" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="be-home-customers__side">
                        <x-erp.home-tile icon="cash" tone="cc" toast="Credit card payments — use Receive Payments.">Accept Credit Cards</x-erp.home-tile>
                        <x-erp.home-tile route="sales-receipts.create" icon="receipt" tone="receipt">Create Sales Receipts</x-erp.home-tile>
                        <div class="be-home-customers__statements">
                            <x-erp.home-tile route="invoices.create" icon="list" tone="stmt">Statement Charges</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                            <x-erp.home-tile route="customers.statements" icon="file" tone="stmt">Statements</x-erp.home-tile>
                        </div>
                        <x-erp.home-tile route="credit-memos.create" icon="credit" tone="credit">Refunds &amp; Credits</x-erp.home-tile>
                    </div>
                </div>
            </section>

            {{-- EMPLOYEES --}}
            <section class="be-home-band be-home-band--employees" aria-labelledby="employees-band">
                <h2 id="employees-band" class="be-home-band__title">Employees</h2>
                <div class="be-home-employees">
                    <x-erp.home-tile icon="clock" tone="time" toast="Enter Time — time tracking opens from Employees when enabled.">Enter Time</x-erp.home-tile>
                    <x-erp.home-tile icon="users" tone="payroll" toast="Payroll — enable under Employees when ready.">Turn On Payroll</x-erp.home-tile>
                </div>
            </section>
        </div>

        <aside class="be-home__rail">
            <section class="be-home-band be-home-band--rail" aria-labelledby="company-panel">
                <h2 id="company-panel" class="be-home-band__title">Company</h2>
                <div class="be-home-rail__list">
                    <x-erp.home-tile route="accounting.chart" icon="chart" tone="coa">Chart of Accounts</x-erp.home-tile>
                    <x-erp.home-tile route="inventory.index" icon="layers" tone="inv">Inventory Activities</x-erp.home-tile>
                    <x-erp.home-tile route="items.index" icon="box" tone="items">Items &amp; Services</x-erp.home-tile>
                    <x-erp.home-tile route="checks.create" icon="checkbook" tone="check">Order Checks</x-erp.home-tile>
                    <x-erp.home-tile icon="camera" tone="calendar" toast="Calendar — open from My Shortcuts when scheduled.">Calendar</x-erp.home-tile>
                </div>
            </section>

            <section class="be-home-band be-home-band--rail" aria-labelledby="banking-panel">
                <h2 id="banking-panel" class="be-home-band__title">Banking</h2>
                <div class="be-home-rail__list">
                    <x-erp.home-tile route="deposits.create" icon="cash" tone="deposit">Record Deposits</x-erp.home-tile>
                    <x-erp.home-tile route="reconciliation.index" icon="check" tone="reconcile">Reconcile</x-erp.home-tile>
                    <x-erp.home-tile route="checks.create" icon="checkbook" tone="check">Write Checks</x-erp.home-tile>
                    <x-erp.home-tile route="banking.index" icon="bank" tone="register">Check Register</x-erp.home-tile>
                    <x-erp.home-tile route="checks.index" icon="print" tone="print">Print Checks</x-erp.home-tile>
                </div>
            </section>
        </aside>
    </div>
</div>
