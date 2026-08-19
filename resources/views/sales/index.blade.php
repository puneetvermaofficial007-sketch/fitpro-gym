@extends('layouts.admin')
@section('title', 'Sales History')
@section('page-title', 'Sales History')
@section('breadcrumb', 'Sales / History')

@section('content')
<div class="mb-6 flex flex-wrap gap-2 justify-between">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search sale or customer..." class="form-input w-48">
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-36">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-36">
        <select name="payment_method" class="form-input w-32"><option value="">Payment</option>@foreach(['cash','card','upi','other'] as $m)<option value="{{ $m }}" @selected(request('payment_method')===$m)>{{ ucfirst($m) }}</option>@endforeach</select>
        <select name="payment_status" class="form-input w-32"><option value="">Status</option>@foreach(['paid','pending','partial'] as $s)<option value="{{ $s }}" @selected(request('payment_status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select>
        <button class="btn btn-primary btn-sm">Filter</button>
    </form>
    <a href="{{ route('sales.pos') }}" class="btn btn-primary">New Sale</a>
</div>
<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr><th class="table-header">Sale #</th><th class="table-header">Date</th><th class="table-header">Customer</th><th class="table-header">Items</th><th class="table-header">Total</th><th class="table-header">Payment</th><th class="table-header">By</th><th class="table-header text-right">Actions</th></tr></thead>
        <tbody class="divide-y">@forelse($sales as $sale)<tr class="hover:bg-slate-50"><td class="table-cell font-mono text-xs">{{ $sale->sale_number }}</td><td class="table-cell">{{ $sale->sale_date->format('M d, Y') }}</td><td class="table-cell">{{ $sale->customerDisplayName() }}</td><td class="table-cell">{{ $sale->items->count() }}</td><td class="table-cell font-medium">₹{{ number_format($sale->total) }}</td><td class="table-cell"><span class="badge {{ $sale->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($sale->payment_method) }}</span></td><td class="table-cell text-xs">{{ $sale->user?->name }}</td><td class="table-cell text-right"><a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-sm">View</a></td></tr>@empty<tr><td colspan="8" class="table-cell text-center py-12 text-slate-400">No sales found</td></tr>@endforelse</tbody>
    </table>
    @if($sales->hasPages())<div class="px-6 py-4 border-t">{{ $sales->links() }}</div>@endif
</div>
@endsection
