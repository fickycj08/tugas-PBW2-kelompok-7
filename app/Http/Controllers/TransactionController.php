<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Tambahkan ini
use App\Models\Customer; // Tambahkan ini
use App\Models\Voucher; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Untuk Auth

class TransactionController extends Controller
{
    // Method untuk menampilkan daftar transaksi
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        // Ambil data ringkasan pesanan
        $pesananDiterima = Transaction::where('status', 'diterima')->count();
        $pesananDiproses = Transaction::where('status', 'diproses')->count();
        $pesananSelesai = Transaction::where('status', 'selesai')->count();
        $pesananPerluDikirim = Transaction::whereDate('finished_at', now()->toDateString())->where('status', 'selesai')->count();

        // Filter transaksi
        $transactions = Transaction::with('customer')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('transaction_id', 'like', "%$search%");
            })
            ->when($request->input('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();


        return view('dashboard', compact(
            'pesananDiterima',
            'pesananDiproses',
            'pesananSelesai',
            'pesananPerluDikirim',
            'transactions'
        ));
    }



    // Method untuk membuat transaksi baru
    public function create()
    {
        $customers = Customer::all(); // Fetch pelanggan dari database
    return view('transactions.new-transaction', compact('customers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'service_type' => 'required|in:cuci_saja,cuci_setrika,express',
            'weight' => 'required|numeric|min:0.1',
            'voucher_code' => 'nullable|exists:vouchers,code',
        ]);
    
        // Cari pelanggan
        $customer = Customer::findOrFail($request->customer_id);
    
        // Cek voucher
        $voucher = null;
        $discount = 0;
    
        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_until', '>=', now())
                ->first();
    
            if ($voucher) {
                $discount = $voucher->discount / 100; // Konversi diskon ke desimal
            } else {
                return back()->withErrors(['voucher_code' => 'Kode voucher tidak valid atau sudah kedaluwarsa.']);
            }
        }
    
        // Hitung harga total
        $pricePerKg = match ($request->service_type) {
            'cuci_saja' => 10000,
            'cuci_setrika' => 15000,
            'express' => 25000,
            default => 0,
        };
        $totalPrice = $pricePerKg * $request->weight * (1 - $discount); // Terapkan diskon jika ada
    
        // Simpan transaksi
        Transaction::create([
            'customer_id' => $customer->customer_id,
            'user_id' => Auth::id(),
            'service_type' => $request->service_type,
            'weight' => $request->weight,
            'price' => round($totalPrice, 2), // Pastikan harga dibulatkan ke 2 desimal
            'voucher_id' => $voucher->voucher_id ?? null,
            'status' => 'diterima',
            'payment_status' => 'pending',
        ]);
    
        // Redirect ke halaman dashboard
        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dibuat!');
    }
    
    public function acceptOrder(Request $request, Transaction $transaction)
    {
        // Update status dan tanggal terkait
        $transaction->update([
            'status' => 'diproses', // Status diperbarui menjadi 'diproses'
            'received_at' => now(), // Tanggal diterima (waktu saat ini)
            'pickup_at' => now(),   // Tanggal kirim (waktu saat ini)
        ]);

        // Hitung service duration berdasarkan jenis layanan
        $serviceDuration = match ($transaction->service_type) {
            'cuci_saja' => 2,       // 2 hari
            'cuci_setrika' => 3,    // 3 hari
            'express' => 1,         // 1 hari
            default => 0,           // Default jika tidak sesuai
        };

        // Update service_duration di database
        $transaction->update([
            'service_duration' => $serviceDuration,
        ]);

        // Redirect ke halaman pickup dengan pesan sukses
        return redirect()->route('pickup.index')->with('success', 'Pesanan berhasil diterima dan diproses.');
    }
    public function showInvoice($id)
    {
        // Ambil data transaksi berdasarkan ID
        $transaction = Transaction::with('customer')->findOrFail($id);

        return view('user.invoice', compact('transaction'));
    }

    public function showPickupRequests()
    {
        $pickupRequests = Transaction::where('status', 'pickup_request')->get();

        return view('admin.pickup_requests', compact('pickupRequests'));
    }

    public function acceptPickup($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $transaction->update(['status' => 'pickup_confirmed']);

        return redirect()->route('admin.pickup.requests')->with('success', 'Pesanan pickup diterima.');
    }

    public function showPayment($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);

        return view('user.payment', compact('transaction'));
    }

    public function processPayment(Request $request, $transaction_id)
    {
        $transaction = Transaction::where('transaction_id', $transaction_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'payment_method' => 'required|in:qris,bank_transfer,e_wallet',
        ]);

        $transaction->update([
            'payment_status' => 'paid', // Tandai sebagai sudah dibayar
            'paid_at' => now(),         // Waktu pembayaran
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Pembayaran berhasil dilakukan!');
    }


    public function userDashboard()
    {
        $orders = Transaction::where('user_id', Auth::id())->get();

        $activeOrders = $orders->filter(function ($order) {
            return in_array($order->status, ['pickup_request', 'pickup_confirmed', 'diproses']);
        });

        $completedOrders = $orders->filter(function ($order) {
            return $order->status === 'selesai';
        });

        $balance = Auth::user()->balance ?? 0;

        return view('user.dashboard', compact('orders', 'activeOrders', 'completedOrders', 'balance'));
    }

    public function history(Request $request)
    {
        // Filter parameters
        $status = $request->input('status');
        $payment = $request->input('payment');
        $dateRange = $request->input('date_range');

        // Query transaksi
        $transactions = Transaction::with('customer', 'user', 'voucher')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($payment, function ($query, $payment) {
                return $query->where('payment_status', $payment);
            })
            ->when($dateRange, function ($query, $dateRange) {
                $dates = explode(' - ', $dateRange);
                return $query->whereBetween('created_at', [$dates[0], $dates[1]]);
            })
            ->get();

        return view('transactions.history', compact('transactions'));
    }


}

