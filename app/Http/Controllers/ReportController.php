<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DietPlan;
use App\Models\Enquiry;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberDietPlan;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Purchase;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function show(Request $request, string $type)
    {
        [$dateFrom, $dateTo] = $this->dateRange($request);

        $data = match ($type) {
            'members' => $this->memberReport($dateFrom, $dateTo),
            'memberships' => $this->membershipReport(),
            'revenue' => $this->revenueReport($dateFrom, $dateTo),
            'attendance' => $this->attendanceReport($dateFrom, $dateTo),
            'expired-memberships' => $this->expiredMembershipReport(),
            'pending-payments' => $this->pendingPaymentReport(),
            'diet-plans' => $this->dietPlanReport(),
            'inventory-stock' => $this->inventoryStockReport(),
            'low-stock' => $this->lowStockReport(),
            'product-sales' => $this->productSalesReport($dateFrom, $dateTo),
            'product-profit' => $this->productProfitReport($dateFrom, $dateTo),
            'purchases' => $this->purchaseReport($dateFrom, $dateTo),
            'stock-movement' => $this->stockMovementReport($dateFrom, $dateTo),
            default => abort(404),
        };

        return view('reports.show', compact('type', 'data', 'dateFrom', 'dateTo'));
    }

    private function dateRange(Request $request): array
    {
        $period = $request->get('period', 'month');

        return match ($period) {
            'today' => [today(), today()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [
                $request->date('date_from', today()),
                $request->date('date_to', today()),
            ],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function memberReport($from, $to): array
    {
        return [
            'title' => 'Member Report',
            'rows' => Member::whereBetween('joining_date', [$from, $to])->with('membershipPlan')->get(),
            'columns' => ['Member', 'Phone', 'Plan', 'Joining Date', 'Status'],
        ];
    }

    private function membershipReport(): array
    {
        return [
            'title' => 'Membership Report',
            'rows' => Member::with('membershipPlan')->get(),
            'columns' => ['Member', 'Plan', 'Start', 'Expiry', 'Status'],
        ];
    }

    private function revenueReport($from, $to): array
    {
        $membershipTotal = Invoice::whereBetween('invoice_date', [$from, $to])->where('payment_status', 'paid')->sum('final_amount');
        $productTotal = ProductSale::whereBetween('sale_date', [$from, $to])->where('payment_status', 'paid')->sum('total');

        return [
            'title' => 'Revenue Report',
            'rows' => collect([
                (object) ['source' => 'Membership', 'amount' => $membershipTotal],
                (object) ['source' => 'Product Sales', 'amount' => $productTotal],
            ]),
            'columns' => ['Revenue Source', 'Amount'],
            'total' => $membershipTotal + $productTotal,
            'is_summary' => true,
        ];
    }

    private function attendanceReport($from, $to): array
    {
        return [
            'title' => 'Attendance Report',
            'rows' => Attendance::whereBetween('date', [$from, $to])->with('member')->get(),
            'columns' => ['Member', 'Date', 'Check In', 'Status'],
        ];
    }

    private function expiredMembershipReport(): array
    {
        return [
            'title' => 'Expired Membership Report',
            'rows' => Member::expired()->with('membershipPlan')->get(),
            'columns' => ['Member', 'Plan', 'Expiry', 'Phone', 'Status'],
        ];
    }

    private function pendingPaymentReport(): array
    {
        return [
            'title' => 'Pending Payment Report',
            'rows' => Invoice::whereIn('payment_status', ['pending', 'overdue'])->with('member')->get(),
            'columns' => ['Invoice', 'Member', 'Amount', 'Status', 'Due Date'],
            'total' => Invoice::whereIn('payment_status', ['pending', 'overdue'])->sum('final_amount'),
        ];
    }

    private function dietPlanReport(): array
    {
        return [
            'title' => 'Diet Plan Report',
            'rows' => MemberDietPlan::with(['member', 'dietPlan'])->where('is_active', true)->get(),
            'columns' => ['Member', 'Diet Plan', 'Start', 'End', 'Goal'],
        ];
    }

    private function inventoryStockReport(): array
    {
        return [
            'title' => 'Current Stock Report',
            'rows' => Product::with(['category', 'supplier'])->orderBy('name')->get(),
            'columns' => ['Product', 'SKU', 'Category', 'Stock', 'Min Level', 'Value', 'Status'],
        ];
    }

    private function lowStockReport(): array
    {
        return [
            'title' => 'Low Stock Report',
            'rows' => Product::with('category')->whereColumn('current_stock', '<=', 'minimum_stock_level')->orderBy('current_stock')->get(),
            'columns' => ['Product', 'Stock', 'Minimum', 'Status'],
        ];
    }

    private function productSalesReport($from, $to): array
    {
        return [
            'title' => 'Product Sales Report',
            'rows' => ProductSale::whereBetween('sale_date', [$from, $to])->with(['member', 'items'])->get(),
            'columns' => ['Sale #', 'Customer', 'Items', 'Total', 'Payment', 'Date'],
            'total' => ProductSale::whereBetween('sale_date', [$from, $to])->where('payment_status', 'paid')->sum('total'),
        ];
    }

    private function productProfitReport($from, $to): array
    {
        return [
            'title' => 'Product Profit Report',
            'rows' => ProductSale::whereBetween('sale_date', [$from, $to])->with('items.product')->get(),
            'columns' => ['Sale #', 'Customer', 'Revenue', 'Profit', 'Date'],
            'total' => ProductSale::whereBetween('sale_date', [$from, $to])->sum('profit'),
        ];
    }

    private function purchaseReport($from, $to): array
    {
        return [
            'title' => 'Purchase Report',
            'rows' => Purchase::whereBetween('purchase_date', [$from, $to])->with(['supplier', 'items'])->get(),
            'columns' => ['Purchase #', 'Supplier', 'Items', 'Total', 'Date'],
            'total' => Purchase::whereBetween('purchase_date', [$from, $to])->sum('total_amount'),
        ];
    }

    private function stockMovementReport($from, $to): array
    {
        return [
            'title' => 'Stock Movement Report',
            'rows' => StockTransaction::whereBetween('created_at', [$from, $to])->with(['product', 'user'])->latest('created_at')->get(),
            'columns' => ['Date', 'Product', 'Type', 'Qty', 'Previous', 'New', 'Reference'],
        ];
    }
}
