@extends('layouts.admin')
@section('title', 'Stock Adjustment')
@section('page-title', 'Stock Adjustment')
@section('content')
<div class="max-w-lg"><form method="POST" action="{{ route('inventory.adjustments.store') }}" class="card p-6 space-y-4">@csrf
<div><label class="form-label">Product *</label><select name="product_id" class="form-input" required>@foreach($products as $p)<option value="{{ $p->id }}" @selected(old('product_id', $selectedProduct?->id) == $p->id)>{{ $p->name }} (Stock: {{ $p->current_stock }})</option>@endforeach</select></div>
<div><label class="form-label">Adjustment Type *</label><select name="adjustment_type" class="form-input"><option value="increase">Stock Increase</option><option value="decrease">Stock Decrease</option><option value="damaged">Damaged</option><option value="lost">Lost</option><option value="expired">Expired</option><option value="correction">Correction</option></select></div>
<div><label class="form-label">Quantity *</label><input type="number" name="quantity" min="1" class="form-input" required></div>
<div><label class="form-label">Reason</label><textarea name="reason" class="form-input" rows="3" placeholder="e.g. Damaged products found during inventory check"></textarea></div>
<div class="flex gap-3 border-t pt-4"><button class="btn btn-primary">Apply Adjustment</button><a href="{{ route('inventory.adjustments.index') }}" class="btn btn-secondary">Cancel</a></div>
</form></div>
@endsection
