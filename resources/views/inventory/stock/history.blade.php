@extends('layouts.admin')
@section('title', 'Stock History')
@section('page-title', 'Stock History')
@section('breadcrumb', 'Inventory / Stock History')
@section('content')
<div class="mb-6"><form method="GET" class="flex flex-wrap gap-2">
    <select name="product_id" class="form-input w-48"><option value="">All Products</option>@foreach($products as $p)<option value="{{ $p->id }}" @selected(request('product_id')==$p->id)>{{ $p->name }}</option>@endforeach</select>
    <select name="type" class="form-input w-36"><option value="">All Types</option>@foreach(['purchase','sale','return','adjustment','damage','expired','correction'] as $t)<option value="{{ $t }}" @selected(request('type')===$t)>{{ ucfirst($t) }}</option>@endforeach</select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-36"><input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-36">
    <button class="btn btn-primary btn-sm">Filter</button>
</form></div>
<div class="card overflow-hidden">
    <table class="w-full"><thead><tr><th class="table-header">Date</th><th class="table-header">Product</th><th class="table-header">Type</th><th class="table-header">Qty</th><th class="table-header">Previous</th><th class="table-header">New</th><th class="table-header">Reference</th><th class="table-header">User</th></tr></thead>
    <tbody class="divide-y">@forelse($transactions as $tx)<tr class="hover:bg-slate-50"><td class="table-cell">{{ $tx->created_at->format('M d, Y H:i') }}</td><td class="table-cell">{{ $tx->product?->name }}</td><td class="table-cell"><span class="badge badge-neutral">{{ $tx->typeLabel() }}</span></td><td class="table-cell font-medium {{ $tx->quantity > 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $tx->quantity > 0 ? '+' : '' }}{{ $tx->quantity }}</td><td class="table-cell">{{ $tx->previous_stock }}</td><td class="table-cell">{{ $tx->new_stock }}</td><td class="table-cell text-xs font-mono">{{ $tx->referenceLabel() ?? '-' }}</td><td class="table-cell text-xs">{{ $tx->user?->name ?? '-' }}</td></tr>@empty<tr><td colspan="8" class="table-cell text-center py-12 text-slate-400">No transactions</td></tr>@endforelse</tbody></table>
    @if($transactions->hasPages())<div class="px-6 py-4 border-t">{{ $transactions->links() }}</div>@endif
</div>
@endsection
