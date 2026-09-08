@extends('layouts.admin')
@section('title', 'Attendance')
@section('page-title', match($filter) { 'history' => 'Attendance History', 'monthly' => 'Monthly Report', default => "Today's Attendance" })
@section('breadcrumb', 'Attendance Management')

@section('content')
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @foreach([['Present Today', $stats['present_today'], 'emerald'], ['Absent Today', $stats['absent_today'], 'red'], ['Active Members', $stats['active_members'], 'blue'], ['Attendance %', $stats['percentage'].'%', 'purple']] as [$l, $v])
        <div class="card p-4"><p class="text-xs text-slate-500">{{ $l }}</p><p class="text-2xl font-bold">{{ $v }}</p></div>
    @endforeach
</div>
<div class="mb-6 flex flex-wrap gap-2 justify-between">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="hidden" name="filter" value="{{ $filter }}">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search member..." class="form-input w-48">
        @if($filter === 'history')
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-40">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-40">
        @endif
        @if($filter === 'monthly')
            <input type="number" name="month" value="{{ request('month', now()->month) }}" min="1" max="12" class="form-input w-24" placeholder="Month">
            <input type="number" name="year" value="{{ request('year', now()->year) }}" class="form-input w-28" placeholder="Year">
        @endif
        <button class="btn btn-primary btn-sm">Filter</button>
    </form>
    <a href="{{ route('attendance.mark') }}" class="btn btn-primary">Mark Attendance</a>
</div>
<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr><th class="table-header">Member</th><th class="table-header">Date</th><th class="table-header">Check In</th><th class="table-header">Check Out</th><th class="table-header">Status</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($attendances as $a)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell font-medium">{{ $a->member?->full_name }}</td>
                    <td class="table-cell">{{ $a->date->format('M d, Y') }}</td>
                    <td class="table-cell">{{ $a->check_in ? substr($a->check_in, 0, 5) : '-' }}</td>
                    <td class="table-cell">{{ $a->check_out ? substr($a->check_out, 0, 5) : '-' }}</td>
                    <td class="table-cell"><span class="badge {{ $a->status === 'present' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($a->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-cell text-center py-12 text-slate-400">No attendance records</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($attendances->hasPages())<div class="px-6 py-4 border-t">{{ $attendances->links() }}</div>@endif
      @if($attendances->hasPages())<div class="px-6 py-4 border-t">{{ $attendances->links() }}</div>@endif
</div>
@endsection
