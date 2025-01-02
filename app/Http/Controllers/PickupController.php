<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Voucher; // Tambahkan ini



class PickupController extends Controller
{
    // Menampilkan daftar pesanan pickup
    public function showPickupRequests()
    {
        $pickupRequests = Transaction::where('status', 'pickup_request')->get();

        return view('admin.pickup', compact('pickupRequests'));
    }
    public function index()
{
    $pickupRequests = Transaction::where('status', 'pickup_request')->get();

    return view('admin.pickup', compact('pickupRequests'));
}

public function acceptPickup(Request $request, $transaction_id)
{
    // Validasi input berat dan kode voucher
    $request->validate([
        'weight' => 'required|numeric|min:0.1',
        'voucher_code' => 'nullable|string|exists:vouchers,code',
    ]);

    // Cari transaksi dengan relasi voucher
    $transaction = Transaction::with('voucher')->findOrFail($transaction_id);

    // Hitung harga per kg berdasarkan jenis layanan
    $pricePerKg = match ($transaction->service_type) {
        'cuci_saja' => 10000,
        'cuci_setrika' => 15000,
        'express' => 25000,
        default => 0,
    };

    // Hitung harga awal
    $basePrice = $pricePerKg * $request->weight;

    // Cek validasi kode voucher yang dimasukkan admin
    $voucher = null;
    $discountRate = 0;

    if ($request->filled('voucher_code')) {
        $voucher = Voucher::where('code', $request->voucher_code)
            ->whereDate('valid_from', '<=', now())
            ->whereDate('valid_until', '>=', now())
            ->first();

        if ($voucher) {
            $discountRate = $voucher->discount / 100;
        } else {
            return back()->withErrors(['voucher_code' => 'Kode voucher tidak valid atau sudah kedaluwarsa.']);
        }
    }

    // Hitung harga akhir setelah diskon
    $finalPrice = $basePrice - ($basePrice * $discountRate);

    // Update transaksi dengan berat, harga, voucher, dan status
    $transaction->update([
        'weight' => $request->weight,
        'price' => $finalPrice,
        'voucher_id' => $voucher->voucher_id ?? null,
        'status' => 'pickup_confirmed', // Perbarui status menjadi 'pickup_confirmed'
    ]);

    // Redirect ke halaman pickup dengan pesan sukses
    return redirect()->route('admin.pickup.index')->with('success', 'Pesanan pickup berhasil diterima dengan berat ' . $request->weight . ' kg. Total harga: Rp ' . number_format($finalPrice, 0, ',', '.') . '.');
}


public function rejectPickup(Request $request, $transaction_id)
{
    $transaction = Transaction::findOrFail($transaction_id);

    $request->validate([
        'reason' => 'required|string|max:255',
    ]);

    $transaction->update([
        'status' => 'pickup_rejected',
        'rejection_reason' => $request->reason,
    ]);

    return redirect()->route('admin.pickup.index')->with('success', 'Pesanan berhasil ditolak dengan alasan.');
}


}
