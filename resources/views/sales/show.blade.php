@extends('layouts.admin')
@section('title', 'Sale Receipt')
@section('page-title', 'Sales Receipt')
@section('breadcrumb', 'Sales / ' . $sale->sale_number)

@section('content')
<div class="mb-6 flex gap-2 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-sm">Print Receipt</button>
    @if($sale->payment_status !== 'paid')<form method="POST" action="{{ route('sales.mark-paid', $sale) }}">@csrf @method('PATCH')<button class="btn btn-success btn-sm">Mark Paid</button></form>@endif
    @if($sale->items->sum(fn($i) => $i->returnableQuantity()) > 0)<a href="{{ route('sales.return', $sale) }}" class="btn btn-secondary btn-sm">Process Return</a>@endif
    <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">Back</a>
</div>
<div class="card max-w-lg mx-auto p-8" id="receipt">
    <div class="text-center border-b pb-4 mb-4">
        <h2 class="text-2xl font-bold">FITPRO GYM</h2>
        <p class="text-sm text-slate-500">SALES RECEIPT</p>
    </div>
    <div class="text-sm space-y-1 mb-4">
        <p><strong>Sale #:</strong> {{ $sale->sale_number }}</p>
        <p><strong>Date:</strong> {{ $sale->sale_date->format('M d, Y h:i A') }}</p>
        <p><strong>Customer:</strong> {{ $sale->customerDisplayName() }}</p>
        @if($sale->member)<p><strong>Member ID:</strong> {{ $sale->member->member_code }}</p>@endif
        <p><strong>Served by:</strong> {{ $sale->user?->name ?? 'Admin' }}</p>
    </div>
    <table class="w-full text-sm mb-4">
        <thead><tr class="border-b"><th class="py-2 text-left">Product</th><th class="py-2">Qty</th><th class="py-2 text-right">Price</th><th class="py-2 text-right">Total</th></tr></thead>
        <tbody>@foreach($sale->items as $item)<tr class="border-b"><td class="py-2">{{ $item->product->name }}</td><td class="py-2 text-center">{{ $item->quantity }}</td><td class="py-2 text-right">₹{{ number_format($item->unit_price) }}</td><td class="py-2 text-right">₹{{ number_format($item->total) }}</td></tr>@endforeach</tbody>
    </table>
    <div class="text-sm space-y-1 border-t pt-4">
        <div class="flex justify-between"><span>Subtotal</span><span>₹{{ number_format($sale->subtotal) }}</span></div>
        @if($sale->discount > 0)<div class="flex justify-between text-red-600"><span>Discount</span><span>-₹{{ number_format($sale->discount) }}</span></div>@endif
        @if($sale->tax > 0)<div class="flex justify-between"><span>Tax</span><span>₹{{ number_format($sale->tax) }}</span></div>@endif
        <div class="flex justify-between font-bold text-lg"><span>Total</span><span>₹{{ number_format($sale->total) }}</span></div>
        <div class="flex justify-between"><span>Profit</span><span class="text-emerald-600">₹{{ number_format($sale->profit) }}</span></div>
        <div class="flex justify-between"><span>Payment</span><span>{{ ucfirst($sale->payment_method) }} ({{ ucfirst($sale->payment_status) }})</span></div>
    </div>
    <p class="text-center text-sm text-slate-500 mt-6">Thank you for your purchase!</p>
</div>
@endsection
