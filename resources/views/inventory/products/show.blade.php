@extends('layouts.admin')
@section('title', $product->name)
@section('page-title', $product->name)
@section('breadcrumb', 'Inventory / Products / View')

@section('content')
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit</a>
    <a href="{{ route('inventory.adjustments.create', ['product_id' => $product->id]) }}" class="btn btn-secondary btn-sm">Adjust Stock</a>
    <a href="{{ route('inventory.stock.history', ['product_id' => $product->id]) }}" class="btn btn-secondary btn-sm">Stock History</a>
    @if($product->current_stock > 0)<a href="{{ route('sales.pos') }}" class="btn btn-success btn-sm">Sell</a>@endif
</div>
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="card p-6 lg:col-span-1 text-center">
        @if($product->image)<img src="{{ Storage::url($product->image) }}" class="mx-auto h-32 w-32 rounded-xl object-cover mb-4">@else<div class="mx-auto h-32 w-32 rounded-xl bg-primary-100 flex items-center justify-center text-3xl font-bold text-primary-600 mb-4">{{ substr($product->name,0,2) }}</div>@endif
        <h2 class="text-xl font-bold">{{ $product->name }}</h2>
        <p class="text-sm text-slate-500 font-mono">{{ $product->sku }}</p>
        <div class="mt-4"><x-stock-badge :product="$product" /></div>
        <p class="mt-4 text-3xl font-bold text-slate-900">{{ $product->current_stock }} <span class="text-sm font-normal text-slate-500">{{ $product->unit }}</span></p>
    </div>
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6 grid grid-cols-2 gap-4">
            @foreach([['Category',$product->category?->name],['Brand',$product->brand],['Purchase','₹'.number_format($product->purchase_price)],['Selling','₹'.number_format($product->selling_price)],['Min Stock',$product->minimum_stock_level],['Supplier',$product->supplier?->name],['Inventory Value','₹'.number_format($product->inventoryValue())]] as [$l,$v])
                <div><p class="text-xs uppercase text-slate-400">{{ $l }}</p><p class="font-medium">{{ $v ?? '-' }}</p></div>
            @endforeach
        </div>
        @if($product->description)<div class="card p-6"><p class="text-sm text-slate-600">{{ $product->description }}</p></div>@endif
        <div class="card overflow-hidden">
            <div class="border-b px-6 py-3 font-semibold">Recent Stock History</div>
            <table class="w-full"><thead><tr><th class="table-header">Date</th><th class="table-header">Type</th><th class="table-header">Qty</th><th class="table-header">Stock</th><th class="table-header">Ref</th></tr></thead>
            <tbody class="divide-y">@forelse($product->stockTransactions as $tx)<tr><td class="table-cell">{{ $tx->created_at->format('M d, Y H:i') }}</td><td class="table-cell">{{ $tx->typeLabel() }}</td><td class="table-cell {{ $tx->quantity > 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $tx->quantity > 0 ? '+' : '' }}{{ $tx->quantity }}</td><td class="table-cell">{{ $tx->previous_stock }} → {{ $tx->new_stock }}</td><td class="table-cell text-xs">{{ $tx->referenceLabel() ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="table-cell text-center py-8 text-slate-400">No transactions</td></tr>@endforelse</tbody></table>
        </div>
    </div>
</div>
@endsection
