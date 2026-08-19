@extends('layouts.admin')
@section('title', 'Edit Enquiry')
@section('page-title', 'Edit Enquiry')
@section('breadcrumb', 'Enquiries / Edit')
@section('content')
<div class="max-w-3xl"><form method="POST" action="{{ route('enquiries.update', $enquiry) }}" class="card p-6">@csrf @method('PUT') @include('enquiries._form', ['enquiry' => $enquiry])<div class="mt-6 flex gap-3 border-t pt-6"><button class="btn btn-primary">Update</button><a href="{{ route('enquiries.index') }}" class="btn btn-secondary">Cancel</a></div></form></div>
@endsection
