@extends('layouts.admin')
@section('title', 'Edit Plan')
@section('page-title', 'Edit Membership Plan')
@section('breadcrumb', 'Membership / Edit')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('memberships.plans.update', $plan) }}" class="card p-6">
        @csrf @method('PUT')
        @include('memberships.plans._form', ['plan' => $plan])
        <div class="mt-6 flex gap-3 border-t pt-6">
            <button type="submit" class="btn btn-primary">Update Plan</button>
            <a href="{{ route('memberships.plans.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
