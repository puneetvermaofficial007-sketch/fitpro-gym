@extends('layouts.admin')
@section('title', 'Active Memberships')
@section('page-title', 'Active Memberships')
@section('breadcrumb', 'Membership / Active')
@section('content')
@include('memberships._member-list', ['members' => $members])
@endsection
