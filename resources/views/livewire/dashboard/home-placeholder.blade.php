<div class="be-page be-home-page">
    <x-erp.home-insights-tabs active="home" />

    <div class="be-home">
        <div class="be-home__main">
            <section class="be-home-band be-home-band--vendors" aria-labelledby="vendors-band">
                <h2 id="vendors-band" class="be-home-band__title">Vendors</h2>

                <div class="be-home-vendors">
                    <div class="be-home-vendors__row1">
                        <x-erp.home-tile route="purchase-orders.create" title="Purchase Orders" icon="clipboard" tone="po" :badge="$counts['purchase_orders'] ?: null">Purchase Orders</x-erp.home-tile>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <x-erp.home-tile route="goods-receipts.create" title="Receive Inventory" icon="truck" tone="recv">Receive Inventory</x-erp.home-tile>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <x-erp.home-tile route="vendor-bills.create" title="Enter Bills Against Inventory" icon="ledger" tone="bill">Enter Bills Against Inventory</x-erp.home-tile>
                        <span class="be-home-arrow" aria-hidden="true"></span>
                        <x-erp.home-tile route="vendor-payments.create" title="Pay Bills" icon="cash" tone="pay" :badge="$counts['vendor_bills'] ?: null">Pay Bills</x-erp.home-tile>
                    </div>

                    <div class="be-home-vendors__row2">
                        <x-erp.home-tile route="vendor-bills.create" title="Enter Bills" icon="receipt" tone="enter">Enter Bills</x-erp.home-tile>
                        <span class="be-home-arrow be-home-arrow--long" aria-hidden="true"></span>
                    </div>

                    <div class="be-home-vendors__extras">
                        <x-erp.home-tile route="banking.create" title="New Business Loans" icon="bank" tone="loan">New Business Loans</x-erp.home-tile>
                        <x-erp.home-tile route="settings.index" title="Manage Sales Tax" icon="gear" tone="tax">Manage Sales Tax</x-erp.home-tile>
                    </div>
                </div>
            </section>

            <section class="be-home-band be-home-band--customers" aria-labelledby="customers-band">
                <h2 id="customers-band" class="be-home-band__title">Customers</h2>

                <div class="be-home-customers">
                    <div class="be-home-customers__grid">
                        <div class="be-home-customers__quote">
                            <x-erp.home-tile route="quotes.create" title="Quotes (Estimates)" icon="file" tone="quote">Quotes (Estimates)</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                        </div>

                        <div class="be-home-customers__so">
                            <x-erp.home-tile route="sales-orders.create" title="Sales Orders" icon="cart" tone="so" :badge="$counts['sales_orders'] ?: null">Sales Orders</x-erp.home-tile>
                            <x-erp.home-tile route="sales-orders.fulfillment" title="SO Fulfillment / Create Invoices" icon="ledger" tone="so">SO → Invoice</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                        </div>

                        <div class="be-home-customers__invoice">
                            <x-erp.home-tile route="invoices.create" title="Create Invoices" icon="ledger" tone="invoice" :badge="$counts['invoices'] ?: null">Create Invoices</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                            <x-erp.home-tile route="payments.create" title="Receive Payments" icon="cash" tone="payment">Receive Payments</x-erp.home-tile>
                            <span class="be-home-arrow be-home-arrow--out" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="be-home-customers__side">
                        <x-erp.home-tile route="payments.create" title="Accept Credit Cards" icon="cash" tone="cc">Accept Credit Cards</x-erp.home-tile>
                        <x-erp.home-tile route="sales-receipts.create" title="Create Sales Receipts" icon="receipt" tone="receipt">Create Sales Receipts</x-erp.home-tile>
                        <div class="be-home-customers__statements">
                            <x-erp.home-tile route="invoices.create" title="Statement Charges" icon="list" tone="stmt">Statement Charges</x-erp.home-tile>
                            <span class="be-home-arrow" aria-hidden="true"></span>
                            <x-erp.home-tile route="customers.statements" title="Statements" icon="file" tone="stmt">Statements</x-erp.home-tile>
                        </div>
                        <x-erp.home-tile route="credit-memos.create" title="Refunds & Credits" icon="credit" tone="credit">Refunds &amp; Credits</x-erp.home-tile>
                    </div>
                </div>
            </section>

            <section class="be-home-band be-home-band--employees" aria-labelledby="employees-band">
                <h2 id="employees-band" class="be-home-band__title">Employees</h2>
                <div class="be-home-employees">
                    <x-erp.home-tile route="employees.time" title="Enter Time" icon="clock" tone="time">Enter Time</x-erp.home-tile>
                    <x-erp.home-tile route="employees.payroll" title="Turn On Payroll" icon="users" tone="payroll">Turn On Payroll</x-erp.home-tile>
                </div>
            </section>
        </div>

        <aside class="be-home__rail">
            <section class="be-home-band be-home-band--rail" aria-labelledby="company-panel">
                <h2 id="company-panel" class="be-home-band__title">Company</h2>
                <div class="be-home-rail__list">
                    <x-erp.home-tile route="accounting.chart" title="Chart of Accounts" icon="chart" tone="coa">Chart of Accounts</x-erp.home-tile>
                    <x-erp.home-tile route="inventory.index" title="Inventory Activities" icon="layers" tone="inv">Inventory Activities</x-erp.home-tile>
                    <x-erp.home-tile route="items.index" title="Items & Services" icon="box" tone="items">Items &amp; Services</x-erp.home-tile>
                    <x-erp.home-tile route="checks.create" title="Order Checks" icon="checkbook" tone="check">Order Checks</x-erp.home-tile>
                    <x-erp.home-tile route="dashboard.snapshots" title="Calendar" icon="camera" tone="calendar">Calendar</x-erp.home-tile>
                </div>
            </section>

            <section class="be-home-band be-home-band--rail" aria-labelledby="banking-panel">
                <h2 id="banking-panel" class="be-home-band__title">Banking</h2>
                <div class="be-home-rail__list">
                    <x-erp.home-tile route="deposits.create" title="Record Deposits" icon="cash" tone="deposit">Record Deposits</x-erp.home-tile>
                    <x-erp.home-tile route="reconciliation.index" title="Reconcile" icon="check" tone="reconcile">Reconcile</x-erp.home-tile>
                    <x-erp.home-tile route="checks.create" title="Write Checks" icon="checkbook" tone="check">Write Checks</x-erp.home-tile>
                    <x-erp.home-tile route="banking.index" title="Check Register" icon="bank" tone="register">Check Register</x-erp.home-tile>
                    <x-erp.home-tile route="checks.index" title="Print Checks" icon="print" tone="print">Print Checks</x-erp.home-tile>
                </div>
            </section>
        </aside>
    </div>
</div>
