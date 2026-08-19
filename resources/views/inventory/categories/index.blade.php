@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Product Categories')
@section('breadcrumb', 'Inventory / Categories')
@section('content')
<div class="mb-6 flex justify-end"><a href="{{ route('inventory.categories.create') }}" class="btn btn-primary">Add Category</a></div>
<div class="card overflow-hidden"><table class="w-full"><thead><tr><th class="table-header">Name</th><th class="table-header">Products</th><th class="table-header">Status</th><th class="table-header text-right">Actions</th></tr></thead>
<tbody class="divide-y">@forelse($categories as $c)<tr><td class="table-cell font-medium">{{ $c->name }}</td><td class="table-cell">{{ $c->products_count }}</td><td class="table-cell"><span class="badge {{ $c->status==='active'?'badge-success':'badge-neutral' }}">{{ ucfirst($c->status) }}</span></td><td class="table-cell text-right"><a href="{{ route('inventory.categories.edit', $c) }}" class="btn btn-secondary btn-sm">Edit</a><form method="POST" action="{{ route('inventory.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form></td></tr>@empty<tr><td colspan="4" class="table-cell text-center py-12 text-slate-400">No categories</td></tr>@endforelse</tbody></table>
@if($categories->hasPages())<div class="px-6 py-4 border-t">{{ $categories->links() }}</div>@endif</div>
@endsection
