@extends('layouts.admin')
@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')
@section('breadcrumb', 'Invoices / Create')
@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('invoices.store') }}" class="card p-6">@csrf
        @include('invoices._form', compact('members', 'plans'))
        <div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Create Invoice</button><a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
