@extends('layouts.admin')

@section('title', 'Members')
@section('page-title', match($filter) {
    'active' => 'Active Members',
    'expired' => 'Expired Members',
    'deleted' => 'Deleted Members',
    default => 'All Members',
})
@section('breadcrumb', 'Manage gym members')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm text-slate-500">{{ $members->total() }} member(s) found</p>
    </div>
    @if($filter !== 'deleted')
        <a href="{{ route('members.create') }}" class="btn btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Member
        </a>
    @endif
</div>

{{-- Filters --}}
<div class="card mb-6 p-4">
    <form method="GET" action="{{ route('members.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @if($filter !== 'all')
            <input type="hidden" name="filter" value="{{ $filter }}">
        @endif
        <div class="lg:col-span-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email, phone, code..." class="form-input">
        </div>
        <div>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                @foreach(['active', 'inactive', 'expired'] as $s)
                    <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="payment_status" class="form-input">
                <option value="">All Payments</option>
                @foreach(['paid', 'pending', 'overdue'] as $s)
                    <option value="{{ $s }}" @selected($paymentStatus === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary flex-1">Filter</button>
            <a href="{{ route('members.index', $filter !== 'all' ? ['filter' => $filter] : []) }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

{{-- Members Table --}}
<div class="card overflow-hidden">
    @if($members->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-slate-400">
            <svg class="mb-4 h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-lg font-medium text-slate-600">No members found</p>
            <p class="text-sm mt-1">Try adjusting your search or filters</p>
            @if($filter !== 'deleted')
                <a href="{{ route('members.create') }}" class="btn btn-primary mt-4">Add First Member</a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="table-header">Member</th>
                        <th class="table-header hidden sm:table-cell">Code</th>
                        <th class="table-header">Plan</th>
                        <th class="table-header hidden md:table-cell">Phone</th>
                        <th class="table-header hidden lg:table-cell">Expiry</th>
                        <th class="table-header">Payment</th>
                        <th class="table-header">Status</th>
                        <th class="table-header text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($members as $member)
                        <tr class="hover:bg-slate-50">
                            <td class="table-cell">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 text-xs font-semibold text-primary-700">{{ $member->initials }}</div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $member->full_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $member->email ?? 'No email' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell hidden sm:table-cell font-mono text-xs">{{ $member->member_code }}</td>
                            <td class="table-cell">{{ $member->membershipPlan?->name ?? '-' }}</td>
                            <td class="table-cell hidden md:table-cell">{{ $member->phone }}</td>
                            <td class="table-cell hidden lg:table-cell">
                                @if($member->membership_expiry_date)
                                    @php $days = $member->daysUntilExpiry(); @endphp
                                    <span class="{{ $days !== null && $days <= 7 ? 'text-amber-600 font-medium' : '' }}">
                                        {{ $member->membership_expiry_date->format('M d, Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="table-cell">
                                <span class="badge {{ $member->payment_status === 'paid' ? 'badge-success' : ($member->payment_status === 'overdue' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ ucfirst($member->payment_status) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                <span class="badge {{ $member->status === 'active' ? 'badge-success' : ($member->status === 'expired' ? 'badge-danger' : 'badge-neutral') }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center justify-end gap-1">
                                    @if($filter === 'deleted')
                                        <form method="POST" action="{{ route('members.restore', $member->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('members.show', $member) }}" class="rounded p-1.5 text-slate-400 hover:bg-slate-100 hover:text-primary-600" title="View">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('members.edit', $member) }}" class="rounded p-1.5 text-slate-400 hover:bg-slate-100 hover:text-amber-600" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('members.toggle-status', $member) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="rounded p-1.5 text-slate-400 hover:bg-slate-100 hover:text-emerald-600" title="Toggle Status">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Are you sure you want to delete this member?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600" title="Delete">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $members->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
