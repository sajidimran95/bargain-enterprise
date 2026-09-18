<?php

use App\Http\Controllers\Purchasing\VendorBillPdfController;
use App\Http\Controllers\Sales\CreditMemoPdfController;
use App\Http\Controllers\Sales\InvoicePdfController;
use App\Http\Middleware\RedirectToWorkspace;
use App\Livewire\Accounting\ChartOfAccounts;
use App\Livewire\Accounting\JournalIndex;
use App\Livewire\Audit\AuditLogIndex;
use App\Livewire\Banking\BankAccountForm;
use App\Livewire\Banking\BankingIndex;
use App\Livewire\Banking\CheckForm;
use App\Livewire\Banking\CheckIndex;
use App\Livewire\Banking\DepositForm;
use App\Livewire\Banking\DepositIndex;
use App\Livewire\Banking\ReconciliationWorksheet;
use App\Livewire\Customers\CustomerCenter;
use App\Livewire\Customers\CustomerForm;
use App\Livewire\Dashboard\CompanySnapshot;
use App\Livewire\Employees\EnterTime;
use App\Livewire\Employees\PayrollCenter;
use App\Livewire\Import\QbImportWizard;
use App\Livewire\Inventory\InventoryAdjustmentIndex;
use App\Livewire\Inventory\InventoryIndex;
use App\Livewire\Items\ItemForm;
use App\Livewire\Items\ItemList;
use App\Livewire\Lookups\LookupManager;
use App\Livewire\Purchasing\GoodsReceiptForm;
use App\Livewire\Purchasing\GoodsReceiptIndex;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Livewire\Purchasing\PurchaseOrderIndex;
use App\Livewire\Purchasing\VendorBillForm;
use App\Livewire\Purchasing\VendorBillIndex;
use App\Livewire\Purchasing\VendorPaymentForm;
use App\Livewire\Purchasing\VendorPaymentIndex;
use App\Livewire\Reports\ApAgingReport;
use App\Livewire\Reports\ArAgingReport;
use App\Livewire\Reports\BalanceSheetReport;
use App\Livewire\Reports\CashFlowReport;
use App\Livewire\Reports\CustomerDirectoryReport;
use App\Livewire\Reports\CustomerOpenBalanceReport;
use App\Livewire\Reports\GeneralLedgerReport;
use App\Livewire\Reports\InventoryStockReport;
use App\Livewire\Reports\InventoryValuationReport;
use App\Livewire\Reports\ProfitLossReport;
use App\Livewire\Reports\PurchaseByItemReport;
use App\Livewire\Reports\ReportCenter;
use App\Livewire\Reports\SalesByItemReport;
use App\Livewire\Reports\TrialBalanceReport;
use App\Livewire\Reports\VendorBalanceReport;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Sales\CreditMemoApply;
use App\Livewire\Sales\CreditMemoForm;
use App\Livewire\Sales\CreditMemoIndex;
use App\Livewire\Sales\CustomerStatement;
use App\Livewire\Sales\InvoiceBatch;
use App\Livewire\Sales\InvoiceForm;
use App\Livewire\Sales\InvoiceIndex;
use App\Livewire\Sales\PaymentForm;
use App\Livewire\Sales\PaymentIndex;
use App\Livewire\Sales\QuoteForm;
use App\Livewire\Sales\QuoteIndex;
use App\Livewire\Sales\SalesOrderForm;
use App\Livewire\Sales\SalesOrderFulfillmentWorksheet;
use App\Livewire\Sales\SalesOrderIndex;
use App\Livewire\Sales\SalesReceiptForm;
use App\Livewire\Sales\SalesReceiptIndex;
use App\Livewire\Settings\CompanySettings;
use App\Livewire\Settings\MyCompany;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserIndex;
use App\Livewire\Vendors\VendorCenter;
use App\Livewire\Vendors\VendorForm;
use App\Livewire\Workspace\Shell;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Shell::class)->name('dashboard');

    Route::middleware([RedirectToWorkspace::class])->group(function () {
        Route::view('dashboard/home', 'dashboard.home')->name('dashboard.home');
        Route::get('dashboard/snapshots', CompanySnapshot::class)->name('dashboard.snapshots');
        Route::view('profile', 'profile')->name('profile');

        Route::get('customers', CustomerCenter::class)->name('customers.index');
        Route::get('customers/create', CustomerForm::class)->name('customers.create');
        Route::get('customers/statements', CustomerStatement::class)->name('customers.statements');
        Route::get('customers/{customer}/edit', CustomerForm::class)->name('customers.edit');
        Route::get('customers/{customer}', CustomerCenter::class)->name('customers.show');

        Route::get('vendors', VendorCenter::class)->name('vendors.index');
        Route::get('vendors/create', VendorForm::class)->name('vendors.create');
        Route::get('vendors/{vendor}/edit', VendorForm::class)->name('vendors.edit');
        Route::get('vendors/{vendor}', VendorCenter::class)->name('vendors.show');

        Route::get('items', ItemList::class)->name('items.index');
        Route::get('items/create', ItemForm::class)->name('items.create');
        Route::get('items/{item}/edit', ItemForm::class)->name('items.edit');
        Route::get('lookups', LookupManager::class)->name('lookups.index');

        Route::get('inventory', InventoryIndex::class)->name('inventory.index');
        Route::get('inventory/adjustments', InventoryAdjustmentIndex::class)->name('inventory.adjustments');

        Route::get('quotes', QuoteIndex::class)->name('quotes.index');
        Route::get('quotes/create', QuoteForm::class)->name('quotes.create');

        Route::get('sales-orders', SalesOrderIndex::class)->name('sales-orders.index');
        Route::get('sales-orders/fulfillment', SalesOrderFulfillmentWorksheet::class)->name('sales-orders.fulfillment');
        Route::get('sales-orders/create', SalesOrderForm::class)->name('sales-orders.create');
        Route::get('sales-orders/{salesOrder}/edit', SalesOrderForm::class)->name('sales-orders.edit');

        Route::get('invoices', InvoiceIndex::class)->name('invoices.index');
        Route::get('invoices/batch', InvoiceBatch::class)->name('invoices.batch');
        Route::get('invoices/create', InvoiceForm::class)->name('invoices.create');
        Route::get('invoices/{invoice}/edit', InvoiceForm::class)->name('invoices.edit');

        Route::get('sales-receipts', SalesReceiptIndex::class)->name('sales-receipts.index');
        Route::get('sales-receipts/create', SalesReceiptForm::class)->name('sales-receipts.create');

        Route::get('payments', PaymentIndex::class)->name('payments.index');
        Route::get('payments/create', PaymentForm::class)->name('payments.create');

        Route::get('credit-memos', CreditMemoIndex::class)->name('credit-memos.index');
        Route::get('credit-memos/create', CreditMemoForm::class)->name('credit-memos.create');
        Route::get('credit-memos/{creditMemo}/apply', CreditMemoApply::class)->name('credit-memos.apply');

        Route::get('purchase-orders', PurchaseOrderIndex::class)->name('purchase-orders.index');
        Route::get('purchase-orders/create', PurchaseOrderForm::class)->name('purchase-orders.create');
        Route::get('purchase-orders/{purchaseOrder}/edit', PurchaseOrderForm::class)->name('purchase-orders.edit');

        Route::get('goods-receipts', GoodsReceiptIndex::class)->name('goods-receipts.index');
        Route::get('goods-receipts/create', GoodsReceiptForm::class)->name('goods-receipts.create');
        Route::get('goods-receipts/{goodsReceipt}/edit', GoodsReceiptForm::class)->name('goods-receipts.edit');

        Route::get('vendor-bills', VendorBillIndex::class)->name('vendor-bills.index');
        Route::get('vendor-bills/create', VendorBillForm::class)->name('vendor-bills.create');
        Route::get('vendor-bills/{vendorBill}/edit', VendorBillForm::class)->name('vendor-bills.edit');
        Route::get('vendor-returns/create', VendorBillForm::class)->name('vendor-returns.create');
        Route::get('vendor-returns/{vendorBill}/edit', VendorBillForm::class)->name('vendor-returns.edit');

        Route::get('vendor-payments', VendorPaymentIndex::class)->name('vendor-payments.index');
        Route::get('vendor-payments/create', VendorPaymentForm::class)->name('vendor-payments.create');

        Route::get('banking', BankingIndex::class)->name('banking.index');
        Route::get('banking/create', BankAccountForm::class)->name('banking.create');
        Route::get('deposits', DepositIndex::class)->name('deposits.index');
        Route::get('deposits/create', DepositForm::class)->name('deposits.create');
        Route::get('checks', CheckIndex::class)->name('checks.index');
        Route::get('checks/create', CheckForm::class)->name('checks.create');
        Route::get('reconciliation', ReconciliationWorksheet::class)->name('reconciliation.index');

        Route::get('accounting/chart-of-accounts', ChartOfAccounts::class)->name('accounting.chart');
        Route::get('accounting/journal-entries', JournalIndex::class)->name('accounting.journals');

        Route::get('reports', ReportCenter::class)->name('reports.index');
        Route::get('reports/customers', CustomerDirectoryReport::class)->name('reports.customers');
        Route::get('reports/inventory', InventoryStockReport::class)->name('reports.inventory');
        Route::get('reports/sales-by-item', SalesByItemReport::class)->name('reports.sales-by-item');
        Route::get('reports/customer-open-balance', CustomerOpenBalanceReport::class)->name('reports.open-balance');
        Route::get('reports/ar-aging', ArAgingReport::class)->name('reports.ar-aging');
        Route::get('reports/ap-aging', ApAgingReport::class)->name('reports.ap-aging');
        Route::get('reports/inventory-valuation', InventoryValuationReport::class)->name('reports.inventory-valuation');
        Route::get('reports/profit-loss', ProfitLossReport::class)->name('reports.profit-loss');
        Route::get('reports/balance-sheet', BalanceSheetReport::class)->name('reports.balance-sheet');
        Route::get('reports/trial-balance', TrialBalanceReport::class)->name('reports.trial-balance');
        Route::get('reports/general-ledger', GeneralLedgerReport::class)->name('reports.general-ledger');
        Route::get('reports/cash-flow', CashFlowReport::class)->name('reports.cash-flow');
        Route::get('reports/vendor-balance', VendorBalanceReport::class)->name('reports.vendor-balance');
        Route::get('reports/purchases-by-item', PurchaseByItemReport::class)->name('reports.purchase-by-item');
        Route::get('company', MyCompany::class)->name('company.info');
        Route::get('settings', CompanySettings::class)->name('settings.index');
        Route::get('users', UserIndex::class)->name('users.index');
        Route::get('users/create', UserForm::class)->name('users.create');
        Route::get('users/{user}/edit', UserForm::class)->name('users.edit');
        Route::get('roles', RoleIndex::class)->name('roles.index');
        Route::get('roles/create', RoleForm::class)->name('roles.create');
        Route::get('roles/{role}/edit', RoleForm::class)->name('roles.edit');
        Route::get('employees/time', EnterTime::class)->name('employees.time');
        Route::get('employees/payroll', PayrollCenter::class)->name('employees.payroll');
        Route::get('audit-log', AuditLogIndex::class)->name('audit.index');
        Route::get('import/quickbooks', QbImportWizard::class)->name('import.index');
    });

    // PDF routes stay outside workspace redirect.
    Route::get('invoices/{invoice}/pdf', InvoicePdfController::class)->name('invoices.pdf');
    Route::get('credit-memos/{creditMemo}/pdf', CreditMemoPdfController::class)->name('credit-memos.pdf');
    Route::get('vendor-bills/{vendorBill}/pdf', VendorBillPdfController::class)->name('vendor-bills.pdf');
});

require __DIR__.'/auth.php';
