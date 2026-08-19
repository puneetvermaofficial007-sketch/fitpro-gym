@extends('layouts.admin')
@section('title', 'Enquiries')
@section('page-title', match($filter) { 'new' => 'New Enquiries', 'follow-ups' => 'Follow-ups', 'converted' => 'Converted', 'lost' => 'Lost Enquiries', default => 'All Enquiries' })
@section('breadcrumb', 'Enquiry Management')
@section('content')
<div class="mb-6 grid grid-cols-3 gap-4">
    @foreach([['New', $stats['new'], 'info'], ['Follow-ups Today', $stats['follow_ups_today'], 'warning'], ['Converted', $stats['converted'], 'success']] as [$l,$v,$c])
        <div class="card p-4"><p class="text-xs text-slate-500">{{ $l }}</p><p class="text-2xl font-bold">{{ $v }}</p></div>
    @endforeach
</div>
<div class="mb-6 flex justify-between"><form method="GET" class="flex gap-2">@if($filter !== 'all')<input type="hidden" name="filter" value="{{ $filter }}">@endif<input type="text" name="search" value="{{ $search }}" class="form-input" placeholder="Search..."><button class="btn btn-primary btn-sm">Search</button></form><a href="{{ route('enquiries.create') }}" class="btn btn-primary">Add Enquiry</a></div>
<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr><th class="table-header">Name</th><th class="table-header">Contact</th><th class="table-header">Interest</th><th class="table-header">Source</th><th class="table-header">Follow-up</th><th class="table-header">Status</th><th class="table-header text-right">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($enquiries as $e)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell font-medium">{{ $e->name }}</td>
                    <td class="table-cell"><p>{{ $e->phone }}</p><p class="text-xs text-slate-500">{{ $e->email }}</p></td>
                    <td class="table-cell">{{ $e->interested_membership ?? '-' }}</td>
                    <td class="table-cell">{{ $e->source ?? '-' }}</td>
                    <td class="table-cell">{{ $e->follow_up_date?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-cell"><span class="badge {{ match($e->status){ 'new'=>'badge-info','converted'=>'badge-success','lost'=>'badge-danger',default=>'badge-warning' } }}">{{ ucfirst(str_replace('_',' ',$e->status)) }}</span></td>
                    <td class="table-cell text-right"><a href="{{ route('enquiries.edit', $e) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form method="POST" action="{{ route('enquiries.destroy', $e) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form></td>
                </tr>
            @empty
                <tr><td colspan="7" class="table-cell text-center py-12 text-slate-400">No enquiries</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($enquiries->hasPages())<div class="px-6 py-4 border-t">{{ $enquiries->links() }}</div>@endif
</div>
@endsection
