@extends('layouts.admin')
@section('title', 'Add Supplier')
@section('page-title', 'Add Supplier')
@section('content')
<div class="max-w-2xl"><form method="POST" action="{{ route('inventory.suppliers.store') }}" class="card p-6 grid grid-cols-2 gap-4">@csrf
<div><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required></div>
<div><label class="form-label">Company</label><input type="text" name="company_name" class="form-input"></div>
<div><label class="form-label">Phone</label><input type="text" name="phone" class="form-input"></div>
<div><label class="form-label">Email</label><input type="email" name="email" class="form-input"></div>
<div class="col-span-2"><label class="form-label">Address</label><textarea name="address" class="form-input" rows="2"></textarea></div>
<div><label class="form-label">Tax Number</label><input type="text" name="tax_number" class="form-input"></div>
<div><label class="form-label">Status</label><select name="status" class="form-input"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
<div class="col-span-2"><label class="form-label">Notes</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
<div class="col-span-2 flex gap-3 border-t pt-4"><button class="btn btn-primary">Save</button><a href="{{ route('inventory.suppliers.index') }}" class="btn btn-secondary">Cancel</a></div>
</form></div>
@endsection
