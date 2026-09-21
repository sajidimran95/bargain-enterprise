# Bargain Enterprise POS/ERP

Wholesale & tobacco distribution POS/ERP built with Laravel, Livewire, Alpine.js, and Tailwind CSS.

## Requirements

Official specs and UI screenshots live in [`requirements/`](requirements/) (junction to `requirment/`).

Project tracking document: [`REQUIREMENTS.md`](REQUIREMENTS.md).

## Stack

- Laravel 13 / PHP 8.4+
- MySQL 8+
- Livewire 3 + Volt (Breeze auth)
- Tailwind CSS
- Architecture: Livewire → Action → Service → Model → MySQL

## Local setup (Laragon)

1. Ensure MySQL is running and database `bargain_db` exists (or update `.env`).
2. `composer install`
3. `cp .env.example .env` and set DB credentials / `php artisan key:generate`
4. **Development reset + demo data:** `php artisan migrate:fresh --seed`
   - Never run `migrate:fresh` in production.
5. `npm install && npm run build` (or `npm run dev`)
6. Open the Laragon site URL (e.g. `http://bargain-enterprise.test`)

### Seeded logins

| Email | Role | Password |
|-------|------|----------|
| admin@gmail.com | Admin | password |
| owner@bargain.local | Owner | password |
| manager@bargain.local | Manager | password |
| sales@bargain.local | Sales / Counter | password |
| books@bargain.local | Bookkeeper | password |

### Demo dataset (after migrate:fresh --seed)

- ~45 customers (CT wholesale/retail accounts), contacts, notes
- ~15 vendors
- ~120 inventory items with price levels + tobacco/vape attributes
- Opening stock via `inventory_transactions` ledger
- ~80 invoices / ~50 payments / quotes / sales orders
- Purchase orders, goods receipts, vendor bills/payments
- Chart of accounts, balanced journals, deposits/checks
- Dashboard Snapshot uses live AR/AP/inventory/sales queries

## Phase status

**Phase 1–3 complete** — auth, shell, reusable ERP components, Customers, Vendors, Items, Lookups.

Next: **Phase 4** — Inventory ledger & adjustments.

## Tests

```bash
php artisan test
```
