@extends('layouts.print')

@php
    $isCredit = $bill->isCreditDocument();
    $isRtv = str_starts_with((string) $bill->bill_number, 'RTV-');
    $docLabel = $isRtv ? 'Return to Vendor (RTV)' : ($isCredit ? 'Vendor Credit' : 'Vendor Bill');
@endphp

@section('title', $docLabel.' '.$bill->bill_number)
@section('toolbar', $docLabel.' '.$bill->bill_number.' — Letter Print')

@section('content')
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">{{ $docLabel }}</div>
    <p>
        <strong>{{ $isCredit ? 'Credit #' : 'Bill #' }}:</strong> {{ $bill->bill_number }}<br>
        <strong>Ref No.:</strong> {{ $bill->ref_no ?: '—' }}<br>
        <strong>Date:</strong> {{ $bill->bill_date?->format('m/d/Y') }}<br>
        <strong>Due:</strong> {{ $bill->due_date?->format('m/d/Y') ?: '—' }}
    </p>

    <div class="party">
        <strong>Vendor</strong><br>
        {{ $bill->vendor?->display_name }}<br>
        @foreach ($bill->vendor?->billFromLines() ?? [] as $line)
            {{ $line }}<br>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="num">Qty</th>
                <th class="num">Cost</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bill->lines as $line)
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
        <tr><td>Subtotal</td><td class="num">{{ number_format((float) $bill->subtotal, 2) }}</td></tr>
        <tr><td><strong>Total</strong></td><td class="num"><strong>{{ number_format((float) $bill->total, 2) }}</strong></td></tr>
        <tr><td>Balance Due</td><td class="num">{{ number_format((float) $bill->balance_due, 2) }}</td></tr>
    </table>

    @if ($bill->memo)
        <p><strong>Memo:</strong> {{ $bill->memo }}</p>
    @endif
@endsection
