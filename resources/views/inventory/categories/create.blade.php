@extends('layouts.admin')
@section('title', 'Add Category')
@section('page-title', 'Add Category')
@section('content')
<div class="max-w-lg"><form method="POST" action="{{ route('inventory.categories.store') }}" class="card p-6 space-y-4">@csrf
<div><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required></div>
<div><label class="form-label">Description</label><textarea name="description" class="form-input" rows="2"></textarea></div>
<div><label class="form-label">Status</label><select name="status" class="form-input"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
<div class="flex gap-3 pt-4 border-t"><button class="btn btn-primary">Save</button><a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">Cancel</a></div>
</form></div>
@endsection
