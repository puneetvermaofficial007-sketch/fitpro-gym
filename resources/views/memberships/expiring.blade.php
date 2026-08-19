@extends('layouts.admin')
@section('title', 'Expiring Soon')
@section('page-title', 'Memberships Expiring Soon')
@section('breadcrumb', 'Membership / Expiring Soon')
@section('content')
@include('memberships._member-list', ['members' => $members, 'showRenew' => true])
@endsection
