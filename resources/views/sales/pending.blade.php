@extends('layouts.admin')
@section('title', 'Pending Payments')
@section('page-title', 'Pending Sales Payments')
@section('breadcrumb', 'Sales / Pending')
@section('content')
@include('sales._table', ['sales' => $sales])
@endsection
