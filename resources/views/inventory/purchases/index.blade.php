@extends('layouts.admin')
@section('title', 'Purchases')
@section('page-title', 'Purchase History')
@section('content')
<div class="mb-6 flex justify-end"><a href="{{ route('inventory.purchases.create') }}" class="btn btn-primary">New Purchase</a></div>
<div class="card overflow-hidden"><table class="w-full"><thead><tr><th class="table-header">Purchase #</th><th class="table-header">Supplier</th><th class="table-header">Date</th><th class="table-header">Total</th><th class="table-header">By</th></tr></thead>
<tbody class="divide-y">@forelse($purchases as $p)<tr><td class="table-cell font-mono text-xs">{{ $p->purchase_number }}</td><td class="table-cell">{{ $p->supplier?->name ?? '-' }}</td><td class="table-cell">{{ $p->purchase_date->format('M d, Y') }}</td><td class="table-cell font-medium">₹{{ number_format($p->total_amount) }}</td><td class="table-cell text-xs">{{ $p->user?->name }}</td></tr>@empty<tr><td colspan="5" class="table-cell text-center py-12 text-slate-400">No purchases yet</td></tr>@endforelse</tbody></table>
@if($purchases->hasPages())<div class="px-6 py-4 border-t">{{ $purchases->links() }}</div>@endif</div>
@endsection
