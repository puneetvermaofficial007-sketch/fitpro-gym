@extends('layouts.admin')
@section('title', 'Renewals')
@section('page-title', 'Membership Renewals')
@section('breadcrumb', 'Membership / Renewals')
@section('content')
@include('memberships._member-list', ['members' => $members, 'showRenew' => true])
@endsection
