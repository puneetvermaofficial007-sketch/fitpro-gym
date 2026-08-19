@props(['enquiry' => null])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div><label class="form-label">Name *</label><input type="text" name="name" value="{{ old('name', $enquiry?->name) }}" class="form-input" required></div>
    <div><label class="form-label">Phone *</label><input type="text" name="phone" value="{{ old('phone', $enquiry?->phone) }}" class="form-input" required></div>
    <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $enquiry?->email) }}" class="form-input"></div>
    <div><label class="form-label">Interested Membership</label><input type="text" name="interested_membership" value="{{ old('interested_membership', $enquiry?->interested_membership) }}" class="form-input"></div>
    <div><label class="form-label">Source</label><input type="text" name="source" value="{{ old('source', $enquiry?->source) }}" class="form-input" placeholder="Walk-in, Instagram..."></div>
    <div><label class="form-label">Enquiry Date *</label><input type="date" name="enquiry_date" value="{{ old('enquiry_date', $enquiry?->enquiry_date?->format('Y-m-d') ?? today()->format('Y-m-d')) }}" class="form-input" required></div>
    <div><label class="form-label">Follow-up Date</label><input type="date" name="follow_up_date" value="{{ old('follow_up_date', $enquiry?->follow_up_date?->format('Y-m-d')) }}" class="form-input"></div>
    <div><label class="form-label">Status *</label>
        <select name="status" class="form-input">@foreach(['new','contacted','follow_up','converted','lost'] as $s)<option value="{{ $s }}" @selected(old('status', $enquiry?->status ?? 'new') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>@endforeach</select>
    </div>
    <div class="md:col-span-2"><label class="form-label">Notes</label><textarea name="notes" rows="3" class="form-input">{{ old('notes', $enquiry?->notes) }}</textarea></div>
</div>
