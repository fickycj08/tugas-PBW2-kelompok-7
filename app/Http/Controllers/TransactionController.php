<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Tambahkan ini
use App\Models\Customer; // Tambahkan ini
use App\Models\Voucher; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Untuk Auth
use DateTime;
use DateInterval;


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

        // Hitung waktu selesai berdasarkan jenis layanan
        $durasi = [
            'cuci_saja' => 24 * 60 * 60,  // 24 jam
            'cuci_setrika' => 48 * 60 * 60, // 48 jam
            'express' => 12 * 60 * 60, // 12 jam
        ];

        $receivedAt = now(); // Waktu diterima (saat ini)
        $finishedAt = (new DateTime($receivedAt))->add(new DateInterval('PT' . $durasi[$request->service_type] . 'S'))->format('Y-m-d H:i:s');

        Transaction::create([
            'customer_id' => $customer->customer_id,
            'user_id' => Auth::id(),
            'service_type' => $request->service_type,
            'weight' => $request->weight,
            'price' => round($totalPrice, 2),
            'voucher_id' => $voucher->voucher_id ?? null,
            'status' => 'diterima',
            'payment_status' => 'pending',
            'received_at' => $receivedAt,
            'finished_at' => $finishedAt, // Tambahkan ini
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
        $transactions = Transaction::with('customer')
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('finished_at', $date);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->payment_status, function ($query, $payment_status) {
                return $query->where('payment_status', $payment_status);
            })
            ->orderBy('finished_at', 'desc')
            ->get();

        return view('transactions.history', compact('transactions'));
    }

    public function editStatus($id)
    {
        $transaction = Transaction::with('customer')->findOrFail($id);
        return view('transactions.edit-status', compact('transaction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,pickup_rejected',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $request->status]);

        return redirect()->route('transactions.history')->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function adminCreate()
    {
        $customers = Customer::all(); // Fetch pelanggan dari database
        return view('transactions.new-transaction', compact('customers'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'service_type' => 'required|in:cuci_saja,cuci_setrika,express',
            'weight' => 'required|numeric|min:0.1',
            'voucher_code' => 'nullable|exists:vouchers,code',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        // Logika untuk admin
        Transaction::create([
            'customer_id' => $customer->customer_id,
            'user_id' => Auth::id(),
            'service_type' => $request->service_type,
            'weight' => $request->weight,
            'price' => $this->calculatePrice($request->service_type, $request->weight, $request->voucher_code),
            'status' => 'diterima',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dibuat!');
    }

    public function userCreate()
    {
        return view('user.createorder');
    }

    public function userStore(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
        'address' => 'required|string|max:255',
        'service_type' => 'required|in:cuci_saja,cuci_setrika,express',
        'voucher_code' => 'nullable|exists:vouchers,code',
    ]);

    // Cari atau buat pelanggan baru
    $customer = Customer::firstOrCreate(
        ['phone_number' => $request->phone_number],
        ['name' => $request->customer_name, 'address' => $request->address]
    );

    // Hitung waktu selesai berdasarkan jenis layanan
    $durasi = [
        'cuci_saja' => 24 * 60 * 60,  // 24 jam
        'cuci_setrika' => 48 * 60 * 60, // 48 jam
        'express' => 12 * 60 * 60, // 12 jam
    ];

    $receivedAt = now(); // Waktu diterima (saat ini)
    $finishedAt = (new DateTime($receivedAt))->add(new DateInterval('PT' . $durasi[$request->service_type] . 'S'))->format('Y-m-d H:i:s');

    Transaction::create([
        'customer_id' => $customer->customer_id,
        'user_id' => Auth::id(),
        'service_type' => $request->service_type,
        'status' => 'pickup_request',
        'payment_status' => 'pending',
        'price' => $this->calculatePrice($request->service_type, 1, $request->voucher_code), // Asumsikan berat default 1kg
        'received_at' => $receivedAt,
        'finished_at' => $finishedAt, // Tambahkan waktu selesai
        'address' => $request->address, // Tambahkan address
    ]);    

    return redirect()->route('user.dashboard')->with('success', 'Pesanan berhasil dibuat!');
}


    protected function calculatePrice($serviceType, $weight, $voucherCode = null)
    {
        $basePrice = match ($serviceType) {
            'cuci_saja' => 10000,
            'cuci_setrika' => 15000,
            'express' => 25000,
            default => 0,
        };

        $voucher = $voucherCode ? Voucher::where('code', $voucherCode)->first() : null;
        $discount = $voucher ? $voucher->discount / 100 : 0;

        return $basePrice * $weight * (1 - $discount);
    }


}

