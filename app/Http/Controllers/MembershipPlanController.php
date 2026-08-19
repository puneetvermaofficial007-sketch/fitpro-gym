<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = MembershipPlan::withCount('members')->latest()->paginate(10);

        return view('memberships.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('memberships.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        MembershipPlan::create($validated);

        return redirect()->route('memberships.plans.index')->with('success', 'Membership plan created.');
    }

    public function edit(MembershipPlan $plan)
    {
        return view('memberships.plans.edit', compact('plan'));
    }

    public function update(Request $request, MembershipPlan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $plan->update($validated);

        return redirect()->route('memberships.plans.index')->with('success', 'Membership plan updated.');
    }

    public function destroy(MembershipPlan $plan)
    {
        if ($plan->members()->exists()) {
            return back()->with('error', 'Cannot delete plan with active members.');
        }

        $plan->delete();

        return redirect()->route('memberships.plans.index')->with('success', 'Membership plan deleted.');
    }

    public function active()
    {
        $members = Member::active()->with('membershipPlan')->latest()->paginate(10);

        return view('memberships.active', compact('members'));
    }

    public function expired()
    {
        $members = Member::expired()->with('membershipPlan')->latest()->paginate(10);

        return view('memberships.expired', compact('members'));
    }

    public function expiring()
    {
        $members = Member::expiringSoon(30)->with('membershipPlan')->orderBy('membership_expiry_date')->paginate(10);

        return view('memberships.expiring', compact('members'));
    }

    public function renewals()
    {
        $members = Member::where('membership_expiry_date', '<=', now()->addDays(30))
            ->with('membershipPlan')
            ->orderBy('membership_expiry_date')
            ->paginate(10);

        return view('memberships.renewals', compact('members'));
    }

    public function renew(Request $request, Member $member)
    {
        $validated = $request->validate([
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
        ]);

        $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);
        $startDate = now();
        $member->update([
            'membership_plan_id' => $plan->id,
            'membership_start_date' => $startDate,
            'membership_expiry_date' => $startDate->copy()->addDays($plan->duration_days),
            'status' => 'active',
            'payment_status' => 'pending',
        ]);

        return back()->with('success', "Membership renewed for {$member->full_name}.");
    }
}
