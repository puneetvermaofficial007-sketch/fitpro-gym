<div class="card overflow-hidden">
    <table class="w-full">
        <thead><tr><th class="table-header">Sale #</th><th class="table-header">Customer</th><th class="table-header">Total</th><th class="table-header">Status</th><th class="table-header text-right">Actions</th></tr></thead>
        <tbody class="divide-y">@forelse($sales as $sale)<tr class="hover:bg-slate-50"><td class="table-cell font-mono text-xs">{{ $sale->sale_number }}</td><td class="table-cell">{{ $sale->customerDisplayName() }}</td><td class="table-cell font-medium">₹{{ number_format($sale->total) }}</td><td class="table-cell"><span class="badge badge-warning">{{ ucfirst($sale->payment_status) }}</span></td><td class="table-cell text-right"><a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-sm">View</a></td></tr>@empty<tr><td colspan="5" class="table-cell text-center py-12 text-slate-400">No sales</td></tr>@endforelse</tbody>
    </table>
    @if($sales->hasPages())<div class="px-6 py-4 border-t">{{ $sales->links() }}</div>@endif
</div>
