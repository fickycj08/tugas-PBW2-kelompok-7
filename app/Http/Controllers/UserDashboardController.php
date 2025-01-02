<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class UserDashboardController extends Controller
{ 
    public function index()
    {
        $user = auth()->user();
        $saldo = $user->balance ?? 0; // Jika ada fitur saldo
        $totalTransaksi = Transaction::where('user_id', $user->id)->count();
        $pesananAktif = Transaction::where('user_id', $user->id)->whereNotIn('status', ['selesai', 'diambil'])->get();
        $pesananTerbaru = Transaction::where('user_id', $user->id)->latest()->take(5)->get();

        return view('user.dashboard', compact('user', 'saldo', 'totalTransaksi', 'pesananAktif', 'pesananTerbaru'));
    }
    public function userDashboard()
{
    $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login

    $activeOrders = Transaction::where('user_id', $userId)->where('status', '!=', 'selesai')->count();
    $completedOrders = Transaction::where('user_id', $userId)->where('status', 'selesai')->count();
    $balance = Auth::user()->balance; // Pastikan kolom `balance` ada di tabel `users`
    $orders = Transaction::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

    return view('user_dashboard', compact('activeOrders', 'completedOrders', 'balance', 'orders'));
}
}
