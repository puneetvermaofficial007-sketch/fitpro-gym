@extends('layouts.admin')
@section('title', $data['title'])
@section('page-title', $data['title'])
@section('breadcrumb', 'Reports / ' . $data['title'])

@section('content')
<div class="mb-6 flex flex-wrap gap-4 justify-between items-center">
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="period" class="form-input w-auto">
            @foreach(['today'=>'Today','week'=>'This Week','month'=>'This Month','year'=>'This Year','custom'=>'Custom'] as $k=>$v)
                <option value="{{ $k }}" @selected(request('period','month')===$k)>{{ $v }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}" class="form-input w-auto">
        <input type="date" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}" class="form-input w-auto">
        <button class="btn btn-primary btn-sm">Apply Filter</button>
    </form>
    <div class="flex gap-2">
        <button onclick="window.print()" class="btn btn-secondary btn-sm">Print Report</button>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>

@if(isset($data['total']))
    <div class="card p-4 mb-6 inline-block"><p class="text-xs text-slate-500">Total Amount</p><p class="text-2xl font-bold text-emerald-600">₹{{ number_format($data['total']) }}</p></div>
@endif

<div class="card overflow-hidden" id="report-print">
    <div class="border-b px-6 py-4"><h3 class="font-semibold">{{ $data['title'] }}</h3><p class="text-xs text-slate-500">{{ $dateFrom->format('M d, Y') }} — {{ $dateTo->format('M d, Y') }}</p></div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr>@foreach($data['columns'] as $col)<th class="table-header">{{ $col }}</th>@endforeach</tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($data['rows'] as $row)
                    <tr class="hover:bg-slate-50">
                        @if($type === 'members')
                            <td class="table-cell">{{ $row->full_name }}</td><td class="table-cell">{{ $row->phone }}</td><td class="table-cell">{{ $row->membershipPlan?->name ?? '-' }}</td><td class="table-cell">{{ $row->joining_date->format('M d, Y') }}</td><td class="table-cell">{{ ucfirst($row->status) }}</td>
                        @elseif($type === 'memberships')
                            <td class="table-cell">{{ $row->full_name }}</td><td class="table-cell">{{ $row->membershipPlan?->name ?? '-' }}</td><td class="table-cell">{{ $row->membership_start_date?->format('M d, Y') ?? '-' }}</td><td class="table-cell">{{ $row->membership_expiry_date?->format('M d, Y') ?? '-' }}</td><td class="table-cell">{{ ucfirst($row->status) }}</td>
                        @elseif($type === 'revenue')
                            @if(!empty($data['is_summary']))
                                <td class="table-cell font-medium">{{ $row->source }}</td><td class="table-cell font-bold">₹{{ number_format($row->amount) }}</td>
                            @else
                                <td class="table-cell font-mono text-xs">{{ $row->invoice_number }}</td><td class="table-cell">{{ $row->member?->full_name }}</td><td class="table-cell">₹{{ number_format($row->final_amount) }}</td><td class="table-cell">{{ ucfirst($row->payment_status) }}</td><td class="table-cell">{{ $row->invoice_date->format('M d, Y') }}</td>
                            @endif
                        @elseif($type === 'attendance')
                            <td class="table-cell">{{ $row->member?->full_name }}</td><td class="table-cell">{{ $row->date->format('M d, Y') }}</td><td class="table-cell">{{ $row->check_in ? substr($row->check_in,0,5) : '-' }}</td><td class="table-cell">{{ ucfirst($row->status) }}</td>
                        @elseif($type === 'expired-memberships')
                            <td class="table-cell">{{ $row->full_name }}</td><td class="table-cell">{{ $row->membershipPlan?->name ?? '-' }}</td><td class="table-cell">{{ $row->membership_expiry_date?->format('M d, Y') ?? '-' }}</td><td class="table-cell">{{ $row->phone }}</td><td class="table-cell">{{ ucfirst($row->status) }}</td>
                        @elseif($type === 'pending-payments')
                            <td class="table-cell font-mono text-xs">{{ $row->invoice_number }}</td><td class="table-cell">{{ $row->member?->full_name }}</td><td class="table-cell">₹{{ number_format($row->final_amount) }}</td><td class="table-cell">{{ ucfirst($row->payment_status) }}</td><td class="table-cell">{{ $row->due_date?->format('M d, Y') ?? '-' }}</td>
                        @elseif($type === 'diet-plans')
                            <td class="table-cell">{{ $row->member?->full_name }}</td><td class="table-cell">{{ $row->dietPlan?->name }}</td><td class="table-cell">{{ $row->start_date->format('M d, Y') }}</td><td class="table-cell">{{ $row->end_date?->format('M d, Y') ?? '-' }}</td><td class="table-cell">{{ $row->goal ?? '-' }}</td>
                        @elseif($type === 'inventory-stock')
                            <td class="table-cell font-medium">{{ $row->name }}</td><td class="table-cell font-mono text-xs">{{ $row->sku }}</td><td class="table-cell">{{ $row->category?->name ?? '-' }}</td><td class="table-cell">{{ $row->current_stock }}</td><td class="table-cell">{{ $row->minimum_stock_level }}</td><td class="table-cell">₹{{ number_format($row->current_stock * $row->purchase_price) }}</td><td class="table-cell"><x-stock-badge :product="$row" /></td>
                        @elseif($type === 'low-stock')
                            <td class="table-cell font-medium">{{ $row->name }}</td><td class="table-cell">{{ $row->current_stock }}</td><td class="table-cell">{{ $row->minimum_stock_level }}</td><td class="table-cell"><x-stock-badge :product="$row" /></td>
                        @elseif($type === 'product-sales')
                            <td class="table-cell font-mono text-xs">{{ $row->sale_number }}</td><td class="table-cell">{{ $row->customerDisplayName() }}</td><td class="table-cell">{{ $row->items->sum('quantity') }}</td><td class="table-cell font-bold">₹{{ number_format($row->total) }}</td><td class="table-cell">{{ ucfirst($row->payment_method) }}</td><td class="table-cell">{{ $row->sale_date->format('M d, Y') }}</td>
                        @elseif($type === 'product-profit')
                            <td class="table-cell font-mono text-xs">{{ $row->sale_number }}</td><td class="table-cell">{{ $row->customerDisplayName() }}</td><td class="table-cell">₹{{ number_format($row->total) }}</td><td class="table-cell font-bold text-emerald-600">₹{{ number_format($row->profit) }}</td><td class="table-cell">{{ $row->sale_date->format('M d, Y') }}</td>
                        @elseif($type === 'purchases')
                            <td class="table-cell font-mono text-xs">{{ $row->purchase_number }}</td><td class="table-cell">{{ $row->supplier?->name ?? '-' }}</td><td class="table-cell">{{ $row->items->sum('quantity') }}</td><td class="table-cell font-bold">₹{{ number_format($row->total_amount) }}</td><td class="table-cell">{{ $row->purchase_date->format('M d, Y') }}</td>
                        @elseif($type === 'stock-movement')
                            <td class="table-cell">{{ $row->created_at->format('M d, Y H:i') }}</td><td class="table-cell">{{ $row->product?->name ?? '-' }}</td><td class="table-cell">{{ ucfirst($row->type) }}</td><td class="table-cell">{{ $row->quantity > 0 ? '+' : '' }}{{ $row->quantity }}</td><td class="table-cell">{{ $row->previous_stock }}</td><td class="table-cell">{{ $row->new_stock }}</td><td class="table-cell text-xs">{{ $row->referenceLabel() ?? '-' }}</td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ count($data['columns']) }}" class="table-cell text-center py-12 text-slate-400">No data for selected period</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t text-xs text-slate-400">{{ $data['rows']->count() }} record(s)</div>
</div>
@endsection
