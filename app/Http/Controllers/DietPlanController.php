<?php

namespace App\Http\Controllers;

use App\Models\DietPlan;
use App\Models\Member;
use App\Models\MemberDietPlan;
use Illuminate\Http\Request;

class DietPlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = DietPlan::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('goal', 'like', "%{$search}%");
        }

        $dietPlans = $query->latest()->paginate(10)->withQueryString();

        return view('diet-plans.index', compact('dietPlans', 'search'));
    }

    public function create()
    {
        return view('diet-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'goal' => ['nullable', 'string', 'max:100'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'breakfast' => ['nullable', 'string'],
            'lunch' => ['nullable', 'string'],
            'dinner' => ['nullable', 'string'],
            'snacks' => ['nullable', 'string'],
            'supplements' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DietPlan::create($validated);

        return redirect()->route('diet-plans.index')->with('success', 'Diet plan created.');
    }

    public function edit(DietPlan $dietPlan)
    {
        return view('diet-plans.edit', compact('dietPlan'));
    }

    public function update(Request $request, DietPlan $dietPlan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'goal' => ['nullable', 'string', 'max:100'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'breakfast' => ['nullable', 'string'],
            'lunch' => ['nullable', 'string'],
            'dinner' => ['nullable', 'string'],
            'snacks' => ['nullable', 'string'],
            'supplements' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $dietPlan->update($validated);

        return redirect()->route('diet-plans.index')->with('success', 'Diet plan updated.');
    }

    public function destroy(DietPlan $dietPlan)
    {
        $dietPlan->delete();

        return redirect()->route('diet-plans.index')->with('success', 'Diet plan deleted.');
    }

    public function assignments()
    {
        $assignments = MemberDietPlan::with(['member', 'dietPlan'])->latest()->paginate(10);

        return view('diet-plans.assignments.index', compact('assignments'));
    }

    public function assignForm()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $dietPlans = DietPlan::where('status', 'active')->get();

        return view('diet-plans.assignments.create', compact('members', 'dietPlans'));
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'diet_plan_id' => ['required', 'exists:diet_plans,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'goal' => ['nullable', 'string', 'max:100'],
            'trainer_notes' => ['nullable', 'string'],
        ]);

        MemberDietPlan::where('member_id', $validated['member_id'])->update(['is_active' => false]);

        MemberDietPlan::create([...$validated, 'is_active' => true]);

        return redirect()->route('diet-plans.assignments')->with('success', 'Diet plan assigned successfully.');
    }
}
