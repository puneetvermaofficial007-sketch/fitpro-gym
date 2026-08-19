<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enquiry;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductSale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'new_members' => Member::where('joining_date', '>=', now()->startOfMonth())->count(),
            'expired_memberships' => Member::expired()->count(),
            'expiring_soon' => Member::expiringSoon(7)->count(),
            'today_attendance' => Attendance::where('date', today())->where('status', 'present')->count(),
            'today_membership_revenue' => Invoice::where('payment_status', 'paid')->whereDate('invoice_date', today())->sum('final_amount'),
            'today_product_revenue' => ProductSale::where('payment_status', 'paid')->whereDate('sale_date', today())->sum('total'),
            'today_revenue' => Invoice::where('payment_status', 'paid')->whereDate('invoice_date', today())->sum('final_amount')
                + ProductSale::where('payment_status', 'paid')->whereDate('sale_date', today())->sum('total'),
            'pending_payments' => Invoice::where('payment_status', 'pending')->sum('final_amount'),
            'total_products' => Product::count(),
            'low_stock' => Product::lowStock()->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'today_product_sales' => ProductSale::whereDate('sale_date', today())->count(),
        ];

        $liveActiveMembers = Member::active()
            ->whereNotNull('checked_in_at')
            ->with('membershipPlan')
            ->get();

        $recentMembers = Member::with('membershipPlan')
            ->latest()
            ->take(8)
            ->get();

        $expiringMemberships = Member::with('membershipPlan')
            ->where('membership_expiry_date', '<=', now()->addDays(30))
            ->where('membership_expiry_date', '>=', now()->subDays(7))
            ->orderBy('membership_expiry_date')
            ->take(6)
            ->get();

        $recentEnquiries = Enquiry::latest()->take(5)->get();

        $recentProductSales = ProductSale::with('member')
            ->latest('sale_date')
            ->take(5)
            ->get();

        $revenueData = $this->getRevenueChartData('weekly');

        $attendanceData = $this->getAttendanceChartData();

        $notifications = $this->getDashboardNotifications();

        return view('dashboard.index', compact(
            'stats',
            'liveActiveMembers',
            'recentMembers',
            'expiringMemberships',
            'recentEnquiries',
            'recentProductSales',
            'revenueData',
            'attendanceData',
            'notifications',
        ));
    }

    public function checkoutMember(Member $member)
    {
        $member->update(['checked_in_at' => null]);

        return back()->with('success', "{$member->full_name} checked out successfully.");
    }

    private function getRevenueChartData(string $period): array
    {
        $labels = [];
        $values = [];

        if ($period === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('D');
                $membership = (float) Invoice::where('payment_status', 'paid')
                    ->whereDate('invoice_date', $date)
                    ->sum('final_amount');
                $product = (float) ProductSale::where('payment_status', 'paid')
                    ->whereDate('sale_date', $date)
                    ->sum('total');
                $values[] = $membership + $product;
            }
        }

        return compact('labels', 'values');
    }

    private function getAttendanceChartData(): array
    {
        $labels = [];
        $present = [];
        $absent = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $present[] = Attendance::where('date', $date)->where('status', 'present')->count();
            $absent[] = Attendance::where('date', $date)->where('status', 'absent')->count();
        }

        return compact('labels', 'present', 'absent');
    }

    private function getDashboardNotifications(): array
    {
        return [
            ['type' => 'warning', 'title' => 'Membership Expiring Soon', 'message' => '3 memberships expire within 7 days', 'time' => '2h ago'],
            ['type' => 'danger', 'title' => 'Payment Pending', 'message' => '2 invoices are overdue', 'time' => '5h ago'],
            ['type' => 'info', 'title' => 'New Enquiry', 'message' => 'Deepak Verma submitted a new enquiry', 'time' => '1d ago'],
            ['type' => 'success', 'title' => 'New Member', 'message' => 'Ananya Gupta joined today', 'time' => '2d ago'],
        ];
    }
}
