@props(['dietPlan' => null])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div><label class="form-label">Plan Name *</label><input type="text" name="name" value="{{ old('name', $dietPlan?->name) }}" class="form-input" required></div>
    <div><label class="form-label">Goal</label><input type="text" name="goal" value="{{ old('goal', $dietPlan?->goal) }}" class="form-input" placeholder="Fat loss, Muscle gain..."></div>
    <div><label class="form-label">Calories</label><input type="number" name="calories" value="{{ old('calories', $dietPlan?->calories) }}" class="form-input" min="0"></div>
    <div><label class="form-label">Status *</label>
        <select name="status" class="form-input"><option value="active" @selected(old('status', $dietPlan?->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $dietPlan?->status) === 'inactive')>Inactive</option></select>
    </div>
    @foreach(['breakfast' => 'Breakfast', 'lunch' => 'Lunch', 'dinner' => 'Dinner', 'snacks' => 'Snacks', 'supplements' => 'Supplements', 'notes' => 'Notes'] as $field => $label)
        <div class="{{ $field === 'notes' ? 'md:col-span-2' : '' }}"><label class="form-label">{{ $label }}</label><textarea name="{{ $field }}" rows="2" class="form-input">{{ old($field, $dietPlan?->$field) }}</textarea></div>
    @endforeach
</div>
