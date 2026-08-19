<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        $query = Enquiry::query();

        $query = match ($filter) {
            'new' => $query->where('status', 'new'),
            'follow-ups' => $query->where(function ($q) {
                $q->where('status', 'follow_up')->orWhereDate('follow_up_date', today());
            }),
            'converted' => $query->where('status', 'converted'),
            'lost' => $query->where('status', 'lost'),
            default => $query,
        };

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->latest('enquiry_date')->paginate(10)->withQueryString();

        $stats = [
            'new' => Enquiry::where('status', 'new')->count(),
            'follow_ups_today' => Enquiry::where('follow_up_date', today())->count(),
            'converted' => Enquiry::where('status', 'converted')->count(),
        ];

        return view('enquiries.index', compact('enquiries', 'filter', 'search', 'stats'));
    }

    public function create()
    {
        return view('enquiries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'interested_membership' => ['nullable', 'string', 'max:100'],
            'source' => ['nullable', 'string', 'max:100'],
            'enquiry_date' => ['required', 'date'],
            'follow_up_date' => ['nullable', 'date'],
            'status' => ['required', 'in:new,contacted,follow_up,converted,lost'],
            'notes' => ['nullable', 'string'],
        ]);

        Enquiry::create($validated);

        return redirect()->route('enquiries.index')->with('success', 'Enquiry added successfully.');
    }

    public function edit(Enquiry $enquiry)
    {
        return view('enquiries.edit', compact('enquiry'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'interested_membership' => ['nullable', 'string', 'max:100'],
            'source' => ['nullable', 'string', 'max:100'],
            'enquiry_date' => ['required', 'date'],
            'follow_up_date' => ['nullable', 'date'],
            'status' => ['required', 'in:new,contacted,follow_up,converted,lost'],
            'notes' => ['nullable', 'string'],
        ]);

        $enquiry->update($validated);

        return redirect()->route('enquiries.index')->with('success', 'Enquiry updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('enquiries.index')->with('success', 'Enquiry deleted.');
    }
}
