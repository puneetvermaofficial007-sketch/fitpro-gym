<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'type', 'quantity', 'previous_stock', 'new_stock',
        'reference_type', 'reference_id', 'user_id', 'notes',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return ucfirst($this->type);
    }

    public function referenceLabel(): ?string
    {
        if (! $this->reference_type || ! $this->reference_id) {
            return null;
        }

        return match ($this->reference_type) {
            Purchase::class => Purchase::find($this->reference_id)?->purchase_number,
            ProductSale::class => ProductSale::find($this->reference_id)?->sale_number,
            StockAdjustment::class => 'ADJ-'.$this->reference_id,
            ProductSaleReturn::class => ProductSaleReturn::find($this->reference_id)?->return_number,
            default => '#'.$this->reference_id,
        };
    }
}
