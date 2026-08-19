@extends('layouts.admin')
@section('title', 'Returns')
@section('page-title', 'Product Returns')
@section('content')
<div class="card overflow-hidden"><table class="w-full"><thead><tr><th class="table-header">Return #</th><th class="table-header">Sale #</th><th class="table-header">Date</th><th class="table-header">Refund</th><th class="table-header">Reason</th><th class="table-header">By</th></tr></thead>
<tbody class="divide-y">@forelse($returns as $r)<tr><td class="table-cell font-mono text-xs">{{ $r->return_number }}</td><td class="table-cell"><a href="{{ route('sales.show', $r->sale) }}" class="text-primary-600">{{ $r->sale?->sale_number }}</a></td><td class="table-cell">{{ $r->return_date->format('M d, Y') }}</td><td class="table-cell">₹{{ number_format($r->total_refund) }}</td><td class="table-cell text-sm">{{ Str::limit($r->reason, 40) }}</td><td class="table-cell text-xs">{{ $r->user?->name }}</td></tr>@empty<tr><td colspan="6" class="table-cell text-center py-12 text-slate-400">No returns yet</td></tr>@endforelse</tbody></table>
@if($returns->hasPages())<div class="px-6 py-4 border-t">{{ $returns->links() }}</div>@endif</div>
@endsection
