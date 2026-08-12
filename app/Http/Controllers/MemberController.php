<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');

        $query = match ($filter) {
            'active' => Member::active()->with('membershipPlan'),
            'expired' => Member::expired()->with('membershipPlan'),
            'deleted' => Member::onlyTrashed()->with('membershipPlan'),
            default => Member::with('membershipPlan'),
        };

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        $members = $query->latest()->paginate(10)->withQueryString();
        $plans = MembershipPlan::active()->get();

        return view('members.index', compact('members', 'filter', 'plans', 'search', 'status', 'paymentStatus'));
    }

    public function create()
    {
        $plans = MembershipPlan::active()->get();

        return view('members.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'joining_date' => ['required', 'date'],
            'membership_start_date' => ['nullable', 'date'],
            'payment_status' => ['required', 'in:paid,pending,overdue'],
            'notes' => ['nullable', 'string'],
        ]);

        $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);
        $startDate = $validated['membership_start_date'] ?? $validated['joining_date'];
        $expiryDate = \Carbon\Carbon::parse($startDate)->addDays($plan->duration_days);

        Member::create([
            ...$validated,
            'member_code' => Member::generateMemberCode(),
            'membership_start_date' => $startDate,
            'membership_expiry_date' => $expiryDate,
            'status' => 'active',
        ]);

        return redirect()->route('members.index')->with('success', 'Member added successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['membershipPlan', 'invoices.membershipPlan', 'attendances', 'activeDietPlan.dietPlan']);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $plans = MembershipPlan::active()->get();

        return view('members.edit', compact('member', 'plans'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'joining_date' => ['required', 'date'],
            'membership_start_date' => ['nullable', 'date'],
            'membership_expiry_date' => ['nullable', 'date'],
            'payment_status' => ['required', 'in:paid,pending,overdue'],
            'status' => ['required', 'in:active,inactive,expired'],
            'notes' => ['nullable', 'string'],
        ]);

        $member->update($validated);

        return redirect()->route('members.show', $member)->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index', ['filter' => 'deleted'])
            ->with('success', 'Member moved to deleted members.');
    }

    public function restore(int $id)
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        $member->restore();

        return redirect()->route('members.index')
            ->with('success', 'Member restored successfully.');
    }

    public function toggleStatus(Member $member)
    {
        $member->update([
            'status' => $member->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Member status updated.');
    }
}
