@props(['members', 'showRenew' => false])

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header">Member</th>
                    <th class="table-header">Plan</th>
                    <th class="table-header">Expiry</th>
                    <th class="table-header">Days Left</th>
                    <th class="table-header">Status</th>
                    @if($showRenew)<th class="table-header text-right">Action</th>@endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($members as $member)
                    @php $days = $member->daysUntilExpiry(); @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="table-cell">
                            <a href="{{ route('members.show', $member) }}" class="font-medium text-primary-600 hover:underline">{{ $member->full_name }}</a>
                            <p class="text-xs text-slate-500">{{ $member->phone }}</p>
                        </td>
                        <td class="table-cell">{{ $member->membershipPlan?->name ?? '-' }}</td>
                        <td class="table-cell">{{ $member->membership_expiry_date?->format('M d, Y') ?? '-' }}</td>
                        <td class="table-cell">
                            @if($days !== null)
                                <span class="badge {{ $days <= 7 ? 'badge-warning' : ($days < 0 ? 'badge-danger' : 'badge-success') }}">
                                    {{ $days < 0 ? 'Expired' : $days . ' days' }}
                                </span>
                            @else - @endif
                        </td>
                        <td class="table-cell"><span class="badge {{ $member->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($member->status) }}</span></td>
                        @if($showRenew)
                            <td class="table-cell text-right">
                                <form method="POST" action="{{ route('memberships.renew', $member) }}" class="flex items-center justify-end gap-2">
                                    @csrf
                                    <select name="membership_plan_id" class="form-input text-xs py-1 w-32" required>
                                        @foreach(\App\Models\MembershipPlan::active()->get() as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Renew</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ $showRenew ? 6 : 5 }}" class="table-cell text-center py-12 text-slate-400">No members found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())<div class="px-6 py-4 border-t">{{ $members->links() }}</div>@endif
</div>
