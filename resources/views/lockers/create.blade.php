@extends('layouts.admin')
@section('title', 'Add Locker')
@section('page-title', 'Add Locker')
@section('breadcrumb', 'Lockers / Add')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('lockers.store') }}" class="card p-6">
        @csrf
        @include('lockers._form')
        <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="btn btn-primary">Save Locker</button>
            <a href="{{ route('lockers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
