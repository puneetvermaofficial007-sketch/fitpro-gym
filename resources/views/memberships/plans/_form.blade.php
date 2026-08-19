@props(['plan' => null])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label class="form-label">Plan Name *</label>
        <input type="text" name="name" value="{{ old('name', $plan?->name) }}" class="form-input" required>
    </div>
    <div>
        <label class="form-label">Duration (days) *</label>
        <input type="number" name="duration_days" value="{{ old('duration_days', $plan?->duration_days) }}" class="form-input" min="1" required>
    </div>
    <div>
        <label class="form-label">Price (₹) *</label>
        <input type="number" name="price" step="0.01" value="{{ old('price', $plan?->price) }}" class="form-input" min="0" required>
    </div>
    <div>
        <label class="form-label">Status *</label>
        <select name="status" class="form-input" required>
            @foreach(['active', 'inactive'] as $s)
                <option value="{{ $s }}" @selected(old('status', $plan?->status ?? 'active') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-input">{{ old('description', $plan?->description) }}</textarea>
    </div>
</div>
