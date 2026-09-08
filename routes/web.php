<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DietPlanController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Inventory\ProductCategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\ProductSaleController;
use App\Http\Controllers\Inventory\PurchaseController;
use App\Http\Controllers\Inventory\StockAdjustmentController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/checkout/{member}', [DashboardController::class, 'checkoutMember'])->name('dashboard.checkout');

    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/create', [MemberController::class, 'create'])->name('create');
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::post('/{id}/restore', [MemberController::class, 'restore'])->name('restore');
        Route::get('/{member}', [MemberController::class, 'show'])->name('show');
        Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
        Route::put('/{member}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
        Route::patch('/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::prefix('lockers')->name('lockers.')->group(function () {
        Route::get('/', [LockerController::class, 'index'])->name('index');
        Route::get('/create', [LockerController::class, 'create'])->name('create');
        Route::post('/', [LockerController::class, 'store'])->name('store');
        Route::get('/{locker}/edit', [LockerController::class, 'edit'])->name('edit');
        Route::put('/{locker}', [LockerController::class, 'update'])->name('update');
        Route::delete('/{locker}', [LockerController::class, 'destroy'])->name('destroy');
        Route::get('/{locker}/assign', [LockerController::class, 'assignForm'])->name('assign');
        Route::post('/{locker}/assign', [LockerController::class, 'assign'])->name('assign.store');
        Route::post('/{locker}/unassign', [LockerController::class, 'unassign'])->name('unassign');
    });

    Route::prefix('memberships')->name('memberships.')->group(function () {
        Route::get('/plans', [MembershipPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [MembershipPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [MembershipPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [MembershipPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [MembershipPlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [MembershipPlanController::class, 'destroy'])->name('plans.destroy');
        Route::get('/active', [MembershipPlanController::class, 'active'])->name('active');
        Route::get('/expired', [MembershipPlanController::class, 'expired'])->name('expired');
        Route::get('/expiring', [MembershipPlanController::class, 'expiring'])->name('expiring');
        Route::get('/renewals', [MembershipPlanController::class, 'renewals'])->name('renewals');
        Route::post('/renew/{member}', [MembershipPlanController::class, 'renew'])->name('renew');
    });

    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/mark', [AttendanceController::class, 'markForm'])->name('mark');
        Route::post('/mark', [AttendanceController::class, 'mark'])->name('mark.store');
        Route::post('/checkout/{member}', [AttendanceController::class, 'checkout'])->name('checkout');
    });

    Route::prefix('diet-plans')->name('diet-plans.')->group(function () {
        Route::get('/', [DietPlanController::class, 'index'])->name('index');
        Route::get('/create', [DietPlanController::class, 'create'])->name('create');
        Route::post('/', [DietPlanController::class, 'store'])->name('store');
        Route::get('/assignments', [DietPlanController::class, 'assignments'])->name('assignments');
        Route::get('/assign', [DietPlanController::class, 'assignForm'])->name('assign');
        Route::post('/assign', [DietPlanController::class, 'assign'])->name('assign.store');
        Route::get('/{dietPlan}/edit', [DietPlanController::class, 'edit'])->name('edit');
        Route::put('/{dietPlan}', [DietPlanController::class, 'update'])->name('update');
        Route::delete('/{dietPlan}', [DietPlanController::class, 'destroy'])->name('destroy');
    });

    Route::resource('enquiries', EnquiryController::class)->except(['show']);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{type}', [ReportController::class, 'show'])->name('show');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{notification}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
    });

    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/low-stock', [StockController::class, 'lowStock'])->name('stock.low');
        Route::get('/stock-history', [StockController::class, 'history'])->name('stock.history');

        Route::get('/categories', [ProductCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [ProductCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [ProductCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [ProductCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [ProductCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [ProductCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/adjustments', [StockAdjustmentController::class, 'index'])->name('adjustments.index');
        Route::get('/adjustments/create', [StockAdjustmentController::class, 'create'])->name('adjustments.create');
        Route::post('/adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');

        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/pos', [ProductSaleController::class, 'create'])->name('pos');
        Route::post('/pos', [ProductSaleController::class, 'store'])->name('store');
        Route::get('/', [ProductSaleController::class, 'index'])->name('index');
        Route::get('/today', [ProductSaleController::class, 'today'])->name('today');
        Route::get('/pending', [ProductSaleController::class, 'pending'])->name('pending');
        Route::get('/returns', [ProductSaleController::class, 'returns'])->name('returns');
        Route::get('/{sale}', [ProductSaleController::class, 'show'])->name('show');
        Route::get('/{sale}/return', [ProductSaleController::class, 'returnForm'])->name('return');
        Route::post('/{sale}/return', [ProductSaleController::class, 'processReturn'])->name('return.store');
        Route::patch('/{sale}/mark-paid', [ProductSaleController::class, 'markPaid'])->name('mark-paid');
    });
});
