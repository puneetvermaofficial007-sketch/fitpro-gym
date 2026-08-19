@extends('layouts.admin')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('breadcrumb', 'Inventory / Products / Edit')
@section('content')
<div class="max-w-4xl"><form method="POST" action="{{ route('inventory.products.update', $product) }}" enctype="multipart/form-data" class="card p-6">@csrf @method('PUT') @include('inventory.products._form', ['product' => $product, 'categories' => $categories, 'suppliers' => $suppliers])<div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Update</button><a href="{{ route('inventory.products.show', $product) }}" class="btn btn-secondary">Cancel</a></div></form></div>
@endsection
