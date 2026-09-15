# Workspace Navigation Architecture

**App:** Bargain Enterprise POS/ERP  
**Last updated:** 2026-09-11  
**Status:** Foundation implemented (shell, catalog, manager, embed panels, sidebar/menubar wiring, customer detail tabs). Continue migrating remaining list→detail openers to `beWorkspace.open` / `<x-erp.workspace-link>`.

---

## 1. Current navigation architecture

| Layer | Behavior today |
|-------|----------------|
| `resources/views/layouts/app.blade.php` | Persistent chrome: title bar, menubar, utility/search, sidebar, main `$slot` |
| Sidebar / menubar | Plain `<a href="{{ route(...) }}">` — full document navigation |
| Livewire modules | Full-page components with `#[Layout('layouts.app')]` |
| Dashboard | Blade `x-app-layout` wrapping `dashboard.home-placeholder` |
| “Open Windows” | Cosmetic label only (`$windowTitle`) — not a real window manager |
| Shortcuts | Alpine `erpShell()` — Ctrl+F/S/N/P, F2–F4 (F-keys use `window.location`) |

**Problem:** Each click recreates the shell and replaces the main document. That feels like a website, not a desktop ERP.

---

## 2. New workspace architecture

### Principles

1. **One persistent shell** — header + sidebar stay mounted.
2. **Workspace tabs** — modules/documents open as tabs inside `<main>`.
3. **No duplicate tabs** — same logical id activates the existing tab.
4. **Lazy panels** — iframe (or panel) created on first open; kept in DOM while tab exists (Livewire state preserved).
5. **Routes kept** — Laravel routes remain for auth, deep links, PDF, Livewire, tests (`?embed=1`).
6. **No visual redesign** — reuse `be-*` tokens; add only a tab strip + panel host.

### Components

| Piece | Role |
|-------|------|
| `App\Support\Workspace\WorkspaceCatalog` | Route → tab type, default title, closable, permission |
| `App\Support\Workspace\WorkspaceManager` | Session-backed `openTab` / `activateTab` / `closeTab` / `closeOthers` / `closeAll` / `findTab` / `isTabOpen` / `refreshTab` |
| `App\Livewire\Workspace\Shell` | UI + server authority for tab mutations; hosts tab bar + panels |
| `layouts.embed` | Chrome-free module layout for panel iframes (`?embed=1`) |
| `App\Http\Middleware\RedirectToWorkspace` | Non-embed HTML GETs → `dashboard?open=…` so the browser stays in the shell |
| Alpine (`erpShell` / workspace helpers) | Tab chrome UX, context menu, postMessage bridge, Ctrl+W |
| `resources/js/workspace.js` | Parent ↔ iframe messaging (`be-workspace-open`, dirty, title, refresh) |

### Shell layout (unchanged regions)

```
┌ Title / Menubar / Search / User ──────────────────────────┐
├ Sidebar ─┬ Tab bar [Home] [Customers ×] [Invoice ×] ──────┤
│          ├ Active panel (iframe embed of module route) ───┤
│ Open     │                                                │
│ Windows  │                                                │
└──────────┴────────────────────────────────────────────────┘
```

Sidebar and menubar call `WorkspaceManager` (via Livewire) instead of full navigation when the shell is present.

---

## 3. Tab types

Catalog keys follow route names (stable ids). Types (logical):

| Type | Examples |
|------|----------|
| `dashboard` | Home (non-closable) |
| `snapshots` | Company Snapshot |
| `customers` / `customer-form` | Customer Center, New/Edit Customer |
| `vendors` / `vendor-form` | Vendor Center, forms |
| `inventory` / `items` / `item-form` / `lookups` | Stock, Item List, Item form |
| `quotes` / `sales-orders` / `invoices` / `invoice-form` | Sales lists & forms |
| `payments` / `credit-memos` / `sales-receipts` | AR docs |
| `purchase-orders` / `goods-receipts` / `vendor-bills` / `vendor-payments` | Purchasing |
| `banking` / `deposits` / `checks` / `reconciliation` | Banking |
| `accounting` | CoA, Journals |
| `reports` | Report Center + runners |
| `settings` / `audit` / `profile` | Admin |

**Dynamic titles:** list tabs use catalog defaults; document tabs use record labels (e.g. `Customer: {display_name}`, `Invoice #{number}`) via `WorkspaceCatalog::titleFor()` and iframe `postMessage` title updates.

---

## 4. State management

### Server (source of truth)

`WorkspaceManager` stores in session (`workspace.tabs`, `workspace.active_id`):

```php
[
  'id' => 'customers.index',          // unique
  'type' => 'customers',
  'title' => 'Customers',
  'route' => 'customers.index',
  'params' => [],
  'url' => 'https://…/customers?embed=1',
  'closable' => true,
  'dirty' => false,
  'refresh_token' => 0,               // bump to reload iframe
]
```

Livewire `Shell` reads/writes through the manager on every action. Refresh restores tabs from session.

### Client

- Tab strip reflects Livewire state.
- Panels: one iframe per open tab; show/hide by `active_id` (iframe stays loaded → Livewire/Alpine state inside preserved).
- `postMessage` from embed:
  - `be-workspace-open` — open another tab (detail/document)
  - `be-workspace-dirty` — mark unsaved
  - `be-workspace-title` — update tab title
  - `be-workspace-close` — request close

---

## 5. Routing strategy

| Concern | Approach |
|---------|----------|
| Keep routes | All existing named routes remain |
| Embed | `?embed=1` (and/or `X-Workspace-Embed: 1`) → `layouts.embed` |
| Shell entry | `GET /dashboard` → `Workspace\Shell` (route name unchanged for auth tests) |
| Deep link | `/invoices/create` → redirect to `/dashboard?open=invoices.create` |
| PDF / downloads | Excluded from workspace redirect |
| Livewire requests | Excluded (`livewire/*`) |
| Tests | Hit `?embed=1` for module HTML, or follow workspace redirect |

Authorization stays in each module `mount()` / policies. Opening a tab the user cannot access still fails inside the panel (403); catalog may also gate `openTab` when a permission is declared.

---

## 6. Browser history strategy

1. Activating a tab updates the parent URL with `?open={tabId}` (or path mirror) via `history.replaceState` so refresh reopens the same active tab.
2. Opening a tab may `pushState` so Back activates the previous tab when possible (Shell listens to `popstate`).
3. Iframe internal Livewire navigation does **not** push parent history (document stays in-tab).
4. Explicit “open as tab” from lists pushes a new parent history entry.

---

## 7. Unsaved changes strategy

1. Forms dispatch `be-workspace-dirty` when dirty (wire:dirty / explicit flag).
2. Closing a dirty tab → confirm: “Unsaved changes will be lost. Continue?” → Cancel / Discard.
3. `closeOthers` / `closeAll` / Ctrl+W use the same check per dirty tab.
4. Only tabs that reported dirty are protected.

Phase 1 wires dirty on major create forms (invoice, customer, vendor bill) via a small shared JS/Livewire hook; others can opt in later.

---

## 8. Keyboard shortcuts

| Shortcut | Action |
|----------|--------|
| Ctrl/Cmd+W | Close active closable tab (with dirty confirm) |
| Ctrl/Cmd+F | Focus global search (existing) |
| Ctrl/Cmd+S | `be-save` into active iframe |
| Ctrl/Cmd+N | `be-new` into active iframe |
| Ctrl/Cmd+P | Print (active iframe preferred) |
| Esc | Close modal (`be-close-modal`) |
| F2 / F3 / F4 | Open Customers / Items / Payments **as tabs** (not `window.location`) |

---

## 9. Migration order

1. Shell + catalog + manager + embed layout + redirect middleware  
2. Sidebar / menubar / Open Windows → workspace  
3. Home + Snapshots + Customers (+ form as tab)  
4. Vendors, Items, Inventory  
5. Sales docs, Purchasing, Banking, Accounting, Reports, Settings  
6. List “open record” actions → `be-workspace-open` (detail tabs)  
7. Dirty guards + context menu + history polish  

**Do not** rewrite module business logic. Modules keep Actions / Services / Policies.

---

## 10. Modal vs tab

| Use a **tab** | Use a **modal** |
|---------------|-----------------|
| Centers, lists, document forms, reports, settings | Confirm delete, tiny lookups, toasts, small dialogs |

---

## 11. Acceptance mapping

| Criterion | Mechanism |
|-----------|-----------|
| Sidebar opens tabs | `openRoute` / dispatch |
| Activate / no duplicates | `WorkspaceManager::openTab` find-by-id |
| Close / others / all | Manager methods + dirty confirm |
| Refresh tab | bump `refresh_token` → iframe reload |
| Detail as tabs | postMessage / `openRoute` with params |
| Dynamic titles | catalog + postMessage |
| State preserved | iframe keep-alive |
| Permissions | server `mount` + optional catalog gate |
| No fake tabs | catalog tied to real routes |
| Design unchanged | tab strip only (`be-workspace-*`) |
