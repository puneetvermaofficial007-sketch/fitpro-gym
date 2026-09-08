@props(['locker' => null])

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label class="form-label">Locker Number *</label>
        <input type="text" name="locker_code" value="{{ old('locker_code', $locker?->locker_code) }}" class="form-input @error('locker_code') border-red-500 @enderror" placeholder="L-101" required>
        @error('locker_code')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Status *</label>
        @if($locker?->isAssigned())
            <input type="hidden" name="status" value="assigned">
            <input type="text" value="Assigned" class="form-input bg-slate-50" disabled>
            <p class="mt-1 text-xs text-slate-400">Unassign this locker before changing its status.</p>
        @else
            <select name="status" class="form-input">
                <option value="available" @selected(old('status', $locker?->status ?? 'available') === 'available')>Available</option>
                <option value="maintenance" @selected(old('status', $locker?->status) === 'maintenance')>Maintenance</option>
            </select>
        @endif
        @error('status')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $locker?->notes) }}</textarea>
        @error('notes')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>
