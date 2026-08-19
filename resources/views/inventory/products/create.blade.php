@extends('layouts.admin')
@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('breadcrumb', 'Inventory / Products / Add')
@section('content')
<div class="max-w-4xl"><form method="POST" action="{{ route('inventory.products.store') }}" enctype="multipart/form-data" class="card p-6">@csrf @include('inventory.products._form', compact('categories', 'suppliers'))<div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Save Product</button><a href="{{ route('inventory.products.index') }}" class="btn btn-secondary">Cancel</a></div></form></div>
@endsection
