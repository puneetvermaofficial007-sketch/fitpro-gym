@props(['invoice' => null, 'members', 'plans'])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label class="form-label">Member *</label>
        <select name="member_id" class="form-input" required>
            <option value="">Select member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}" @selected(old('member_id', $invoice?->member_id) == $member->id)>{{ $member->full_name }} ({{ $member->member_code }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Membership Plan</label>
        <select name="membership_plan_id" class="form-input">
            <option value="">None</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" @selected(old('membership_plan_id', $invoice?->membership_plan_id) == $plan->id)>{{ $plan->name }} - ₹{{ number_format($plan->price) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Amount (₹) *</label>
        <input type="number" name="amount" step="0.01" value="{{ old('amount', $invoice?->amount) }}" class="form-input" required>
    </div>
    <div>
        <label class="form-label">Discount (₹)</label>
        <input type="number" name="discount" step="0.01" value="{{ old('discount', $invoice?->discount ?? 0) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Tax (₹)</label>
        <input type="number" name="tax" step="0.01" value="{{ old('tax', $invoice?->tax ?? 0) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-input">
            <option value="">Select</option>
            @foreach(['cash', 'card', 'upi', 'bank_transfer'] as $m)
                <option value="{{ $m }}" @selected(old('payment_method', $invoice?->payment_method) === $m)>{{ ucfirst(str_replace('_', ' ', $m)) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Payment Status *</label>
        <select name="payment_status" class="form-input" required>
            @foreach(['paid', 'pending', 'overdue'] as $s)
                <option value="{{ $s }}" @selected(old('payment_status', $invoice?->payment_status ?? 'pending') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Invoice Date *</label>
        <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice?->invoice_date?->format('Y-m-d') ?? today()->format('Y-m-d')) }}" class="form-input" required>
    </div>
    <div>
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" value="{{ old('due_date', $invoice?->due_date?->format('Y-m-d')) }}" class="form-input">
    </div>
</div>
