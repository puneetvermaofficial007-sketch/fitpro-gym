@extends('layouts.admin')
@section('title', 'Stock Management')
@section('page-title', 'Stock Management')
@section('breadcrumb', 'Inventory / Stock')
@section('content')
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
    @foreach([['Total Products',$stats['total_products']],['Total Stock Qty',$stats['total_stock']],['Low Stock',$stats['low_stock'],'amber'],['Out of Stock',$stats['out_of_stock'],'red'],['Inventory Value','₹'.number_format($stats['inventory_value']),'emerald']] as $item)
        <div class="card p-4"><p class="text-xs text-slate-500">{{ $item[0] }}</p><p class="text-xl font-bold {{ isset($item[2]) && $item[2]==='red' ? 'text-red-600' : (isset($item[2]) && $item[2]==='amber' ? 'text-amber-600' : '') }}">{{ $item[1] }}</p></div>
    @endforeach
</div>
<div class="card overflow-hidden">
    <table class="w-full"><thead><tr><th class="table-header">Product</th><th class="table-header">Category</th><th class="table-header">Stock</th><th class="table-header">Min</th><th class="table-header">Value</th><th class="table-header">Status</th><th class="table-header text-right">Actions</th></tr></thead>
    <tbody class="divide-y">@foreach($products as $p)<tr class="hover:bg-slate-50"><td class="table-cell font-medium">{{ $p->name }}</td><td class="table-cell">{{ $p->category?->name }}</td><td class="table-cell font-bold">{{ $p->current_stock }}</td><td class="table-cell">{{ $p->minimum_stock_level }}</td><td class="table-cell">₹{{ number_format($p->inventoryValue()) }}</td><td class="table-cell"><x-stock-badge :product="$p" /></td><td class="table-cell text-right"><a href="{{ route('inventory.purchases.create') }}?product={{ $p->id }}" class="btn btn-primary btn-sm">Purchase</a></td></tr>@endforeach</tbody></table>
    @if($products->hasPages())<div class="px-6 py-4 border-t">{{ $products->links() }}</div>@endif
</div>
@endsection
