@props(['product' => null, 'categories', 'suppliers'])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div class="md:col-span-2"><label class="form-label">Product Name *</label><input type="text" name="name" value="{{ old('name', $product?->name) }}" class="form-input" required></div>
    <div><label class="form-label">SKU</label><input type="text" name="sku" value="{{ old('sku', $product?->sku) }}" class="form-input" placeholder="Auto-generated if empty"></div>
    <div><label class="form-label">Barcode</label><input type="text" name="barcode" value="{{ old('barcode', $product?->barcode) }}" class="form-input"></div>
    <div><label class="form-label">Category</label><select name="category_id" class="form-input"><option value="">Select</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product?->category_id) == $c->id)>{{ $c->name }}</option>@endforeach</select></div>
    <div><label class="form-label">Brand</label><input type="text" name="brand" value="{{ old('brand', $product?->brand) }}" class="form-input"></div>
    <div><label class="form-label">Purchase Price (₹) *</label><input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product?->purchase_price) }}" class="form-input" required></div>
    <div><label class="form-label">Selling Price (₹) *</label><input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product?->selling_price) }}" class="form-input" required></div>
    <div><label class="form-label">Minimum Stock Level *</label><input type="number" name="minimum_stock_level" value="{{ old('minimum_stock_level', $product?->minimum_stock_level ?? 5) }}" class="form-input" min="0" required></div>
    <div><label class="form-label">Unit *</label><input type="text" name="unit" value="{{ old('unit', $product?->unit ?? 'pcs') }}" class="form-input" required></div>
    <div><label class="form-label">Supplier</label><select name="supplier_id" class="form-input"><option value="">Select</option>@foreach($suppliers as $s)<option value="{{ $s->id }}" @selected(old('supplier_id', $product?->supplier_id) == $s->id)>{{ $s->name }}</option>@endforeach</select></div>
    <div><label class="form-label">Status *</label><select name="status" class="form-input"><option value="active" @selected(old('status', $product?->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $product?->status) === 'inactive')>Inactive</option></select></div>
    <div><label class="form-label">Product Image</label><input type="file" name="image" accept="image/*" class="form-input"></div>
    <div class="md:col-span-2"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-input">{{ old('description', $product?->description) }}</textarea></div>
    @if($product)<div><label class="form-label">Current Stock</label><p class="text-lg font-bold text-slate-900">{{ $product->current_stock }} {{ $product->unit }} <x-stock-badge :product="$product" /></p><p class="text-xs text-slate-500 mt-1">Stock is updated via purchases, sales, and adjustments only.</p></div>@endif
</div>
