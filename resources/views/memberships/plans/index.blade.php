@extends('layouts.admin')
@section('title', 'Membership Plans')
@section('page-title', 'Membership Plans')
@section('breadcrumb', 'Membership / Plans')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-sm text-slate-500">{{ $plans->total() }} plan(s)</p>
    <a href="{{ route('memberships.plans.create') }}" class="btn btn-primary">Add Plan</a>
</div>
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header">Plan</th>
                    <th class="table-header">Duration</th>
                    <th class="table-header">Price</th>
                    <th class="table-header">Members</th>
                    <th class="table-header">Status</th>
                    <th class="table-header text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($plans as $plan)
                    <tr class="hover:bg-slate-50">
                        <td class="table-cell">
                            <p class="font-medium">{{ $plan->name }}</p>
                            <p class="text-xs text-slate-500">{{ Str::limit($plan->description, 40) }}</p>
                        </td>
                        <td class="table-cell">{{ $plan->duration_days }} days</td>
                        <td class="table-cell">₹{{ number_format($plan->price) }}</td>
                        <td class="table-cell">{{ $plan->members_count }}</td>
                        <td class="table-cell"><span class="badge {{ $plan->status === 'active' ? 'badge-success' : 'badge-neutral' }}">{{ ucfirst($plan->status) }}</span></td>
                        <td class="table-cell text-right">
                            <a href="{{ route('memberships.plans.edit', $plan) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('memberships.plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Delete this plan?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="table-cell text-center py-12 text-slate-400">No plans found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($plans->hasPages())<div class="px-6 py-4 border-t">{{ $plans->links() }}</div>@endif
</div>
@endsection
