<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function increaseStock(
        Product $product,
        int $quantity,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
    ): StockTransaction {
        return DB::transaction(function () use ($product, $quantity, $type, $referenceType, $referenceId, $notes) {
            $product = Product::lockForUpdate()->findOrFail($product->id);
            $previous = $product->current_stock;
            $new = $previous + $quantity;

            $product->update(['current_stock' => $new]);

            return StockTransaction::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previous,
                'new_stock' => $new,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'user_id' => auth()->id(),
                'notes' => $notes,
            ]);
        });
    }

    public function decreaseStock(
        Product $product,
        int $quantity,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
    ): StockTransaction {
        return DB::transaction(function () use ($product, $quantity, $type, $referenceType, $referenceId, $notes) {
            $product = Product::lockForUpdate()->findOrFail($product->id);

            if ($product->current_stock < $quantity) {
                throw new \RuntimeException("Insufficient stock for {$product->name}. Available: {$product->current_stock}");
            }

            $previous = $product->current_stock;
            $new = $previous - $quantity;

            $product->update(['current_stock' => $new]);

            return StockTransaction::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => -$quantity,
                'previous_stock' => $previous,
                'new_stock' => $new,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'user_id' => auth()->id(),
                'notes' => $notes,
            ]);
        });
    }

    public function adjustmentTypeToTransactionType(string $adjustmentType): string
    {
        return match ($adjustmentType) {
            'damaged' => 'damage',
            'lost', 'expired' => $adjustmentType,
            'correction' => 'correction',
            default => 'adjustment',
        };
    }
}
