<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// Route utama untuk debugging


// Redirect ke login jika belum login
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Route::get('/debug', function () {
//     if (Auth::check()) {
//         return response()->json([
//             'user_id' => Auth::user()->user_id,
//             'name' => Auth::user()->name,
//             'role' => Auth::user()->role,
//         ]);
//     }
//     return 'Not logged in';
// });

Route::get('/debug', function () {
    return response()->json(Auth::user());
})->middleware('auth');


// // Route untuk kasir
// Route::middleware(['auth', RoleMiddleware::class . ':kasir'])->group(function () {
//     Route::get('/dashboard', function () {
//         return 'Welcome to Kasir Dashboard';
//     })->name('dashboard');
// });
// // Route untuk user
// Route::middleware(['auth', RoleMiddleware::class . ':user'])->group(function () {
//     Route::get('/user/dashboard', function () {
//         return 'Welcome to User Dashboard';
//     })->name('user.dashboard');
// });

Route::get('/choose-dashboard', function () {
    return view('choose-dashboard');
})->middleware('auth')->name('choose-dashboard');


Route::middleware(['auth', RoleMiddleware::class . ':kasir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', RoleMiddleware::class . ':user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});


// Route untuk kasir
Route::middleware(['auth', RoleMiddleware::class . ':kasir'])->group(function () {

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/{id}/edit-status', [TransactionController::class, 'editStatus'])->name('transactions.edit-status');
Route::put('/transactions/{id}', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');


    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer_id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer_id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Pickup
    Route::get('/pickup', [PickupController::class, 'index'])->name('pickup.index');
    Route::post('/pickup/{transaction}/accept', [TransactionController::class, 'acceptOrder'])->name('pickup.accept');
    // Menerima pesanan pickup
    Route::post('/admin/pickup-requests/{transaction_id}/accept', [PickupController::class, 'acceptPickup'])->name('admin.pickup.accept');
    // Menolak pesanan pickup
    Route::post('/admin/pickup-requests/{transaction_id}/reject', [PickupController::class, 'rejectPickup'])->name('admin.pickup.reject');
    Route::post('/orders', [TransactionController::class, 'store'])->name('user.orders.store');
    Route::get('/admin/pickup', [PickupController::class, 'index'])->name('admin.pickup.index');

    // Vouchers
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher_id}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher_id}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher_id}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');

    Route::get('/admin/orders/create', [TransactionController::class, 'adminCreate'])->name('admin.orders.create');
    Route::post('/admin/orders/store', [TransactionController::class, 'adminStore'])->name('admin.orders.store');
});

// Route untuk user
Route::middleware(['auth', RoleMiddleware::class . ':user'])->group(function () {

    // Orders
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [TransactionController::class, 'store'])->name('user.orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

        // Orders
    Route::get('/orders/create', [TransactionController::class, 'create'])->name('user.orders.create');
    Route::post('/orders', [TransactionController::class, 'store'])->name('user.orders.store');
    Route::get('/invoice/{id}', [TransactionController::class, 'showInvoice'])->name('user.invoice.show');

    // Payments
    Route::get('/user/payment/{transaction}', [PaymentController::class, 'show'])->name('user.payment');
    Route::post('/user/payment/{transaction}', [PaymentController::class, 'process'])->name('user.payment.process');

    // Balance (Top-Up)
    Route::get('/user/topup', [BalanceController::class, 'index'])->name('user.balance.index');
    Route::post('/user/topup', [BalanceController::class, 'store'])->name('user.balance.store');

    // Invoices
    Route::get('/invoice/{id}', [TransactionController::class, 'showInvoice'])->name('user.invoice.show');

    Route::get('/user/orders/create', [TransactionController::class, 'userCreate'])->name('user.orders.create');
    Route::post('/user/orders/store', [TransactionController::class, 'userStore'])->name('user.orders.store');
});








// // Route untuk user
// Route::middleware(['auth', RoleMiddleware::class . ':user'])->group(function () {
//     // Dashboard
//     Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

//     // Transactions
//     Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
//     Route::get('/transactions/create', function () {
//         return view('new-transaction');
//     })->name('transactions.create');
//     Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');

//     // Orders
//     Route::get('/orders/create', [TransactionController::class, 'create'])->name('user.orders.create');
//     Route::post('/orders', [TransactionController::class, 'store'])->name('user.orders.store');
//     Route::get('/invoice/{id}', [TransactionController::class, 'showInvoice'])->name('user.invoice.show');

//     // Balance (Top-Up)
//     Route::get('/balance/topup', [BalanceController::class, 'index'])->name('balance.topup');
//     Route::post('/user/topup', [BalanceController::class, 'store'])->name('user.balance.store');

//     // Payment
//     Route::get('/user/payment/{transaction}', [PaymentController::class, 'show'])->name('user.payment');
//     Route::post('/user/payment/{transaction}', [PaymentController::class, 'process'])->name('user.payment.process');

// });

// Auth routes
require __DIR__ . '/auth.php';
