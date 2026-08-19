<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])->latest()->paginate(10);

        return view('inventory.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();

        return view('inventory.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.purchase_price' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase = DB::transaction(function () use ($validated) {
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['quantity'] * $item['purchase_price'];
            }

            $purchase = Purchase::create([
                'purchase_number' => Purchase::generateNumber(),
                'supplier_id' => $validated['supplier_id'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => $total,
                'notes' => $validated['notes'],
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['purchase_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'total' => $lineTotal,
                ]);

                $product = Product::find($item['product_id']);
                $product->update(['purchase_price' => $item['purchase_price']]);

                $this->stockService->increaseStock(
                    $product,
                    $item['quantity'],
                    'purchase',
                    Purchase::class,
                    $purchase->id,
                    "Purchase {$purchase->purchase_number}",
                );
            }

            return $purchase;
        });

        return redirect()->route('inventory.purchases.index')->with('success', "Purchase {$purchase->purchase_number} recorded. Stock updated.");
    }
}
