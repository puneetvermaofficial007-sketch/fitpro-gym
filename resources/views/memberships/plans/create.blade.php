@extends('layouts.admin')
@section('title', 'Add Plan')
@section('page-title', 'Add Membership Plan')
@section('breadcrumb', 'Membership / Add Plan')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('memberships.plans.store') }}" class="card p-6">
        @csrf
        @include('memberships.plans._form')
        <div class="mt-6 flex gap-3 border-t pt-6">
            <button type="submit" class="btn btn-primary">Save Plan</button>
            <a href="{{ route('memberships.plans.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
