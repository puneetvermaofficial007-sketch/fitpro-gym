@extends('layouts.admin')
@section('title', 'Create Diet Plan')
@section('page-title', 'Create Diet Plan')
@section('breadcrumb', 'Diet Plans / Create')
@section('content')
<div class="max-w-4xl"><form method="POST" action="{{ route('diet-plans.store') }}" class="card p-6">@csrf @include('diet-plans._form')<div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Save</button><a href="{{ route('diet-plans.index') }}" class="btn btn-secondary">Cancel</a></div></form></div>
@endsection
