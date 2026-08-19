@extends('layouts.admin')
@section('title', 'Purchase Stock')
@section('page-title', 'Purchase Stock')
@section('breadcrumb', 'Inventory / Purchase')

@section('content')
<div class="max-w-4xl" x-data="{ items: [{ product_id: '{{ request('product') }}', quantity: 1, purchase_price: '' }], addItem() { this.items.push({ product_id: '', quantity: 1, purchase_price: '' }); }, removeItem(i) { this.items.splice(i, 1); } }">
    <form method="POST" action="{{ route('inventory.purchases.store') }}" class="card p-6">@csrf
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div><label class="form-label">Supplier</label><select name="supplier_id" class="form-input"><option value="">Select supplier</option>@foreach($suppliers as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
            <div><label class="form-label">Purchase Date *</label><input type="date" name="purchase_date" value="{{ today()->format('Y-m-d') }}" class="form-input" required></div>
            <div class="col-span-2"><label class="form-label">Notes</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
        </div>
        <h3 class="font-semibold mb-3">Purchase Items</h3>
        <template x-for="(item, index) in items" :key="index">
            <div class="grid grid-cols-12 gap-2 mb-3 items-end">
                <div class="col-span-5"><label class="form-label text-xs" x-show="index===0">Product</label><select :name="'items['+index+'][product_id]'" class="form-input" required><option value="">Select</option>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
                <div class="col-span-2"><label class="form-label text-xs" x-show="index===0">Qty</label><input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="1" class="form-input" required></div>
                <div class="col-span-3"><label class="form-label text-xs" x-show="index===0">Price (₹)</label><input type="number" step="0.01" :name="'items['+index+'][purchase_price]'" x-model="item.purchase_price" min="0" class="form-input" required></div>
                <div class="col-span-2"><button type="button" @click="removeItem(index)" x-show="items.length > 1" class="btn btn-danger btn-sm w-full">Remove</button></div>
            </div>
        </template>
        <button type="button" @click="addItem()" class="btn btn-secondary btn-sm mb-6">+ Add Item</button>
        <div class="border-t pt-4 flex gap-3"><button type="submit" class="btn btn-primary">Save Purchase & Update Stock</button><a href="{{ route('inventory.purchases.index') }}" class="btn btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
