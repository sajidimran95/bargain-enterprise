<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PO {{ $order->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #555; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .num { text-align: right; }
        .totals { width: 240px; margin-left: auto; margin-top: 12px; }
        .totals td { border: none; padding: 2px 4px; }
        .vendor { margin-top: 10px; }
    </style>
</head>
<body>
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Purchase Order</div>
    <p>
        <strong>PO #:</strong> {{ $order->number }}<br>
        <strong>Date:</strong> {{ $order->order_date?->format('m/d/Y') }}<br>
        <strong>Expected:</strong> {{ $order->expected_date?->format('m/d/Y') ?: '—' }}<br>
        <strong>Status:</strong> {{ $order->status }}
    </p>

    <div class="vendor">
        <strong>Vendor</strong><br>
        {{ $order->vendor?->display_name }}<br>
        @foreach ($order->vendor?->billFromLines() ?? [] as $line)
            {{ $line }}<br>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Received</th>
                <th class="num">Rate</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->lines as $line)
                <tr>
                    <td>{{ $line->item?->sku }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="num">{{ number_format((float) $line->quantity, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->qty_received, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->rate, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="num">{{ number_format((float) $order->subtotal, 2) }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong>{{ number_format((float) $order->total, 2) }}</strong></td></tr>
    </table>

    @if ($order->memo)
        <p><strong>Memo:</strong> {{ $order->memo }}</p>
    @endif
</body>
</html>
