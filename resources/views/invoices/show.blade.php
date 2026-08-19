@extends('layouts.admin')
@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice Details')
@section('breadcrumb', 'Invoices / ' . $invoice->invoice_number)

@section('content')
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary btn-sm">Edit</a>
    <button onclick="window.print()" class="btn btn-secondary btn-sm">Print</button>
    @if($invoice->payment_status !== 'paid')
        <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}">@csrf @method('PATCH')<button class="btn btn-success btn-sm">Mark as Paid</button></form>
    @endif
</div>
<div class="card max-w-3xl p-8" id="invoice-print">
    <div class="flex justify-between border-b pb-6 mb-6">
        <div><h2 class="text-2xl font-bold text-slate-900">FitPro Gym</h2><p class="text-sm text-slate-500">Invoice</p></div>
        <div class="text-right"><p class="font-mono font-bold">{{ $invoice->invoice_number }}</p><p class="text-sm text-slate-500">{{ $invoice->invoice_date->format('M d, Y') }}</p></div>
    </div>
    <div class="grid grid-cols-2 gap-6 mb-8">
        <div><p class="text-xs uppercase text-slate-400 mb-1">Bill To</p><p class="font-medium">{{ $invoice->member->full_name }}</p><p class="text-sm text-slate-500">{{ $invoice->member->phone }}</p></div>
        <div><p class="text-xs uppercase text-slate-400 mb-1">Plan</p><p>{{ $invoice->membershipPlan?->name ?? 'General' }}</p></div>
    </div>
    <table class="w-full mb-6">
        <tr class="border-b"><td class="py-2">Amount</td><td class="py-2 text-right">₹{{ number_format($invoice->amount) }}</td></tr>
        <tr class="border-b"><td class="py-2">Discount</td><td class="py-2 text-right">-₹{{ number_format($invoice->discount) }}</td></tr>
        <tr class="border-b"><td class="py-2">Tax</td><td class="py-2 text-right">₹{{ number_format($invoice->tax) }}</td></tr>
        <tr><td class="py-3 font-bold">Final Amount</td><td class="py-3 text-right font-bold text-lg">₹{{ number_format($invoice->final_amount) }}</td></tr>
    </table>
    <div class="flex justify-between text-sm">
        <span class="badge {{ $invoice->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($invoice->payment_status) }}</span>
        @if($invoice->due_date)<span class="text-slate-500">Due: {{ $invoice->due_date->format('M d, Y') }}</span>@endif
    </div>
</div>
@endsection
