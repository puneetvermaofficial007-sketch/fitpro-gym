@extends('layouts.admin')
@section('title', 'Suppliers')
@section('page-title', 'Suppliers')
@section('breadcrumb', 'Inventory / Suppliers')
@section('content')
<div class="mb-6 flex justify-between"><form method="GET"><input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="form-input w-48 inline-block"><button class="btn btn-primary btn-sm ml-2">Search</button></form><a href="{{ route('inventory.suppliers.create') }}" class="btn btn-primary">Add Supplier</a></div>
<div class="card overflow-hidden"><table class="w-full"><thead><tr><th class="table-header">Name</th><th class="table-header">Company</th><th class="table-header">Phone</th><th class="table-header">Products</th><th class="table-header">Status</th><th class="table-header text-right">Actions</th></tr></thead>
<tbody class="divide-y">@forelse($suppliers as $s)<tr><td class="table-cell font-medium">{{ $s->name }}</td><td class="table-cell">{{ $s->company_name }}</td><td class="table-cell">{{ $s->phone }}</td><td class="table-cell">{{ $s->products_count }}</td><td class="table-cell"><span class="badge {{ $s->status==='active'?'badge-success':'badge-neutral' }}">{{ ucfirst($s->status) }}</span></td><td class="table-cell text-right"><a href="{{ route('inventory.suppliers.edit', $s) }}" class="btn btn-secondary btn-sm">Edit</a></td></tr>@empty<tr><td colspan="6" class="table-cell text-center py-12 text-slate-400">No suppliers</td></tr>@endforelse</tbody></table>
@if($suppliers->hasPages())<div class="px-6 py-4 border-t">{{ $suppliers->links() }}</div>@endif</div>
@endsection
