<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo',
        'membership_plan_id',
        'joining_date',
        'membership_start_date',
        'membership_expiry_date',
        'payment_status',
        'status',
        'notes',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'membership_start_date' => 'date',
            'membership_expiry_date' => 'date',
            'checked_in_at' => 'datetime',
        ];
    }

    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function dietPlanAssignments(): HasMany
    {
        return $this->hasMany(MemberDietPlan::class);
    }

    public function activeDietPlan()
    {
        return $this->hasOne(MemberDietPlan::class)->where('is_active', true)->latest();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1).substr($this->last_name, 0, 1));
    }

    public function isExpired(): bool
    {
        return $this->membership_expiry_date && $this->membership_expiry_date->isPast();
    }

    public function daysUntilExpiry(): ?int
    {
        if (! $this->membership_expiry_date) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays($this->membership_expiry_date, false);
    }

    public function isCheckedIn(): bool
    {
        return $this->checked_in_at !== null;
    }

    public function getDurationInsideAttribute(): ?string
    {
        if (! $this->checked_in_at) {
            return null;
        }

        $minutes = $this->checked_in_at->diffInMinutes(now());
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->whereNull('deleted_at');
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
                ->orWhere('membership_expiry_date', '<', now());
        });
    }

    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->whereBetween('membership_expiry_date', [now(), now()->addDays($days)]);
    }

    public static function generateMemberCode(): string
    {
        $last = static::withTrashed()->orderByDesc('id')->first();
        $next = $last ? $last->id + 1 : 1;

        return 'GYM-'.str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}
