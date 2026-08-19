<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSaleReturnItem extends Model
{
    protected $fillable = [
        'product_sale_return_id', 'product_sale_item_id', 'product_id', 'quantity', 'refund_amount',
    ];

    protected function casts(): array
    {
        return ['refund_amount' => 'decimal:2'];
    }

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(ProductSaleReturn::class, 'product_sale_return_id');
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(ProductSaleItem::class, 'product_sale_item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
