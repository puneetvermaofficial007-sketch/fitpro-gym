<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('current_stock'),
            'low_stock' => Product::lowStock()->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'inventory_value' => Product::selectRaw('SUM(current_stock * purchase_price) as val')->value('val') ?? 0,
        ];

        $products = Product::with(['category', 'supplier'])
            ->orderBy('current_stock')
            ->paginate(15);

        return view('inventory.stock.index', compact('stats', 'products'));
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'supplier'])
            ->where(function ($q) {
                $q->lowStock()->orWhere(fn ($q2) => $q2->outOfStock());
            })
            ->orderBy('current_stock')
            ->paginate(15);

        return view('inventory.stock.low', compact('products'));
    }

    public function history(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest('created_at')->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('inventory.stock.history', compact('transactions', 'products'));
    }
}
