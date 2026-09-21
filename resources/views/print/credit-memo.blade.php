@extends('layouts.print')

@section('title', 'Credit Memo '.$creditMemo->credit_number)
@section('toolbar', 'Credit Memo '.$creditMemo->credit_number.' — Letter Print')

@section('content')
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Credit Memo</div>
    <p>
        <strong>Credit #:</strong> {{ $creditMemo->credit_number }}<br>
        <strong>Date:</strong> {{ $creditMemo->credit_date?->format('m/d/Y') }}<br>
        <strong>Remaining Credit:</strong> {{ number_format((float) $creditMemo->remaining_credit, 2) }}
    </p>

    <div class="party">
        <strong>Customer</strong><br>
        {{ $creditMemo->customer?->display_name }}<br>
        @foreach ($creditMemo->customer?->billToLines() ?? [] as $line)
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
            @forelse ($creditMemo->lines as $line)
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
        <tr><td>Subtotal</td><td class="num">{{ number_format((float) $creditMemo->subtotal, 2) }}</td></tr>
        <tr><td>Tax</td><td class="num">{{ number_format((float) ($creditMemo->tax_total ?? 0), 2) }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong>{{ number_format((float) $creditMemo->total, 2) }}</strong></td></tr>
    </table>

    @if ($creditMemo->memo)
        <p><strong>Memo:</strong> {{ $creditMemo->memo }}</p>
    @endif
@endsection
