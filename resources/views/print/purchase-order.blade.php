@extends('layouts.print')

@section('title', 'PO '.$order->number)
@section('toolbar', 'Purchase Order '.$order->number.' — Letter Print')

@section('content')
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Purchase Order</div>
    <p>
        <strong>PO #:</strong> {{ $order->number }}<br>
        <strong>Date:</strong> {{ $order->order_date?->format('m/d/Y') }}<br>
        <strong>Expected:</strong> {{ $order->expected_date?->format('m/d/Y') ?: '—' }}<br>
        <strong>Status:</strong> {{ $order->status }}
    </p>

    <div class="party">
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
            @forelse ($order->lines as $line)
                <tr>
                    <td>{{ $line->item?->sku }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="num">{{ number_format((float) $line->quantity, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->qty_received, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->rate, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No lines.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="num">{{ number_format((float) $order->subtotal, 2) }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong>{{ number_format((float) $order->total, 2) }}</strong></td></tr>
    </table>

    @if ($order->memo)
        <p><strong>Memo:</strong> {{ $order->memo }}</p>
    @endif
@endsection
