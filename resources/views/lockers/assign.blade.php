@extends('layouts.admin')
@section('title', 'Assign Locker')
@section('page-title', 'Assign Locker '.$locker->locker_code)
@section('breadcrumb', 'Lockers / Assign')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('lockers.assign.store', $locker) }}" class="card p-6">
        @csrf
        <div>
            <label class="form-label">Member *</label>
            <select name="member_id" class="form-input @error('member_id') border-red-500 @enderror" required>
                <option value="">Select member</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                        {{ $member->full_name }} ({{ $member->member_code }})
                    </option>
                @endforeach
            </select>
            @error('member_id')<p class="form-error">{{ $message }}</p>@enderror
            @if($members->isEmpty())
                <p class="mt-2 text-sm text-slate-400">All members already have a locker assigned.</p>
            @endif
        </div>
        <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-6">
            <button type="submit" class="btn btn-primary" @disabled($members->isEmpty())>Assign Locker</button>
            <a href="{{ route('lockers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
