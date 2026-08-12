@extends('layouts.admin')

@section('title', 'Edit Member')
@section('page-title', 'Edit Member')
@section('breadcrumb', 'Members / ' . $member->full_name)

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('members.update', $member) }}" class="card p-6">
        @csrf @method('PUT')
        @include('members._form', ['member' => $member, 'plans' => $plans])

        <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="btn btn-primary">Update Member</button>
            <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
