<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Member;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        $query = Invoice::with(['member', 'membershipPlan']);

        $query = match ($filter) {
            'paid' => $query->where('payment_status', 'paid'),
            'pending' => $query->where('payment_status', 'pending'),
            'overdue' => $query->where('payment_status', 'overdue'),
            default => $query,
        };

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('member', fn ($m) => $m->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%"));
            });
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();
        $stats = [
            'total' => Invoice::count(),
            'paid' => Invoice::where('payment_status', 'paid')->sum('final_amount'),
            'pending' => Invoice::where('payment_status', 'pending')->sum('final_amount'),
            'overdue' => Invoice::where('payment_status', 'overdue')->sum('final_amount'),
        ];

        return view('invoices.index', compact('invoices', 'filter', 'search', 'stats'));
    }

    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $plans = MembershipPlan::active()->get();

        return view('invoices.create', compact('members', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'membership_plan_id' => ['nullable', 'exists:membership_plans,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', 'in:paid,pending,overdue'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
        ]);

        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;
        $finalAmount = $validated['amount'] - $discount + $tax;

        Invoice::create([
            ...$validated,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'discount' => $discount,
            'tax' => $tax,
            'final_amount' => max(0, $finalAmount),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['member', 'membershipPlan']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $members = Member::orderBy('first_name')->get();
        $plans = MembershipPlan::active()->get();

        return view('invoices.edit', compact('invoice', 'members', 'plans'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'membership_plan_id' => ['nullable', 'exists:membership_plans,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', 'in:paid,pending,overdue'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
        ]);

        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;

        $invoice->update([
            ...$validated,
            'discount' => $discount,
            'tax' => $tax,
            'final_amount' => max(0, $validated['amount'] - $discount + $tax),
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated.');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update(['payment_status' => 'paid']);

        return back()->with('success', 'Invoice marked as paid.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }
}
