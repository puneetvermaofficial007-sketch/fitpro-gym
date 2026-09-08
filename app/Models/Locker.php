<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\ValidationException;

class Locker extends Model
{
    protected $fillable = [
        'locker_code',
        'status',
        'notes',
    ];

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isAssigned(): bool
    {
        return $this->status === 'assigned';
    }

    public function assignTo(Member $member): void
    {
        $this->load('member');

        if ($this->status === 'maintenance') {
            throw ValidationException::withMessages([
                'locker_id' => 'This locker is under maintenance and cannot be assigned.',
            ]);
        }

        if ($this->member && $this->member->id !== $member->id) {
            throw ValidationException::withMessages([
                'locker_id' => 'This locker is already assigned to another member.',
            ]);
        }

        if ($member->locker_id && $member->locker_id !== $this->id) {
            $member->locker?->release();
        }

        $member->forceFill(['locker_id' => $this->id])->save();
        $this->update(['status' => 'assigned']);
    }

    public function release(): void
    {
        $this->load('member');

        if ($this->member) {
            $this->member->forceFill(['locker_id' => null])->save();
        }

        if ($this->status === 'assigned') {
            $this->update(['status' => 'available']);
        }
    }
}
