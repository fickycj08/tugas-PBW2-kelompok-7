<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login

        // Data untuk dashboard
        $activeOrders = Transaction::where('user_id', $userId)
            ->whereNotIn('status', ['selesai', 'diambil'])
            ->count();

        $completedOrders = Transaction::where('user_id', $userId)
            ->where('status', 'selesai')
            ->count();

        $balance = Auth::user()->balance ?? 0; // Pastikan kolom `balance` ada di tabel `users`
        $orders = Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('user.dashboard', compact('activeOrders', 'completedOrders', 'balance', 'orders'));
    }
}
