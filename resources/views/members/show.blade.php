@extends('layouts.admin')

@section('title', $member->full_name)
@section('page-title', 'Member Profile')
@section('breadcrumb', 'Members / ' . $member->full_name)

@section('content')
<div class="mb-6 flex flex-wrap gap-3">
    <a href="{{ route('members.edit', $member) }}" class="btn btn-primary btn-sm">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Profile
    </a>
    <a href="{{ route('memberships.renewals') }}" class="btn btn-secondary btn-sm">Renew Membership</a>
    <a href="{{ route('invoices.create') }}" class="btn btn-secondary btn-sm">Add Payment</a>
    <a href="{{ route('diet-plans.assign') }}" class="btn btn-secondary btn-sm">Assign Diet Plan</a>
    <button onclick="window.print()" class="btn btn-secondary btn-sm">Print Profile</button>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Profile Card --}}
    <div class="card p-6 text-center lg:col-span-1">
        <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-primary-100 text-3xl font-bold text-primary-700">
            {{ $member->initials }}
        </div>
        <h2 class="text-xl font-bold text-slate-900">{{ $member->full_name }}</h2>
        <p class="text-sm text-slate-500 font-mono">{{ $member->member_code }}</p>
        <div class="mt-4 flex flex-wrap justify-center gap-2">
            <span class="badge {{ $member->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($member->status) }}</span>
            <span class="badge {{ $member->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($member->payment_status) }}</span>
        </div>
        <div class="mt-6 space-y-3 text-left text-sm">
            <div class="flex items-center gap-3 text-slate-600">
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $member->phone }}
            </div>
            @if($member->email)
                <div class="flex items-center gap-3 text-slate-600">
                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $member->email }}
                </div>
            @endif
            @if($member->address)
                <div class="flex items-start gap-3 text-slate-600">
                    <svg class="h-4 w-4 shrink-0 mt-0.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $member->address }}
                </div>
            @endif
        </div>
    </div>

    {{-- Details --}}
    <div class="space-y-6 lg:col-span-2">
        {{-- Personal Info --}}
        <div class="card p-6">
            <h3 class="mb-4 text-base font-semibold text-slate-900">Personal Information</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach([
                    ['Date of Birth', $member->date_of_birth?->format('M d, Y') ?? '-'],
                    ['Gender', ucfirst($member->gender ?? '-')],
                    ['Emergency Contact', $member->emergency_contact_name ?? '-'],
                    ['Emergency Phone', $member->emergency_contact_phone ?? '-'],
                ] as [$label, $value])
                    <div>
                        <p class="text-xs font-medium uppercase text-slate-400">{{ $label }}</p>
                        <p class="mt-1 text-sm text-slate-900">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Membership Info --}}
        <div class="card p-6">
            <h3 class="mb-4 text-base font-semibold text-slate-900">Membership Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['Plan', $member->membershipPlan?->name ?? '-'],
                    ['Joining Date', $member->joining_date->format('M d, Y')],
                    ['Start Date', $member->membership_start_date?->format('M d, Y') ?? '-'],
                    ['Expiry Date', $member->membership_expiry_date?->format('M d, Y') ?? '-'],
                    ['Days Remaining', $member->daysUntilExpiry() !== null ? max(0, $member->daysUntilExpiry()) . ' days' : '-'],
                    ['Plan Price', $member->membershipPlan ? '₹' . number_format($member->membershipPlan->price) : '-'],
                ] as [$label, $value])
                    <div>
                        <p class="text-xs font-medium uppercase text-slate-400">{{ $label }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-900">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Payment History --}}
        <div class="card overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Payment / Invoice History</h3>
            </div>
            @if($member->invoices->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-slate-400">No invoices yet</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header">Invoice #</th>
                                <th class="table-header">Amount</th>
                                <th class="table-header">Status</th>
                                <th class="table-header">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($member->invoices as $invoice)
                                <tr>
                                    <td class="table-cell font-mono text-xs">{{ $invoice->invoice_number }}</td>
                                    <td class="table-cell">₹{{ number_format($invoice->final_amount) }}</td>
                                    <td class="table-cell">
                                        <span class="badge {{ $invoice->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($invoice->payment_status) }}</span>
                                    </td>
                                    <td class="table-cell">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Attendance --}}
        <div class="card overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Recent Attendance</h3>
            </div>
            @if($member->attendances->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-slate-400">No attendance records</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header">Date</th>
                                <th class="table-header">Check In</th>
                                <th class="table-header">Check Out</th>
                                <th class="table-header">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($member->attendances->take(10) as $attendance)
                                <tr>
                                    <td class="table-cell">{{ $attendance->date->format('M d, Y') }}</td>
                                    <td class="table-cell">{{ $attendance->check_in ?? '-' }}</td>
                                    <td class="table-cell">{{ $attendance->check_out ?? '-' }}</td>
                                    <td class="table-cell">
                                        <span class="badge {{ $attendance->status === 'present' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($attendance->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($member->notes)
            <div class="card p-6">
                <h3 class="mb-2 text-base font-semibold text-slate-900">Notes</h3>
                <p class="text-sm text-slate-600">{{ $member->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
