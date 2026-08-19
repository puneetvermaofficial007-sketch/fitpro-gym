@extends('layouts.admin')
@section('title', 'Assigned Diet Plans')
@section('page-title', 'Assigned Diet Plans')
@section('breadcrumb', 'Diet Plans / Assignments')
@section('content')
<div class="mb-6 flex justify-end"><a href="{{ route('diet-plans.assign') }}" class="btn btn-primary">Assign Diet Plan</a></div>
<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr><th class="table-header">Member</th><th class="table-header">Diet Plan</th><th class="table-header">Start</th><th class="table-header">End</th><th class="table-header">Goal</th><th class="table-header">Status</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($assignments as $a)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell"><a href="{{ route('members.show', $a->member) }}" class="text-primary-600 hover:underline">{{ $a->member?->full_name }}</a></td>
                    <td class="table-cell">{{ $a->dietPlan?->name }}</td>
                    <td class="table-cell">{{ $a->start_date->format('M d, Y') }}</td>
                    <td class="table-cell">{{ $a->end_date?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-cell">{{ $a->goal ?? '-' }}</td>
                    <td class="table-cell"><span class="badge {{ $a->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $a->is_active ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="table-cell text-center py-12 text-slate-400">No assignments yet</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($assignments->hasPages())<div class="px-6 py-4 border-t">{{ $assignments->links() }}</div>@endif
</div>
@endsection
