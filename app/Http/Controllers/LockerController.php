<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LockerController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = match ($filter) {
            'available' => Locker::available(),
            'assigned' => Locker::assigned(),
            'maintenance' => Locker::where('status', 'maintenance'),
            default => Locker::query(),
        };

        $lockers = $query->with('member')->orderBy('locker_code')->paginate(15)->withQueryString();

        $counts = [
            'all' => Locker::count(),
            'available' => Locker::available()->count(),
            'assigned' => Locker::assigned()->count(),
            'maintenance' => Locker::where('status', 'maintenance')->count(),
        ];

        return view('lockers.index', compact('lockers', 'filter', 'counts'));
    }

    public function create()
    {
        return view('lockers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'locker_code' => ['required', 'string', 'max:50', 'unique:lockers,locker_code'],
            'status' => ['required', 'in:available,maintenance'],
            'notes' => ['nullable', 'string'],
        ]);

        Locker::create($validated);

        return redirect()->route('lockers.index')->with('success', 'Locker added successfully.');
    }

    public function edit(Locker $locker)
    {
        $locker->load('member');

        return view('lockers.edit', compact('locker'));
    }

    public function update(Request $request, Locker $locker)
    {
        $validated = $request->validate([
            'locker_code' => ['required', 'string', 'max:50', Rule::unique('lockers', 'locker_code')->ignore($locker->id)],
            'status' => ['required', Rule::in($locker->isAssigned() ? ['assigned'] : ['available', 'maintenance'])],
            'notes' => ['nullable', 'string'],
        ]);

        if ($locker->isAssigned()) {
            $validated['status'] = 'assigned';
        }

        $locker->update($validated);

        return redirect()->route('lockers.index')->with('success', 'Locker updated successfully.');
    }

    public function destroy(Locker $locker)
    {
        if ($locker->isAssigned() || $locker->member()->exists()) {
            return back()->with('error', 'Assigned lockers cannot be deleted. Unassign the locker first.');
        }

        $locker->delete();

        return redirect()->route('lockers.index')->with('success', 'Locker deleted successfully.');
    }

    public function assignForm(Locker $locker)
    {
        abort_unless($locker->isAvailable(), 403, 'Only available lockers can be assigned.');

        $members = Member::query()
            ->whereNull('locker_id')
            ->orderBy('first_name')
            ->get();

        return view('lockers.assign', compact('locker', 'members'));
    }

    public function assign(Request $request, Locker $locker)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $locker->assignTo($member);

        return redirect()->route('lockers.index', ['filter' => 'assigned'])
            ->with('success', "Locker {$locker->locker_code} assigned to {$member->full_name}.");
    }

    public function unassign(Locker $locker)
    {
        $name = $locker->member?->full_name;
        $locker->release();

        return redirect()->route('lockers.index', ['filter' => 'available'])
            ->with('success', $name
                ? "Locker {$locker->locker_code} unassigned from {$name}."
                : "Locker {$locker->locker_code} is now available.");
    }
}
