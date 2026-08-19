@extends('layouts.admin')
@section('title', 'Edit Diet Plan')
@section('page-title', 'Edit Diet Plan')
@section('breadcrumb', 'Diet Plans / Edit')
@section('content')
<div class="max-w-4xl"><form method="POST" action="{{ route('diet-plans.update', $dietPlan) }}" class="card p-6">@csrf @method('PUT') @include('diet-plans._form', ['dietPlan' => $dietPlan])<div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Update</button><a href="{{ route('diet-plans.index') }}" class="btn btn-secondary">Cancel</a></div></form></div>
@endsection
