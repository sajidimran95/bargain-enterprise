@extends('layouts.print')

@section('title', 'Receipt '.$receipt->number)
@section('toolbar', 'Receive Inventory '.$receipt->number.' — Letter Print')

@section('content')
    <h1>{{ config('bargain.company_name', config('app.name')) }}</h1>
    <div class="muted">Goods Receipt / Receive Inventory</div>
    <p>
        <strong>Receipt #:</strong> {{ $receipt->number }}<br>
        <strong>Date:</strong> {{ $receipt->receipt_date?->format('m/d/Y') }}<br>
        <strong>PO:</strong> {{ $receipt->purchaseOrder?->number ?: '—' }}
    </p>

    <div class="party">
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
            @forelse ($receipt->lines as $line)
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
            @empty
                <tr><td colspan="5">No lines.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if ($receipt->memo)
        <p><strong>Memo:</strong> {{ $receipt->memo }}</p>
    @endif
@endsection
