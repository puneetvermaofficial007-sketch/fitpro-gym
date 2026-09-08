@extends('layouts.admin')
@section('title', 'Edit Locker')
@section('page-title', 'Edit Locker')
@section('breadcrumb', 'Lockers / ' . $locker->locker_code)

@section('content')
<div class="max-w-3xl">
    @if($locker->member)
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Currently assigned to <a href="{{ route('members.show', $locker->member) }}" class="font-medium underline">{{ $locker->member->full_name }}</a>.
        </div>
    @endif
    <form method="POST" action="{{ route('lockers.update', $locker) }}" class="card p-6">
        @csrf @method('PUT')
        @include('lockers._form', ['locker' => $locker])
        <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="btn btn-primary">Update Locker</button>
            <a href="{{ route('lockers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
