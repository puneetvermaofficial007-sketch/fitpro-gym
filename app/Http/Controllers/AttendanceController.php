<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'today');
        $date = $request->get('date', today()->format('Y-m-d'));
        $search = $request->get('search');

        $query = Attendance::with('member');

        if ($filter === 'today') {
            $query->where('date', today());
        } elseif ($filter === 'history') {
            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }
        } elseif ($filter === 'member' && $request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        } elseif ($filter === 'monthly') {
            $query->whereMonth('date', $request->get('month', now()->month))
                ->whereYear('date', $request->get('year', now()->year));
        }

        if ($search) {
            $query->whereHas('member', fn ($q) => $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('member_code', 'like', "%{$search}%"));
        }

        $attendances = $query->latest('date')->paginate(15)->withQueryString();

        $stats = [
            'present_today' => Attendance::where('date', today())->where('status', 'present')->count(),
            'absent_today' => Attendance::where('date', today())->where('status', 'absent')->count(),
            'active_members' => Member::active()->count(),
            'percentage' => $this->attendancePercentage(),
        ];

        $members = Member::active()->orderBy('first_name')->get();

        return view('attendance.index', compact('attendances', 'filter', 'date', 'search', 'stats', 'members'));
    }

    public function markForm()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $todayMarked = Attendance::where('date', today())->pluck('member_id')->toArray();

        return view('attendance.mark', compact('members', 'todayMarked'));
    }

    public function mark(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'status' => ['required', 'in:present,absent'],
            'check_in' => ['nullable', 'date_format:H:i'],
        ]);

        $member = Member::findOrFail($validated['member_id']);

        Attendance::updateOrCreate(
            ['member_id' => $member->id, 'date' => today()],
            [
                'status' => $validated['status'],
                'check_in' => $validated['check_in'] ?? now()->format('H:i:s'),
            ]
        );

        if ($validated['status'] === 'present') {
            $member->update(['checked_in_at' => now()]);
        }

        return back()->with('success', 'Attendance marked successfully.');
    }

    public function checkout(Member $member)
    {
        $attendance = Attendance::where('member_id', $member->id)->where('date', today())->first();
        if ($attendance) {
            $attendance->update(['check_out' => now()->format('H:i:s')]);
        }
        $member->update(['checked_in_at' => null]);

        return back()->with('success', "{$member->full_name} checked out.");
    }

    private function attendancePercentage(): float
    {
        $active = Member::active()->count();
        if ($active === 0) {
            return 0;
        }

        $present = Attendance::where('date', today())->where('status', 'present')->count();

        return round(($present / $active) * 100, 1);
    }
}
