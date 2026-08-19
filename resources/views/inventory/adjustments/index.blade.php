@extends('layouts.admin')
@section('title', 'Stock Adjustments')
@section('page-title', 'Stock Adjustments')
@section('content')
<div class="mb-6 flex justify-end"><a href="{{ route('inventory.adjustments.create') }}" class="btn btn-primary">New Adjustment</a></div>
<div class="card overflow-hidden"><table class="w-full"><thead><tr><th class="table-header">Date</th><th class="table-header">Product</th><th class="table-header">Type</th><th class="table-header">Change</th><th class="table-header">Stock</th><th class="table-header">Reason</th><th class="table-header">By</th></tr></thead>
<tbody class="divide-y">@forelse($adjustments as $a)<tr><td class="table-cell">{{ $a->created_at->format('M d, Y') }}</td><td class="table-cell">{{ $a->product?->name }}</td><td class="table-cell">{{ ucfirst($a->adjustment_type) }}</td><td class="table-cell font-medium {{ $a->quantity_change > 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $a->quantity_change > 0 ? '+' : '' }}{{ $a->quantity_change }}</td><td class="table-cell">{{ $a->previous_stock }} → {{ $a->new_stock }}</td><td class="table-cell text-sm">{{ Str::limit($a->reason, 40) }}</td><td class="table-cell text-xs">{{ $a->user?->name }}</td></tr>@empty<tr><td colspan="7" class="table-cell text-center py-12 text-slate-400">No adjustments</td></tr>@endforelse</tbody></table>
@if($adjustments->hasPages())<div class="px-6 py-4 border-t">{{ $adjustments->links() }}</div>@endif</div>
@endsection
