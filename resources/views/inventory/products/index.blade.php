@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')
@section('breadcrumb', 'Inventory / Products')

@section('content')
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <form method="GET" class="flex flex-wrap gap-2 flex-1">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search name, SKU, barcode..." class="form-input w-48">
        <select name="category_id" class="form-input w-40"><option value="">All Categories</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>@endforeach</select>
        <select name="stock_status" class="form-input w-36"><option value="">All Stock</option><option value="in" @selected(request('stock_status')==='in')>In Stock</option><option value="low" @selected(request('stock_status')==='low')>Low Stock</option><option value="out" @selected(request('stock_status')==='out')>Out of Stock</option></select>
        <select name="status" class="form-input w-32"><option value="">All Status</option><option value="active" @selected(request('status')==='active')>Active</option><option value="inactive" @selected(request('status')==='inactive')>Inactive</option></select>
        <button class="btn btn-primary btn-sm">Filter</button>
    </form>
    <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">Add Product</a>
</div>
<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr>
            <th class="table-header">Product</th><th class="table-header">SKU</th><th class="table-header">Category</th>
            <th class="table-header">Purchase</th><th class="table-header">Selling</th><th class="table-header">Stock</th><th class="table-header">Status</th><th class="table-header text-right">Actions</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($products as $product)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell"><div class="flex items-center gap-3">@if($product->image)<img src="{{ Storage::url($product->image) }}" class="h-10 w-10 rounded-lg object-cover">@else<div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-400">{{ substr($product->name,0,2) }}</div>@endif<div><p class="font-medium">{{ $product->name }}</p><x-stock-badge :product="$product" /></div></div></td>
                    <td class="table-cell font-mono text-xs">{{ $product->sku }}</td>
                    <td class="table-cell">{{ $product->category?->name ?? '-' }}</td>
                    <td class="table-cell">₹{{ number_format($product->purchase_price) }}</td>
                    <td class="table-cell font-medium">₹{{ number_format($product->selling_price) }}</td>
                    <td class="table-cell font-bold">{{ $product->current_stock }}</td>
                    <td class="table-cell"><span class="badge {{ $product->status === 'active' ? 'badge-success' : 'badge-neutral' }}">{{ ucfirst($product->status) }}</span></td>
                    <td class="table-cell text-right">
                        <div class="flex justify-end gap-1 flex-wrap">
                            <a href="{{ route('inventory.products.show', $product) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('inventory.products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @if($product->current_stock > 0)<a href="{{ route('sales.pos') }}?product={{ $product->id }}" class="btn btn-primary btn-sm">Sell</a>@endif
                            <a href="{{ route('inventory.adjustments.create', ['product_id' => $product->id]) }}" class="btn btn-secondary btn-sm">Adjust</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="table-cell text-center py-12 text-slate-400">No products found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())<div class="px-6 py-4 border-t">{{ $products->links() }}</div>@endif
</div>
@endsection
