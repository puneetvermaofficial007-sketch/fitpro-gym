@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')
@section('content')
<div class="max-w-2xl"><form method="POST" action="{{ route('inventory.suppliers.update', $supplier) }}" class="card p-6 grid grid-cols-2 gap-4">@csrf @method('PUT')
<div><label class="form-label">Name *</label><input type="text" name="name" value="{{ $supplier->name }}" class="form-input" required></div>
<div><label class="form-label">Company</label><input type="text" name="company_name" value="{{ $supplier->company_name }}" class="form-input"></div>
<div><label class="form-label">Phone</label><input type="text" name="phone" value="{{ $supplier->phone }}" class="form-input"></div>
<div><label class="form-label">Email</label><input type="email" name="email" value="{{ $supplier->email }}" class="form-input"></div>
<div class="col-span-2"><label class="form-label">Address</label><textarea name="address" class="form-input" rows="2">{{ $supplier->address }}</textarea></div>
<div><label class="form-label">Tax Number</label><input type="text" name="tax_number" value="{{ $supplier->tax_number }}" class="form-input"></div>
<div><label class="form-label">Status</label><select name="status" class="form-input"><option value="active" @selected($supplier->status==='active')>Active</option><option value="inactive" @selected($supplier->status==='inactive')>Inactive</option></select></div>
<div class="col-span-2 flex gap-3 border-t pt-4"><button class="btn btn-primary">Update</button><a href="{{ route('inventory.suppliers.index') }}" class="btn btn-secondary">Cancel</a></div>
</form></div>
@endsection
