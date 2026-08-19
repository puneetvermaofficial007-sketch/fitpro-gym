@extends('layouts.admin')
@section('title', 'Edit Invoice')
@section('page-title', 'Edit Invoice')
@section('breadcrumb', 'Invoices / Edit')
@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="card p-6">@csrf @method('PUT')
        @include('invoices._form', ['invoice' => $invoice, 'members' => $members, 'plans' => $plans])
        <div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Update</button><a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
