<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth; // Untuk autentikasi

class PaymentController extends Controller
{
    // Menampilkan halaman pembayaran
    public function show($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        return view('user.payment', compact('transaction'));
    }

    // Proses pembayaran
    public function process(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        // Validasi input metode pembayaran
        $request->validate([
            'payment_method' => 'required|in:qris,bank_transfer,e_wallet,balance',
        ]);

        // Logika pembayaran berdasarkan metode
        if ($request->payment_method === 'balance') {
            $user = Auth::user();

            // Cek saldo pengguna
            if ($user->balance < $transaction->price) {
                return back()->withErrors(['payment_method' => 'Saldo Anda tidak mencukupi untuk pembayaran.']);
            }

            // Kurangi saldo pengguna
            $user->balance -= $transaction->price;
            $user->save();

            // Perbarui status transaksi
            $transaction->update([
                'payment_status' => 'success',
                'payment_method' => 'balance',
                'paid_at' => now(),
            ]);

            return redirect()->route('user.dashboard')->with('success', 'Pembayaran berhasil menggunakan saldo akun!');
        }

        // Untuk metode pembayaran lainnya
        $transaction->update([
            'payment_status' => 'success',
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Pembayaran berhasil!');
    }
}
