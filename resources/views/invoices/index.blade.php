@extends('layouts.admin')
@section('title', 'Invoices')
@section('page-title', match($filter) { 'paid' => 'Paid Invoices', 'pending' => 'Pending Payments', 'overdue' => 'Overdue Payments', default => 'All Invoices' })
@section('breadcrumb', 'Invoice Management')

@section('content')
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
    @foreach([['Total Invoices', $stats['total'], 'primary'], ['Collected', '₹'.number_format($stats['paid']), 'emerald'], ['Pending', '₹'.number_format($stats['pending']), 'amber'], ['Overdue', '₹'.number_format($stats['overdue']), 'red']] as [$label, $val, $color])
        <div class="card p-4"><p class="text-xs text-slate-500">{{ $label }}</p><p class="text-xl font-bold text-slate-900">{{ $val }}</p></div>
    @endforeach
</div>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:justify-between">
    <form method="GET" class="flex gap-2 flex-1 max-w-md">
        @if($filter !== 'all')<input type="hidden" name="filter" value="{{ $filter }}">@endif
        <input type="text" name="search" value="{{ $search }}" placeholder="Search invoice or member..." class="form-input">
        <button class="btn btn-primary">Search</button>
    </form>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
</div>
<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr>
            <th class="table-header">Invoice #</th><th class="table-header">Member</th><th class="table-header">Plan</th>
            <th class="table-header">Amount</th><th class="table-header">Status</th><th class="table-header">Date</th><th class="table-header text-right">Actions</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($invoices as $invoice)
                <tr class="hover:bg-slate-50">
                    <td class="table-cell font-mono text-xs">{{ $invoice->invoice_number }}</td>
                    <td class="table-cell">{{ $invoice->member?->full_name }}</td>
                    <td class="table-cell">{{ $invoice->membershipPlan?->name ?? '-' }}</td>
                    <td class="table-cell font-medium">₹{{ number_format($invoice->final_amount) }}</td>
                    <td class="table-cell"><span class="badge {{ $invoice->payment_status === 'paid' ? 'badge-success' : ($invoice->payment_status === 'overdue' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($invoice->payment_status) }}</span></td>
                    <td class="table-cell">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    <td class="table-cell text-right">
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary btn-sm">View</a>
                        @if($invoice->payment_status !== 'paid')
                            <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" class="inline">@csrf @method('PATCH')<button class="btn btn-success btn-sm">Mark Paid</button></form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="table-cell text-center py-12 text-slate-400">No invoices found</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($invoices->hasPages())<div class="px-6 py-4 border-t">{{ $invoices->links() }}</div>@endif
</div>
@endsection
