<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DietPlan extends Model
{
    protected $fillable = [
        'name',
        'goal',
        'calories',
        'breakfast',
        'lunch',
        'dinner',
        'snacks',
        'supplements',
        'notes',
        'status',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(MemberDietPlan::class);
    }
}
