# Bargain Enterprise — Wholesale & Tobacco Distribution POS/ERP

**Reference client:** Bargain Enterprise Inc.  
**Source system:** QuickBooks Premier — Manufacturing and Wholesale Edition 2019  
**Prepared from:** `requirment/` (note: folder is misspelled; treated as `/requirements`)  
**Last updated:** 2026-09-11  
**Status rule:** Only **DONE** or **NOT DONE**. No “mostly / partial / in progress.”

---

## Project status (strict checklist)

### DONE
- [x] UI / Design (desktop ERP shell, sidebar submenus, top menubar, `be-*` tokens)
- [x] Desktop-style multi-tab workspace shell (sidebar opens tabs; embed panels; session tab state)
- [x] Menubar matches QB order (File…Help) + Window Close All
- [x] QB import pipeline (CSV Raw→Normalize→Validate→Transform→Production)
- [x] Authentication (Breeze Livewire login/logout/profile/password)
- [x] Roles & permissions (Owner, Manager, Sales, Bookkeeper + Gate::before)
- [x] Global search (customers / vendors / items)
- [x] Seeded demo users + demo data (`migrate:fresh --seed`)
- [x] Reusable ERP UI components (`components/erp/*`, list toolbar New/Find/Print/Excel)
- [x] Customers (CRUD + contacts/notes/todos + Excel)
- [x] Vendors (CRUD + contacts/notes + Excel)
- [x] Lookups: Categories / Item Types / Units / Tax Codes / Price Levels
- [x] Items (CRUD + barcode/UPC + Excel)
- [x] Inventory ledger service + stock list + adjustments UI
- [x] Home Page workflow map (Vendors / Customers / Company / Banking links)
- [x] Company Snapshot / Insights live widgets (income, balances, who owes, top customers, best sellers)
- [x] Chart of Accounts CRUD + balanced manual journals
- [x] Quotes list + create
- [x] Sales Orders list + create
- [x] Invoices list + QB-style create + barcode scan + stock out + journals
- [x] Sales Receipts list + create + stock out
- [x] Payments create (allocate to open invoices)
- [x] Credit Memos create + stock in + remaining credit
- [x] Credit memo apply to invoice + give refund (cash/check GL)
- [x] Purchase Orders list + create
- [x] Goods Receipts create (posts inventory)
- [x] Vendor Bills list + create
- [x] Vendor Payments create (allocate to open bills)
- [x] Bank accounts list + create
- [x] Deposits: select undeposited payments → post Dr Bank / Cr Undeposited Funds (1050)
- [x] Checks: write check → post Dr Expense (6000) / Cr Bank
- [x] Bank reconciliation worksheet (clear deposits/checks; finish when difference = 0)
- [x] Report Center + 4 on-screen runners (Customer Directory, Inventory/Stock, Sales by Item, Customer Open Balance)
- [x] Report date presets + Print + Excel on runners
- [x] Print + Excel CSV on list screens
- [x] New opens create pages (not silent drafts)
- [x] Negative inventory policy setting (ALLOW / WARN / BLOCK)

### NOT DONE — screenshot design parity (required; match `requirment/` images)
- [x] Company Snapshot: PoP widget + Add Content / Restore Default widget config
- [x] Invoice: Class / Template + full Main ribbon (Delete, Copy, Memorize, Pending, Email, Attach, Batch…)
- [x] Credit memo: Class / Template / P.O. No. + refund/apply ribbon parity
- [x] Enter Bills: Bill/Credit toggle, Expenses tab, Select PO
- [ ] Keyboard shortcuts (Ctrl+N/S/P/F, F2–F4) end-to-end
- [ ] Price level auto-price on scan
- [ ] Statements / Statement Charges
- [x] Line-wise row select on lists/reports (QB green highlight + ▶ marker)
- [ ] Employees / Payroll (out of scope for v1 — Home stub only)

**Previously listed as polish — these are design-parity work against screenshots, not optional.**

### Already done (delivery)
- [x] PDF delivery
- [x] Email delivery
- [x] Audit log writers + Audit log UI

**Do not rebuild:** existing layout, auth, or design system. Extend with `be-*` styles.

---

## 1. Source of truth

| Priority | Source |
|----------|--------|
| 1 | Explicit written requirements (Developer Brief + this document) |
| 2 | Functional workflows in the brief |
| 3 | Design screenshots in `requirment/` |
| 4 | Existing project architecture |
| 5 | Laravel best practices |

### Requirements inventory (`requirment/`)

| File | Type | Covers |
|------|------|--------|
| Bargain Enterprise Developer Brief.docx | Spec | Full functional brief |
| Home Page.png | UI | Workflow flowchart dashboard |
| Business Analytics.png | UI | Company Snapshot widgets |
| Customer Center.png | UI | Customer list + detail |
| Create Invoice.png | UI | Invoice form empty |
| Customer info during invoice creation.png | UI | Invoice + customer context panel |
| Credit Memo.png | UI | Credit memo / refund form |
| Entering Item In Stock Table.png | UI | Edit Item form |
| Custom Fields.png | UI | Tobacco/vape custom fields modal |
| Stock Table.png | UI | Item list |
| Recieve-Update Inventory.png | UI | Enter Bills (Items tab) |
| Customer Sales History Report.png | UI | Customer Open Balance report |
| MSA Customer Report.png | UI | Customer directory report |
| MSA Inventory Report.png | UI | Inventory/stock + category/promo |
| MSA Sales Report.png | UI | Item-grouped sales report |

---

## 2. Module list

| Module | Brief § | Status |
|--------|---------|--------|
| Auth / Users / Roles / Permissions | NFR | **DONE** |
| Desktop ERP shell | Screenshots | **DONE** |
| Home workflow map | §10 | **DONE** |
| Company Snapshot / Insights | §3 | **DONE** |
| Customers | §6 | **DONE** |
| Vendors | Architect + §8 | **DONE** |
| Items / Lookups | §7 | **DONE** |
| Inventory ledger & adjustments | §7 | **DONE** |
| Quotes / Sales Orders | §10 | **DONE** |
| Invoices | §4 | **DONE** |
| Sales Receipts | §10 | **DONE** |
| Payments / Allocations | §4, §10 | **DONE** |
| Credit Memos create | §5 | **DONE** |
| Credit apply / refund | §5 | **DONE** |
| Purchase Orders | §10 | **DONE** |
| Goods Receipts | §10 | **DONE** |
| Vendor Bills | §8 | **DONE** |
| Vendor Payments | §10 | **DONE** |
| Chart of Accounts / Journals | Architect | **DONE** |
| Bank accounts create | §10 | **DONE** |
| Deposits / Checks GL posting | §10 | **DONE** |
| Bank reconciliation | §10 | **DONE** |
| Reports (CSV exports) | §9 | **DONE** |
| Reports (full runners) | §9 | **DONE** |
| Reports (PDF/Email) | §9 | **DONE** |
| Print / Excel CSV | NFR | **DONE** |
| PDF / Email | NFR | **DONE** |
| Audit log UI | NFR | **DONE** |
| QB import pipeline | §11 | **DONE** (CSV staging pipeline; IIF later) |
| REST API `/api/v1` | Architect | **NOT DONE** |
| Flutter clients | Architect | **NOT DONE** |
| Employees / Payroll | Q3 | **NOT DONE** (out of scope v1) |

---

## 3. Feature checklist (by area)

### Dashboard (§3)
- [x] Home Page workflow map
- [x] Home Page ↔ Insights tabs
- [x] Company Snapshot live widgets
- [x] Income Trend (monthly)
- [x] Period-over-Period Comparison (weekly)
- [x] Account Balances
- [x] Customers Who Owe Money + Receive Payment link
- [x] Top Customers by Sales
- [x] Best-Selling Items
- [x] Expense Breakdown empty state
- [x] User-configurable widgets

### Sales — Invoices (§4)
- [x] Header / lines / scan / totals / footer / inspector
- [x] CreateInvoiceAction (inventory + journals)
- [x] Class, Template
- [x] Delete, Copy, Memorize, Pending, Email, Attach, Batch

### Credit Memos (§5)
- [x] Create + stock return + remaining credit
- [x] Apply credit to invoice
- [x] Give refund (cash/check + GL)
- [x] Full QB form parity

### Customers (§6) / Vendors / Items (§7)
- [x] All listed CRUD + list/search/Excel features
- [x] Inventory ledger + adjustments + negative policy

### Purchasing (§8)
- [x] Bills, POs, Receive Inventory, Pay Bills
- [x] Bill/Credit toggle, Expenses tab, Select PO wizard

### Reports (§9)
- [x] Report Center + CSV exports
- [x] Customer Directory runner
- [x] Inventory / Stock runner
- [x] Sales by Item runner
- [x] Customer Open Balance runner
- [x] Date presets + Print + Excel + Refresh
- [x] PDF / Email from reports

### Banking / Accounting
- [x] CoA + journals
- [x] Bank accounts / deposit & check records
- [x] Deposit GL (Undeposited Funds → Bank)
- [x] Check GL (Expense → Bank)
- [x] Reconciliation worksheet

### Navigation / shell UX
- [x] Sidebar submenus, menubar routes, list toolbar, footer links

---

## 4. Important business rules

1. **Inventory ledger** — every stock change writes `inventory_transactions`.
2. **Negative inventory** — ALLOW / WARN / BLOCK (default **WARN**).
3. **Money** — `DECIMAL(15,2)`; quantities `DECIMAL(15,4)`.
4. **Double-entry** — journals must balance.
5. **Price levels** — field present; scan still uses item sales price until auto-price is DONE.
6. **Tax** — from customer tax code on invoices.
7. **Tobacco fields** — Category, Items Per Container, Promotion, Item Type.
8. **Do not invent** MSA filing rules.
9. **Migration** — Raw → Normalize → Validate → Transform → Production (NOT DONE).
10. **Authorization** — Policies/Gates server-side.
11. **Architecture** — Livewire → Action → Service → Model → MySQL.
12. **Barcode scan** — barcode OR sku OR MPN; duplicate scan increments qty.
13. **New button** — opens create form; never silent draft insert.

---

## 5–8. UI / DB / Workflow / Permissions

Unchanged intent from prior revisions. Seeded logins:

`owner@bargain.local` / `manager@bargain.local` / `sales@bargain.local` / `books@bargain.local` — password: `password`

---

## 9. Reporting requirements

| Report | Status |
|--------|--------|
| Customer Directory | **DONE** (on-screen + CSV) |
| Inventory / Stock | **DONE** (on-screen + CSV) |
| Open AR / AP CSV | **DONE** |
| Sales by Item | **DONE** (on-screen + CSV) |
| Customer Open Balance | **DONE** (on-screen + CSV) |
| PDF / Email from reports | **DONE** |

---

## 10. Conflicts: written vs screenshot

| Topic | Resolution |
|-------|------------|
| Negative inventory | Configurable; default WARN |
| Item compliance fields | Native fields (not QB Custom Fields modal typo) |
| Home vs Insights | Home = workflow map; Insights = Snapshot |
| Employees / Payroll | Out of scope v1 |
| Barcode scan | Implemented |

---

## 11. Open questions / assumptions

| # | Question | Assumption |
|---|----------|------------|
| Q1 | Legal company name? | Bargain Enterprise Inc. |
| Q2 | Negative inventory default? | WARN |
| Q3 | Employees/Payroll? | No for v1 |
| Q4 | Sales tax? | Configurable tax_codes |
| Q5 | Multi-company? | Single company v1 |
| Q6 | Classes / Jobs? | Fields planned; Class on invoice NOT DONE |
| Q7 | Word export? | Defer |
| Q8 | Online payments gateway? | Eligibility only |

---

## 12. Counts (verified 2026-09-11)

| | Count |
|--|------:|
| **DONE** modules/features (strict) | **55** |
| **NOT DONE** remaining items | **7** |

### Already implemented (summary)
Phases 0–12 complete through Audit Log. Banking GL, reconciliation, credit apply/refund, report runners, PDF/Email, audit, invoice/credit ribbon parity, snapshot PoP/widgets, and Enter Bills design parity are live.

### Remaining build order (recommended)
1. Screenshot design parity leftovers (shortcuts / price-level scan / Statements)  
2. REST API  
3. Flutter  
4. Production deploy  
5. IIF importer (when client export format confirmed) 

---

## 13. Implementation checklist (phased)

| Phase | Scope | Status |
|-------|-------|--------|
| 0 | Requirements + REQUIREMENTS.md | **DONE** |
| 1 | Auth, roles, desktop layout | **DONE** |
| 2 | Reusable UI components | **DONE** |
| 3 | Customers, Vendors, Items, Lookups | **DONE** |
| 4 | Inventory ledger & adjustments | **DONE** |
| 5 | Quotes, SOs, Invoices, Sales Receipts | **DONE** |
| 6 | Payments + Credit Memo create | **DONE** |
| 6b | Credit apply / refund | **DONE** |
| 7 | POs, Goods Receipts, Vendor Bills/Payments | **DONE** |
| 8a | CoA + Journals + Bank accounts | **DONE** |
| 8b | Deposit/Check GL + Reconciliation | **DONE** |
| 9 | Full report runners | **DONE** |
| 10 | Snapshot extras (PoP, widget config) | **DONE** |
| 11 | PDF / Email | **DONE** |
| 12 | Audit UI | **DONE** |
| 13 | Data migration | **DONE** (CSV pipeline; IIF deferred) |
| 14 | Production deployment | **NOT DONE** |
| 15 | REST API | **NOT DONE** |
| 16 | Flutter | **NOT DONE** |

---

## 14. Key deliverables (current)

- Laravel 13 + Livewire 3 + Tailwind + MySQL
- Roles: Owner, Manager, Sales, Bookkeeper
- Desktop ERP shell with submenus + menubar
- Create Invoice: QB-style + barcode scan + stock + journals
- Sales Receipt + Credit Memo stock posting
- Source folder: `requirment/` (typo)

---

## 15. Design tokens

```css
--be-sidebar: #1a2b44;
--be-sidebar-text: #e8eef5;
--be-topbar: #f0f0f0;
--be-content: #ffffff;
--be-border: #c8c8c8;
--be-accent: #2f6fed;
--be-row-alt: #eef5fb;
--be-selected: #3d8b40;
--be-danger: #c62828;
--be-font: "Segoe UI", Tahoma, sans-serif;
```
