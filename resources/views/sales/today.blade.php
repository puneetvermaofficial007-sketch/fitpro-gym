@extends('layouts.admin')
@section('title', "Today's Sales")
@section('page-title', "Today's Sales")
@section('breadcrumb', 'Sales / Today')
@section('content')
<div class="mb-6 grid grid-cols-3 gap-4"><div class="card p-4"><p class="text-xs text-slate-500">Sales Today</p><p class="text-2xl font-bold">{{ $stats['count'] }}</p></div><div class="card p-4"><p class="text-xs text-slate-500">Revenue</p><p class="text-2xl font-bold text-emerald-600">₹{{ number_format($stats['revenue']) }}</p></div><div class="card p-4"><p class="text-xs text-slate-500">Profit</p><p class="text-2xl font-bold text-primary-600">₹{{ number_format($stats['profit']) }}</p></div></div>
@include('sales._table', ['sales' => $sales])
@endsection
