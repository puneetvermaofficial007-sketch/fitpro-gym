@extends('layouts.admin')
@section('title', 'Assign Diet Plan')
@section('page-title', 'Assign Diet Plan to Member')
@section('breadcrumb', 'Diet Plans / Assign')
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('diet-plans.assign.store') }}" class="card p-6">@csrf
        <div class="space-y-4">
            <div><label class="form-label">Member *</label><select name="member_id" class="form-input" required><option value="">Select</option>@foreach($members as $m)<option value="{{ $m->id }}">{{ $m->full_name }}</option>@endforeach</select></div>
            <div><label class="form-label">Diet Plan *</label><select name="diet_plan_id" class="form-input" required><option value="">Select</option>@foreach($dietPlans as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
            <div><label class="form-label">Start Date *</label><input type="date" name="start_date" value="{{ today()->format('Y-m-d') }}" class="form-input" required></div>
            <div><label class="form-label">End Date</label><input type="date" name="end_date" class="form-input"></div>
            <div><label class="form-label">Goal</label><input type="text" name="goal" class="form-input"></div>
            <div><label class="form-label">Trainer Notes</label><textarea name="trainer_notes" rows="3" class="form-input"></textarea></div>
        </div>
        <div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Assign Plan</button><a href="{{ route('diet-plans.assignments') }}" class="btn btn-secondary">Cancel</a></div>
    </form>
</div>
@endsection
