@extends('layouts.admin')
@section('title', 'Add Enquiry')
@section('page-title', 'New Enquiry')
@section('breadcrumb', 'Enquiries / Add')
@section('content')
<div class="max-w-3xl"><form method="POST" action="{{ route('enquiries.store') }}" class="card p-6">@csrf @include('enquiries._form')<div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Save</button><a href="{{ route('enquiries.index') }}" class="btn btn-secondary">Cancel</a></div></form></div>
@endsection
