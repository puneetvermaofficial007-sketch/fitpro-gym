@extends('layouts.admin')
@section('title', 'Lockers')
@section('page-title', 'Locker Management')
@section('breadcrumb', 'Lockers')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap gap-2">
        @foreach(['all' => 'All', 'available' => 'Available', 'assigned' => 'Assigned', 'maintenance' => 'Maintenance'] as $key => $label)
            <a href="{{ route('lockers.index', ['filter' => $key]) }}" class="btn btn-sm {{ $filter === $key ? 'btn-primary' : 'btn-secondary' }}">
                {{ $label }} ({{ $counts[$key] }})
            </a>
        @endforeach
    </div>
    <a href="{{ route('lockers.create') }}" class="btn btn-primary">Add Locker</a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header">Locker</th>
                    <th class="table-header">Status</th>
                    <th class="table-header">Assigned Member</th>
                    <th class="table-header">Notes</th>
                    <th class="table-header">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($lockers as $locker)
                    <tr>
                        <td class="table-cell font-medium font-mono">{{ $locker->locker_code }}</td>
                        <td class="table-cell">
                            <span class="badge {{ match($locker->status) { 'available' => 'badge-success', 'assigned' => 'badge-warning', default => 'badge-neutral' } }}">
                                {{ ucfirst($locker->status) }}
                            </span>
                        </td>
                        <td class="table-cell">
                            @if($locker->member)
                                <a href="{{ route('members.show', $locker->member) }}" class="text-primary-600 hover:underline">{{ $locker->member->full_name }}</a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="table-cell text-slate-500">{{ $locker->notes ? \Illuminate\Support\Str::limit($locker->notes, 40) : '—' }}</td>
                        <td class="table-cell">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('lockers.edit', $locker) }}" class="btn btn-secondary btn-sm">Edit</a>
                                @if($locker->isAvailable())
                                    <a href="{{ route('lockers.assign', $locker) }}" class="btn btn-primary btn-sm">Assign</a>
                                @endif
                                @if($locker->isAssigned())
                                    <form method="POST" action="{{ route('lockers.unassign', $locker) }}">
                                        @csrf
                                        <button class="btn btn-secondary btn-sm" onclick="return confirm('Unassign this locker?')">Unassign</button>
                                    </form>
                                @endif
                                @unless($locker->isAssigned() || $locker->member)
                                    <form method="POST" action="{{ route('lockers.destroy', $locker) }}" onsubmit="return confirm('Delete this locker?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">No lockers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($lockers->hasPages())<div class="mt-4">{{ $lockers->links() }}</div>@endif
@endsection
