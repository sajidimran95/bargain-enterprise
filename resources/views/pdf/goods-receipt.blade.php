<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $receipt->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #555; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .num { text-align: right; }
        .vendor { margin-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Goods Receipt / Receive Inventory</div>
    <p>
        <strong>Receipt #:</strong> {{ $receipt->number }}<br>
        <strong>Date:</strong> {{ $receipt->receipt_date?->format('m/d/Y') }}<br>
        <strong>PO:</strong> {{ $receipt->purchaseOrder?->number ?: '—' }}
    </p>

    <div class="vendor">
        <strong>Vendor</strong><br>
        {{ $receipt->vendor?->display_name }}<br>
        @foreach ($receipt->vendor?->billFromLines() ?? [] as $line)
            {{ $line }}<br>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Unit Cost</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receipt->lines as $line)
                @php
                    $amount = (float) bcmul((string) $line->quantity, (string) $line->unit_cost, 4);
                @endphp
                <tr>
                    <td>{{ $line->item?->sku }}</td>
                    <td>{{ $line->item?->purchase_description ?: $line->item?->name }}</td>
                    <td class="num">{{ number_format((float) $line->quantity, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->unit_cost, 2) }}</td>
                    <td class="num">{{ number_format($amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($receipt->memo)
        <p><strong>Memo:</strong> {{ $receipt->memo }}</p>
    @endif
</body>
</html>
