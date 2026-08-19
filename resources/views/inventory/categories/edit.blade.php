@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('content')
<div class="max-w-lg"><form method="POST" action="{{ route('inventory.categories.update', $category) }}" class="card p-6 space-y-4">@csrf @method('PUT')
<div><label class="form-label">Name *</label><input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-input" required></div>
<div><label class="form-label">Description</label><textarea name="description" class="form-input" rows="2">{{ old('description', $category->description) }}</textarea></div>
<div><label class="form-label">Status</label><select name="status" class="form-input"><option value="active" @selected($category->status==='active')>Active</option><option value="inactive" @selected($category->status==='inactive')>Inactive</option></select></div>
<div class="flex gap-3 pt-4 border-t"><button class="btn btn-primary">Update</button><a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">Cancel</a></div>
</form></div>
@endsection
