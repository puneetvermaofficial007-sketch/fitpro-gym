<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSaleReturn extends Model
{
    protected $fillable = [
        'return_number', 'product_sale_id', 'return_date', 'reason', 'total_refund', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'total_refund' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(ProductSale::class, 'product_sale_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductSaleReturnItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateNumber(): string
    {
        $count = static::count() + 1;

        return 'RET-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
