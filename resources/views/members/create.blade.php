@extends('layouts.admin')

@section('title', 'Add Member')
@section('page-title', 'Add New Member')
@section('breadcrumb', 'Members / Add Member')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('members.store') }}" class="card p-6">
        @csrf
        @include('members._form', ['plans' => $plans])

        <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="btn btn-primary">Save Member</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
