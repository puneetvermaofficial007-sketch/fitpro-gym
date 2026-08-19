<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\ProductSaleItem;
use App\Models\ProductSaleReturn;
use App\Models\ProductSaleReturnItem;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductSaleController extends Controller
{
    public function __construct(private StockService $stockService) {}

    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $products = Product::active()->where('current_stock', '>', 0)->orderBy('name')->get();

        return view('sales.pos', compact('members', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_type' => ['required', 'in:member,walk_in'],
            'member_id' => ['nullable', 'required_if:customer_type,member', 'exists:members,id'],
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cash,card,upi,other'],
            'payment_status' => ['required', 'in:paid,pending,partial'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $sale = DB::transaction(function () use ($validated) {
                $subtotal = 0;
                $totalProfit = 0;
                $lineItems = [];

                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->current_stock < $item['quantity']) {
                        throw new \RuntimeException("Insufficient stock for {$product->name}. Available: {$product->current_stock}");
                    }

                    $lineTotal = $product->selling_price * $item['quantity'];
                    $lineProfit = ($product->selling_price - $product->purchase_price) * $item['quantity'];

                    $lineItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->selling_price,
                        'purchase_price' => $product->purchase_price,
                        'total' => $lineTotal,
                        'profit' => $lineProfit,
                    ];

                    $subtotal += $lineTotal;
                    $totalProfit += $lineProfit;
                }

                $discount = $validated['discount'] ?? 0;
                $tax = $validated['tax'] ?? 0;
                $total = max(0, $subtotal - $discount + $tax);

                $member = null;
                $customerName = $validated['customer_name'];
                $customerPhone = $validated['customer_phone'];

                if ($validated['customer_type'] === 'member') {
                    $member = Member::find($validated['member_id']);
                    $customerName = $member->full_name;
                    $customerPhone = $member->phone;
                }

                $sale = ProductSale::create([
                    'sale_number' => ProductSale::generateNumber(),
                    'member_id' => $member?->id,
                    'customer_type' => $validated['customer_type'],
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $total,
                    'profit' => $totalProfit,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_status'],
                    'user_id' => auth()->id(),
                    'sale_date' => now(),
                ]);

                foreach ($lineItems as $line) {
                    ProductSaleItem::create([
                        'product_sale_id' => $sale->id,
                        'product_id' => $line['product']->id,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['unit_price'],
                        'purchase_price' => $line['purchase_price'],
                        'total' => $line['total'],
                        'profit' => $line['profit'],
                    ]);

                    $this->stockService->decreaseStock(
                        $line['product'],
                        $line['quantity'],
                        'sale',
                        ProductSale::class,
                        $sale->id,
                        "Sale {$sale->sale_number}",
                    );
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale)->with('success', 'Sale completed successfully.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = ProductSale::with(['member', 'user', 'items']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        $sales = $query->latest('sale_date')->paginate(15)->withQueryString();
        $members = Member::orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        return view('sales.index', compact('sales', 'members', 'search'));
    }

    public function today()
    {
        $sales = ProductSale::with(['member', 'items'])
            ->whereDate('sale_date', today())
            ->latest('sale_date')
            ->paginate(15);

        $stats = [
            'count' => ProductSale::whereDate('sale_date', today())->count(),
            'revenue' => ProductSale::whereDate('sale_date', today())->where('payment_status', 'paid')->sum('total'),
            'profit' => ProductSale::whereDate('sale_date', today())->sum('profit'),
        ];

        return view('sales.today', compact('sales', 'stats'));
    }

    public function pending()
    {
        $sales = ProductSale::with(['member', 'items'])
            ->whereIn('payment_status', ['pending', 'partial'])
            ->latest('sale_date')
            ->paginate(15);

        return view('sales.pending', compact('sales'));
    }

    public function show(ProductSale $sale)
    {
        $sale->load(['member', 'items.product', 'user']);

        return view('sales.show', compact('sale'));
    }

    public function returns()
    {
        $returns = ProductSaleReturn::with(['sale', 'user'])->latest()->paginate(15);

        return view('sales.returns.index', compact('returns'));
    }

    public function returnForm(ProductSale $sale)
    {
        $sale->load(['items.product', 'items.returnItems']);

        return view('sales.returns.create', compact('sale'));
    }

    public function processReturn(Request $request, ProductSale $sale)
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:product_sale_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            DB::transaction(function () use ($validated, $sale) {
                $totalRefund = 0;
                $returnItems = [];

                foreach ($validated['items'] as $itemData) {
                    if (($itemData['quantity'] ?? 0) <= 0) {
                        continue;
                    }

                    $saleItem = ProductSaleItem::where('product_sale_id', $sale->id)
                        ->findOrFail($itemData['sale_item_id']);

                    if ($itemData['quantity'] > $saleItem->returnableQuantity()) {
                        throw new \RuntimeException("Cannot return more than purchased for {$saleItem->product->name}.");
                    }

                    $refund = ($saleItem->unit_price * $itemData['quantity']);
                    $returnItems[] = ['saleItem' => $saleItem, 'quantity' => $itemData['quantity'], 'refund' => $refund];
                    $totalRefund += $refund;
                }

                if (empty($returnItems)) {
                    throw new \RuntimeException('Select at least one item to return.');
                }

                $return = ProductSaleReturn::create([
                    'return_number' => ProductSaleReturn::generateNumber(),
                    'product_sale_id' => $sale->id,
                    'return_date' => today(),
                    'reason' => $validated['reason'],
                    'total_refund' => $totalRefund,
                    'user_id' => auth()->id(),
                ]);

                foreach ($returnItems as $ri) {
                    ProductSaleReturnItem::create([
                        'product_sale_return_id' => $return->id,
                        'product_sale_item_id' => $ri['saleItem']->id,
                        'product_id' => $ri['saleItem']->product_id,
                        'quantity' => $ri['quantity'],
                        'refund_amount' => $ri['refund'],
                    ]);

                    $this->stockService->increaseStock(
                        $ri['saleItem']->product,
                        $ri['quantity'],
                        'return',
                        ProductSaleReturn::class,
                        $return->id,
                        "Return {$return->return_number}",
                    );
                }
            });

            return redirect()->route('sales.returns')->with('success', 'Return processed. Stock updated.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function markPaid(ProductSale $sale)
    {
        $sale->update(['payment_status' => 'paid']);

        return back()->with('success', 'Payment marked as paid.');
    }
}
