@props(['member' => null, 'plans'])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label class="form-label">First Name *</label>
        <input type="text" name="first_name" value="{{ old('first_name', $member?->first_name) }}" class="form-input @error('first_name') border-red-500 @enderror" required>
        @error('first_name')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Last Name *</label>
        <input type="text" name="last_name" value="{{ old('last_name', $member?->last_name) }}" class="form-input @error('last_name') border-red-500 @enderror" required>
        @error('last_name')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $member?->email) }}" class="form-input @error('email') border-red-500 @enderror">
        @error('email')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Phone *</label>
        <input type="text" name="phone" value="{{ old('phone', $member?->phone) }}" class="form-input @error('phone') border-red-500 @enderror" required>
        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member?->date_of_birth?->format('Y-m-d')) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Gender</label>
        <select name="gender" class="form-input">
            <option value="">Select gender</option>
            @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                <option value="{{ $val }}" @selected(old('gender', $member?->gender) === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="form-label">Address</label>
        <textarea name="address" rows="2" class="form-input">{{ old('address', $member?->address) }}</textarea>
    </div>
    <div>
        <label class="form-label">Emergency Contact Name</label>
        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $member?->emergency_contact_name) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Emergency Contact Phone</label>
        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $member?->emergency_contact_phone) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Membership Plan *</label>
        <select name="membership_plan_id" class="form-input @error('membership_plan_id') border-red-500 @enderror" required>
            <option value="">Select plan</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" @selected(old('membership_plan_id', $member?->membership_plan_id) == $plan->id)>
                    {{ $plan->name }} - ₹{{ number_format($plan->price) }} ({{ $plan->duration_days }} days)
                </option>
            @endforeach
        </select>
        @error('membership_plan_id')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Joining Date *</label>
        <input type="date" name="joining_date" value="{{ old('joining_date', $member?->joining_date?->format('Y-m-d') ?? today()->format('Y-m-d')) }}" class="form-input" required>
    </div>
    <div>
        <label class="form-label">Membership Start Date</label>
        <input type="date" name="membership_start_date" value="{{ old('membership_start_date', $member?->membership_start_date?->format('Y-m-d')) }}" class="form-input">
    </div>
    @if($member)
        <div>
            <label class="form-label">Membership Expiry Date</label>
            <input type="date" name="membership_expiry_date" value="{{ old('membership_expiry_date', $member->membership_expiry_date?->format('Y-m-d')) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                @foreach(['active', 'inactive', 'expired'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $member->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div>
        <label class="form-label">Payment Status *</label>
        <select name="payment_status" class="form-input" required>
            @foreach(['paid', 'pending', 'overdue'] as $status)
                <option value="{{ $status }}" @selected(old('payment_status', $member?->payment_status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $member?->notes) }}</textarea>
    </div>
</div>
