<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($stockStatus = $request->get('stock_status')) {
            $query = match ($stockStatus) {
                'low' => $query->lowStock(),
                'out' => $query->outOfStock(),
                'in' => $query->where('current_stock', '>', 0)->whereColumn('current_stock', '>', 'minimum_stock_level'),
                default => $query,
            };
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'name');
        $dir = $request->get('dir', 'asc');
        $query->orderBy(in_array($sort, ['name', 'sku', 'selling_price', 'current_stock']) ? $sort : 'name', $dir === 'desc' ? 'desc' : 'asc');

        $products = $query->paginate(12)->withQueryString();
        $categories = ProductCategory::active()->orderBy('name')->get();

        return view('inventory.products.index', compact('products', 'categories', 'search'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('inventory.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['sku'] = $validated['sku'] ?? Product::generateSku();
        $validated['current_stock'] = 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('inventory.products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier', 'stockTransactions' => fn ($q) => $q->latest()->take(20)]);

        return view('inventory.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('inventory.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('inventory.products.show', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('inventory.products.index')->with('success', 'Product deleted.');
    }

    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $products = Product::active()
            ->where('current_stock', '>', 0)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%");
            })
            ->with('category')
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => (float) $p->selling_price,
                'stock' => $p->current_stock,
                'purchase_price' => (float) $p->purchase_price,
            ]);

        return response()->json($products);
    }

    private function validateProduct(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['nullable', 'string', 'max:50', 'unique:products,sku,'.$id],
            'barcode' => ['nullable', 'string', 'max:50', 'unique:products,barcode,'.$id],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock_level' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
