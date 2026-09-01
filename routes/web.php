<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\AgentStatsController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\CallCenterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public storefront
Route::get('/store/{slug}', [StorefrontController::class, 'show'])->name('storefront.show');
Route::get('/store/{slug}/product/{productId}', [StorefrontController::class, 'showProduct'])->name('storefront.product');
Route::post('/store/{slug}/order', [StorefrontController::class, 'submitOrder'])->name('storefront.order');
Route::get('/store/{slug}/success/{orderId}', [StorefrontController::class, 'success'])->name('storefront.success');

// Order tracking
Route::get('/track/{orderNumber}', [TrackController::class, 'track'])->name('track.order');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard (protected — all roles can view)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ═══════════════════════════════════════════════════
    // VIEWER+ : Can view orders, stats, dashboard
    // ═══════════════════════════════════════════════════
    Route::get('/orders', [OrderController::class, 'index'])->name('dashboard.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('dashboard.orders.show');
    Route::get('/orders/{id}/label', [OrderController::class, 'printLabel'])->name('dashboard.orders.label');
    Route::get('/stats', [StatsController::class, 'index'])->name('dashboard.stats.index');
    Route::get('/api/stats', [StatsController::class, 'api'])->name('dashboard.stats.api');
    Route::get('/stats/agents', [AgentStatsController::class, 'index'])->name('dashboard.stats.agents');

    // ═══════════════════════════════════════════════════
    // OPERATOR+ : Can update orders, manage products
    // ═══════════════════════════════════════════════════
    Route::middleware('role:owner,manager,operator')->group(function () {
        // Order actions
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('dashboard.orders.updateStatus');
        Route::patch('/orders/{id}/call-attempt', [OrderController::class, 'incrementCallAttempt'])->name('dashboard.orders.callAttempt');
        Route::patch('/orders/{id}/quick-update', [OrderController::class, 'quickUpdate'])->name('dashboard.orders.quickUpdate');
        Route::post('/orders/{id}/send-shipping', [OrderController::class, 'sendToShipping'])->name('dashboard.orders.sendShipping');
        Route::post('/orders/sync', [OrderController::class, 'syncOrders'])->name('dashboard.orders.sync');
        Route::post('/orders/send-all', [OrderController::class, 'sendAllToShipping'])->name('dashboard.orders.sendAll');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('dashboard.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('dashboard.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('dashboard.products.store');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('dashboard.products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('dashboard.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('dashboard.products.destroy');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('dashboard.categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('dashboard.categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('dashboard.categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('dashboard.categories.destroy');

        // Coupons
        Route::get('/coupons', [CouponController::class, 'index'])->name('dashboard.coupons.index');
        Route::post('/coupons', [CouponController::class, 'store'])->name('dashboard.coupons.store');
        Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('dashboard.coupons.destroy');
        Route::patch('/coupons/{id}/toggle', [CouponController::class, 'toggleActive'])->name('dashboard.coupons.toggle');

        // Warehouse
        Route::get('/warehouse', [WarehouseController::class, 'index'])->name('dashboard.warehouse.index');
        Route::patch('/warehouse/{id}/stock', [WarehouseController::class, 'updateStock'])->name('dashboard.warehouse.updateStock');
        Route::post('/warehouse/expenses', [WarehouseController::class, 'storeExpense'])->name('dashboard.warehouse.storeExpense');
        Route::delete('/warehouse/expenses/{id}', [WarehouseController::class, 'deleteExpense'])->name('dashboard.warehouse.deleteExpense');
        Route::get('/warehouse/expenses', [WarehouseController::class, 'expenses'])->name('dashboard.warehouse.expenses');
        Route::get('/warehouse/wallet', [WarehouseController::class, 'wallet'])->name('dashboard.warehouse.wallet');
        Route::get('/warehouse/orders', [WarehouseController::class, 'orders'])->name('dashboard.warehouse.orders');
        Route::get('/warehouse/orders/{id}/timeline', [WarehouseController::class, 'orderTimeline'])->name('dashboard.warehouse.orderTimeline');

        // CSV Import
        Route::get('/orders/import', [OrderController::class, 'importExcel'])->name('dashboard.orders.import');
        Route::post('/orders/import', [OrderController::class, 'processImport'])->name('dashboard.orders.import.store');
        Route::get('/orders/export-dhd', [OrderController::class, 'exportDhd'])->name('dashboard.orders.exportDhd');

        // Call Center
        Route::get('/call-center', [CallCenterController::class, 'index'])->name('dashboard.callCenter.index');
        Route::get('/call-center/next', [CallCenterController::class, 'assignNext'])->name('dashboard.callCenter.next');

        // Returns
        Route::get('/returns', [ReturnController::class, 'scan'])->name('dashboard.returns.scan');
        Route::post('/returns/process', [ReturnController::class, 'process'])->name('dashboard.returns.process');

        // Shipping
        Route::get('/shipping', [ShippingController::class, 'index'])->name('dashboard.shipping.index');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('dashboard.shipping.store');
        Route::put('/shipping/{id}', [ShippingController::class, 'updateCompany'])->name('dashboard.shipping.update');
        Route::patch('/shipping/{id}/toggle', [ShippingController::class, 'toggleActive'])->name('dashboard.shipping.toggle');
    });

    // ═══════════════════════════════════════════════════
    // MANAGER+ : Can manage settings, employees, blacklist
    // ═══════════════════════════════════════════════════
    Route::middleware('role:owner,manager')->group(function () {
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('dashboard.settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('dashboard.settings.update');
        Route::put('/settings/facebook', [SettingsController::class, 'updateFacebook'])->name('dashboard.settings.facebook');
        Route::get('/settings/wilaya-rates', [SettingsController::class, 'wilayaRates'])->name('dashboard.settings.wilayaRates');
        Route::post('/settings/wilaya-rates', [SettingsController::class, 'wilayaRates'])->name('dashboard.settings.wilayaRates.store');
        Route::post('/settings/test-dhd', [SettingsController::class, 'testDhd'])->name('dashboard.settings.testDhd');
        Route::post('/settings/test-telegram', [SettingsController::class, 'testTelegram'])->name('dashboard.settings.testTelegram');

        // Phone Blacklist (Fraud Protection)
        Route::post('/settings/blacklist', [SettingsController::class, 'addToBlacklist'])->name('dashboard.settings.blacklist.add');
        Route::delete('/settings/blacklist', [SettingsController::class, 'removeFromBlacklist'])->name('dashboard.settings.blacklist.remove');

        // Employees
        Route::get('/employees', [EmployeeController::class, 'index'])->name('dashboard.employees.index');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('dashboard.employees.store');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('dashboard.employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('dashboard.employees.destroy');
        Route::patch('/employees/{id}/toggle', [EmployeeController::class, 'toggleActive'])->name('dashboard.employees.toggle');
    });
});
