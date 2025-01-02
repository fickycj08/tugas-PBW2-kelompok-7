<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;  // Pastikan Anda menggunakan TransactionController
use App\Http\Controllers\PickupController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');  // Mengarahkan ke halaman login
});

// Admin Route
Route::prefix("admin")->name('admin.')->group(function () {

    // Master data route
    Route::prefix('master_data')->name('master_data.')->group(function () {

        // Transaction Route
        Route::controller(TransactionController::class)->prefix('/transaction')->name('transaction.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show', 'show')->name('show');
            Route::get('/{id}/edit', 'detail')->name('detail');
        });
    });
});


Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions (formerly Orders)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', function () {
        return view('new-transaction'); // Mengarahkan ke 'resources/views/new-transaction.blade.php'
    })->name('transactions.create');

    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');


    // Page data pelanggan
    Route::middleware(['auth'])->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer_id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer_id}', [CustomerController::class, 'update'])->name('customers.update');        
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');

    });
    
    // Pickup
    Route::get('/pickup', [PickupController::class, 'index'])->name('pickup.index');

    // Vouchers
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher_id}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher_id}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher_id}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth'])->group(function () {
        Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    });
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/balance/topup', [BalanceController::class, 'topup'])->name('balance.topup');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    });

    // Menampilkan form pesanan
    Route::get('/orders/create', [TransactionController::class, 'create'])->name('user.orders.create');

    // Memproses form pesanan
    Route::post('/orders', [TransactionController::class, 'store'])->name('user.orders.store');
    Route::get('/admin/pickup', [PickupController::class, 'index'])->name('admin.pickup.index');


    Route::post('/user/orders/store', [TransactionController::class, 'store'])->name('user.orders.store');
    Route::get('/user/payment/{transaction}', [PaymentController::class, 'show'])->name('user.payment');
    Route::get('/user/payment/{transaction}', [PaymentController::class, 'show'])->name('user.payment');
    Route::post('/user/payment/{transaction}', [PaymentController::class, 'process'])->name('user.payment.process');
    Route::post('/pickup/{transaction}/accept', [TransactionController::class, 'acceptOrder'])->name('pickup.accept');
    Route::get('/invoice/{id}', [TransactionController::class, 'showInvoice'])->name('user.invoice.show');
    Route::get('/user/payment/{transaction}', [TransactionController::class, 'showPayment'])->name('user.payment');


    Route::middleware(['auth'])->group(function () {
        // Menampilkan form top-up
        Route::get('/balance/topup', [BalanceController::class, 'index'])->name('balance.topup');

        Route::get('/user/topup', [BalanceController::class, 'index'])->name('user.balance.index');
        // Proses top-up
        Route::post('/user/topup', [BalanceController::class, 'store'])->name('user.balance.store');
    });

    //history
    Route::get('/customers/management', function () {
        return view('customers.management');
    })->name('customers.management');

    // Menampilkan daftar pickup
    Route::get('/admin/pickup-requests', [PickupController::class, 'showPickupRequests'])->name('admin.pickup.requests');

    // Menerima pesanan pickup
    Route::post('/admin/pickup-requests/{transaction_id}/accept', [PickupController::class, 'acceptPickup'])->name('admin.pickup.accept');

    // Menolak pesanan pickup
    Route::post('/admin/pickup-requests/{transaction_id}/reject', [PickupController::class, 'rejectPickup'])->name('admin.pickup.reject');


});


require __DIR__ . '/auth.php';
