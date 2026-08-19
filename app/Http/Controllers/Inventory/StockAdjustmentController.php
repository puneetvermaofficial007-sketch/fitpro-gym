<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function index()
    {
        $adjustments = StockAdjustment::with(['product', 'user'])->latest()->paginate(15);

        return view('inventory.adjustments.index', compact('adjustments'));
    }

    public function create(Request $request)
    {
        $products = Product::active()->orderBy('name')->get();
        $selectedProduct = $request->filled('product_id') ? Product::find($request->product_id) : null;

        return view('inventory.adjustments.create', compact('products', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'adjustment_type' => ['required', 'in:increase,decrease,damaged,lost,expired,correction'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $previous = $product->current_stock;

            $isDecrease = in_array($validated['adjustment_type'], ['decrease', 'damaged', 'lost', 'expired']);
            $change = $isDecrease ? -$validated['quantity'] : $validated['quantity'];
            $new = $previous + $change;

            if ($new < 0) {
                throw new \RuntimeException("Cannot adjust below zero. Current stock: {$previous}");
            }

            $adjustment = StockAdjustment::create([
                'product_id' => $product->id,
                'adjustment_type' => $validated['adjustment_type'],
                'quantity_change' => $change,
                'previous_stock' => $previous,
                'new_stock' => $new,
                'reason' => $validated['reason'],
                'user_id' => auth()->id(),
            ]);

            $product->update(['current_stock' => $new]);

            $txType = $this->stockService->adjustmentTypeToTransactionType($validated['adjustment_type']);

            \App\Models\StockTransaction::create([
                'product_id' => $product->id,
                'type' => $txType,
                'quantity' => $change,
                'previous_stock' => $previous,
                'new_stock' => $new,
                'reference_type' => StockAdjustment::class,
                'reference_id' => $adjustment->id,
                'user_id' => auth()->id(),
                'notes' => $validated['reason'],
            ]);
        });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('inventory.adjustments.index')->with('success', 'Stock adjusted successfully.');
    }
}
