<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSale extends Model
{
    protected $fillable = [
        'sale_number', 'member_id', 'customer_type', 'customer_name', 'customer_phone',
        'subtotal', 'discount', 'tax', 'total', 'profit', 'payment_method', 'payment_status', 'user_id', 'sale_date',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'profit' => 'decimal:2',
            'sale_date' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductSaleItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ProductSaleReturn::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customerDisplayName(): string
    {
        if ($this->customer_type === 'member' && $this->member) {
            return $this->member->full_name;
        }

        return $this->customer_name ?: 'Walk-in Customer';
    }

    public static function generateNumber(): string
    {
        $count = static::whereYear('created_at', now()->year)->count() + 1;

        return 'SALE-'.now()->format('Y').'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
}
