@extends('layouts.admin')
@section('title', 'Expired Memberships')
@section('page-title', 'Expired Memberships')
@section('breadcrumb', 'Membership / Expired')
@section('content')
@include('memberships._member-list', ['members' => $members, 'showRenew' => true])
@endsection
