<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'interested_membership',
        'source',
        'enquiry_date',
        'follow_up_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'enquiry_date' => 'date',
            'follow_up_date' => 'date',
        ];
    }
}
