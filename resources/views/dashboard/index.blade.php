@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')
@section('breadcrumb', 'Welcome back, ' . auth()->user()->name)

@section('content')
{{-- Quick Actions --}}
<div class="mb-6 flex flex-wrap gap-2">
    @foreach([
        ['Add Member', route('members.create'), 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
        ['Create Invoice', '#', 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
        ['Mark Attendance', '#', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['Add Enquiry', '#', 'M12 4v16m8-8H4'],
        ['Create Diet Plan', '#', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ['Renew Membership', '#', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
    ] as [$label, $url, $icon])
        <a href="{{ $url }}" class="btn btn-secondary btn-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Stats Grid --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-stat-card label="Total Members" :value="$stats['total_members']" trend="+12%" :trendUp="true" color="primary"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z\'/></svg>'" />
    <x-stat-card label="Active Members" :value="$stats['active_members']" trend="+5%" :trendUp="true" color="emerald"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
    <x-stat-card label="New Members" :value="$stats['new_members']" trend="This month" color="blue"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z\'/></svg>'" />
    <x-stat-card label="Expired Memberships" :value="$stats['expired_memberships']" trend="-2" :trendUp="false" color="red"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
    <x-stat-card label="Expiring Soon" :value="$stats['expiring_soon']" trend="7 days" color="amber"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
    <x-stat-card label="Today's Attendance" :value="$stats['today_attendance']" trend="+8%" :trendUp="true" color="purple"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4\'/></svg>'" />
    <x-stat-card label="Today's Revenue" :value="'₹' . number_format($stats['today_revenue'])" trend="+15%" :trendUp="true" color="emerald"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
    <x-stat-card label="Pending Payments" :value="'₹' . number_format($stats['pending_payments'])" trend="3 invoices" color="amber"
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z\'/></svg>'" />
</div>

{{-- Charts Row --}}
<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="card p-6">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-900">Revenue Collection</h3>
            <select class="rounded-lg border border-slate-200 px-2 py-1 text-xs">
                <option>Weekly</option>
                <option>Monthly</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="rounded-lg bg-emerald-50 p-3">
                <p class="text-xs text-emerald-600">Today</p>
                <p class="text-lg font-bold text-emerald-800">₹{{ number_format($stats['today_revenue']) }}</p>
            </div>
            <div class="rounded-lg bg-blue-50 p-3">
                <p class="text-xs text-blue-600">Pending</p>
                <p class="text-lg font-bold text-blue-800">₹{{ number_format($stats['pending_payments']) }}</p>
            </div>
        </div>
        <canvas id="revenueChart" height="200"></canvas>
    </div>
    <div class="card p-6">
        <h3 class="mb-4 text-base font-semibold text-slate-900">Attendance Overview</h3>
        <canvas id="attendanceChart" height="200"></canvas>
    </div>
</div>

{{-- Live Active Members --}}
<div class="card mb-6">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Live Active Members</h3>
            <p class="text-xs text-slate-500">Members currently inside the gym</p>
        </div>
        <span class="badge badge-success">{{ $liveActiveMembers->count() }} Active</span>
    </div>
    @if($liveActiveMembers->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
            <svg class="mb-3 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-sm">No members currently checked in</p>
        </div>
    @else
        <div class="divide-y divide-slate-100">
            @foreach($liveActiveMembers as $member)
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">
                            {{ $member->initials }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">{{ $member->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $member->membershipPlan?->name ?? 'N/A' }} · Checked in {{ $member->checked_in_at->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-emerald-600">{{ $member->duration_inside }}</p>
                            <p class="text-xs text-slate-400">Duration</p>
                        </div>
                        <span class="badge badge-success">Active</span>
                        <form method="POST" action="{{ route('dashboard.checkout', $member) }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Check Out</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Recent Members & Expiring --}}
<div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
    {{-- Recent Members Table --}}
    <div class="card xl:col-span-2 overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h3 class="text-base font-semibold text-slate-900">Recent Members</h3>
            <a href="{{ route('members.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="table-header">Member</th>
                        <th class="table-header">Membership</th>
                        <th class="table-header hidden md:table-cell">Joining</th>
                        <th class="table-header hidden lg:table-cell">Expiry</th>
                        <th class="table-header">Payment</th>
                        <th class="table-header">Status</th>
                        <th class="table-header">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentMembers as $member)
                        <tr class="hover:bg-slate-50">
                            <td class="table-cell">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">{{ $member->initials }}</div>
                                    <span class="font-medium">{{ $member->full_name }}</span>
                                </div>
                            </td>
                            <td class="table-cell">{{ $member->membershipPlan?->name ?? '-' }}</td>
                            <td class="table-cell hidden md:table-cell">{{ $member->joining_date->format('M d, Y') }}</td>
                            <td class="table-cell hidden lg:table-cell">{{ $member->membership_expiry_date?->format('M d, Y') ?? '-' }}</td>
                            <td class="table-cell">
                                <span class="badge {{ $member->payment_status === 'paid' ? 'badge-success' : ($member->payment_status === 'overdue' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ ucfirst($member->payment_status) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                <span class="badge {{ $member->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($member->status) }}</span>
                            </td>
                            <td class="table-cell">
                                <div class="flex gap-1">
                                    <a href="{{ route('members.show', $member) }}" class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-primary-600" title="View">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}" class="rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-amber-600" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Expiring Memberships --}}
    <div class="card overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="text-base font-semibold text-slate-900">Expiring Soon</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($expiringMemberships as $member)
                @php
                    $days = $member->daysUntilExpiry();
                    $badgeClass = $days < 0 ? 'badge-danger' : ($days === 0 ? 'badge-danger' : ($days <= 7 ? 'badge-warning' : 'badge-success'));
                    $label = $days < 0 ? 'Expired' : ($days === 0 ? 'Today' : "{$days} days left");
                @endphp
                <div class="px-6 py-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-medium text-slate-900">{{ $member->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $member->membershipPlan?->name }}</p>
                        </div>
                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-400">{{ $member->phone }}</p>
                        <button class="btn btn-primary btn-sm">Renew</button>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-slate-400">No expiring memberships</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Enquiries --}}
<div class="card">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <h3 class="text-base font-semibold text-slate-900">Recent Enquiries</h3>
        <span class="badge badge-info">{{ $recentEnquiries->where('status', 'new')->count() }} New</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header">Name</th>
                    <th class="table-header">Phone</th>
                    <th class="table-header hidden sm:table-cell">Interest</th>
                    <th class="table-header">Status</th>
                    <th class="table-header hidden md:table-cell">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentEnquiries as $enquiry)
                    <tr class="hover:bg-slate-50">
                        <td class="table-cell font-medium">{{ $enquiry->name }}</td>
                        <td class="table-cell">{{ $enquiry->phone }}</td>
                        <td class="table-cell hidden sm:table-cell">{{ $enquiry->interested_membership }}</td>
                        <td class="table-cell">
                            <span class="badge {{ match($enquiry->status) { 'new' => 'badge-info', 'converted' => 'badge-success', 'lost' => 'badge-danger', default => 'badge-warning' } }}">
                                {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                            </span>
                        </td>
                        <td class="table-cell hidden md:table-cell">{{ $enquiry->enquiry_date->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="table-cell text-center text-slate-400 py-8">No enquiries yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueData['labels']),
            datasets: [{
                label: 'Revenue (₹)',
                data: @json($revenueData['values']),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: @json($attendanceData['labels']),
            datasets: [
                { label: 'Present', data: @json($attendanceData['present']), backgroundColor: '#10b981' },
                { label: 'Absent', data: @json($attendanceData['absent']), backgroundColor: '#ef4444' },
            ]
        },
        options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } } }
    });
});
</script>
@endpush
