@extends('layouts.admin')
@section('title', 'Mark Attendance')
@section('page-title', 'Mark Attendance')
@section('breadcrumb', 'Attendance / Mark')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('attendance.mark.store') }}" class="card p-6">@csrf
        <div class="space-y-4">
            <div>
                <label class="form-label">Member *</label>
                <select name="member_id" class="form-input" required>
                    <option value="">Select member</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" @disabled(in_array($member->id, $todayMarked))>{{ $member->full_name }} @if(in_array($member->id, $todayMarked))(marked)@endif</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status *</label>
                <select name="status" class="form-input" required>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select>
            </div>
            <div>
                <label class="form-label">Check In Time</label>
                <input type="time" name="check_in" value="{{ now()->format('H:i') }}" class="form-input">
            </div>
        </div>
        <div class="mt-6 flex gap-3 border-t pt-6">
            <button class="btn btn-primary">Mark Attendance</button>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
@endsection
