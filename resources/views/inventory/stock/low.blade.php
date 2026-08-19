@extends('layouts.admin')
@section('title', 'Low Stock')
@section('page-title', 'Low Stock Alerts')
@section('breadcrumb', 'Inventory / Low Stock')
@section('content')
<div class="mb-6 flex justify-end"><a href="{{ route('inventory.purchases.create') }}" class="btn btn-primary">Purchase Stock</a></div>
<div class="card overflow-hidden">
    <table class="w-full"><thead><tr><th class="table-header">Product</th><th class="table-header">Stock</th><th class="table-header">Minimum</th><th class="table-header">Status</th><th class="table-header text-right">Action</th></tr></thead>
    <tbody class="divide-y">@forelse($products as $p)<tr class="hover:bg-slate-50"><td class="table-cell font-medium">{{ $p->name }}</td><td class="table-cell font-bold">{{ $p->current_stock }}</td><td class="table-cell">{{ $p->minimum_stock_level }}</td><td class="table-cell"><x-stock-badge :product="$p" /></td><td class="table-cell text-right"><a href="{{ route('inventory.purchases.create') }}?product={{ $p->id }}" class="btn btn-primary btn-sm">Purchase Stock</a></td></tr>@empty<tr><td colspan="5" class="table-cell text-center py-12 text-slate-400">All products adequately stocked</td></tr>@endforelse</tbody></table>
    @if($products->hasPages())<div class="px-6 py-4 border-t">{{ $products->links() }}</div>@endif
</div>
@endsection
