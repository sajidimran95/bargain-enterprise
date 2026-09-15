<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Credit Memo {{ $creditMemo->credit_number }}</title>
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
    </style>
</head>
<body>
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Credit Memo</div>
    <p>
        <strong>Credit #:</strong> {{ $creditMemo->credit_number }}<br>
        <strong>Date:</strong> {{ $creditMemo->credit_date?->format('m/d/Y') }}<br>
        <strong>Customer:</strong> {{ $creditMemo->customer?->display_name }}<br>
        <strong>Remaining Credit:</strong> {{ number_format((float) $creditMemo->remaining_credit, 2) }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Rate</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($creditMemo->lines as $line)
                <tr>
                    <td>{{ $line->item?->sku }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="num">{{ number_format((float) $line->quantity, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->rate, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td><strong>Total</strong></td><td class="num"><strong>{{ number_format((float) $creditMemo->total, 2) }}</strong></td></tr>
    </table>
</body>
</html>
