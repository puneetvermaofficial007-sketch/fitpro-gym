@extends('layouts.admin')
@section('title', 'Diet Plans')
@section('page-title', 'Diet Plans')
@section('breadcrumb', 'Diet Plan Management')
@section('content')
<div class="mb-6 flex justify-between"><form method="GET" class="flex gap-2"><input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="form-input"><button class="btn btn-primary btn-sm">Search</button></form><a href="{{ route('diet-plans.create') }}" class="btn btn-primary">Create Diet Plan</a></div>
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
    @forelse($dietPlans as $plan)
        <div class="card p-5">
            <div class="flex justify-between mb-3"><h3 class="font-semibold">{{ $plan->name }}</h3><span class="badge {{ $plan->status === 'active' ? 'badge-success' : 'badge-neutral' }}">{{ ucfirst($plan->status) }}</span></div>
            <p class="text-sm text-slate-500 mb-2">{{ $plan->goal ?? 'No goal set' }}</p>
            @if($plan->calories)<p class="text-sm mb-3"><span class="font-medium">{{ $plan->calories }}</span> kcal/day</p>@endif
            <div class="flex gap-2 mt-4">
                <a href="{{ route('diet-plans.edit', $plan) }}" class="btn btn-secondary btn-sm flex-1">Edit</a>
                <form method="POST" action="{{ route('diet-plans.destroy', $plan) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Delete</button></form>
            </div>
        </div>
    @empty
        <div class="col-span-full card p-12 text-center text-slate-400">No diet plans found</div>
    @endforelse
</div>
@if($dietPlans->hasPages())<div class="mt-4">{{ $dietPlans->links() }}</div>@endif
@endsection
