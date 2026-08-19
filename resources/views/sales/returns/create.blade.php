@extends('layouts.admin')
@section('title', 'Process Return')
@section('page-title', 'Return - ' . $sale->sale_number)
@section('content')
<div class="max-w-2xl"><form method="POST" action="{{ route('sales.return.store', $sale) }}" class="card p-6 space-y-4">@csrf
<p class="text-sm text-slate-500">Sale: {{ $sale->sale_number }} — {{ $sale->customerDisplayName() }}</p>
@foreach($sale->items as $item)
    @if($item->returnableQuantity() > 0)
        <div class="border rounded-lg p-4">
            <p class="font-medium">{{ $item->product->name }}</p>
            <p class="text-xs text-slate-500">Sold: {{ $item->quantity }} | Returnable: {{ $item->returnableQuantity() }} | ₹{{ number_format($item->unit_price) }}/unit</p>
            <input type="hidden" name="items[{{ $loop->index }}][sale_item_id]" value="{{ $item->id }}">
            <div class="mt-2"><label class="form-label">Return Quantity</label><input type="number" name="items[{{ $loop->index }}][quantity]" min="0" max="{{ $item->returnableQuantity() }}" value="0" class="form-input w-32"></div>
        </div>
    @endif
@endforeach
<div><label class="form-label">Return Reason</label><textarea name="reason" class="form-input" rows="2"></textarea></div>
<div class="flex gap-3 border-t pt-4"><button class="btn btn-primary">Process Return</button><a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">Cancel</a></div>
</form></div>
@endsection
