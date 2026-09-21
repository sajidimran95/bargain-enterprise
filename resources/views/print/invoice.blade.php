@extends('layouts.print')

@section('title', 'Invoice '.$invoice->invoice_number)
@section('toolbar', 'Invoice '.$invoice->invoice_number.' — Letter Print')

@section('content')
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Invoice</div>
    <p>
        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
        <strong>Date:</strong> {{ $invoice->invoice_date?->format('m/d/Y') }}<br>
        <strong>Due:</strong> {{ $invoice->due_date?->format('m/d/Y') ?: '—' }}
    </p>

    <div class="party">
        <strong>Bill To</strong><br>
        {{ $invoice->customer?->display_name }}<br>
        @foreach ($invoice->customer?->billToLines() ?? [] as $line)
            {{ $line }}<br>
        @endforeach
    </div>

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
            @forelse ($invoice->lines as $line)
                <tr>
                    <td>{{ $line->item?->sku }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="num">{{ number_format((float) $line->quantity, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->rate, 2) }}</td>
                    <td class="num">{{ number_format((float) $line->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No lines.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td class="num">{{ number_format((float) $invoice->subtotal, 2) }}</td></tr>
        <tr><td>Tax</td><td class="num">{{ number_format((float) $invoice->tax_total, 2) }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong>{{ number_format((float) $invoice->total, 2) }}</strong></td></tr>
        <tr><td>Balance Due</td><td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td></tr>
    </table>

    @if ($invoice->memo)
        <p><strong>Memo:</strong> {{ $invoice->memo }}</p>
    @endif
@endsection
