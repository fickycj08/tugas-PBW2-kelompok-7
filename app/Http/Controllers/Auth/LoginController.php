<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Arahkan pengguna setelah login ke halaman pilih dashboard.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'kasir') {
            return redirect()->intended('/dashboard'); // Dashboard Kasir
        }
    
        if ($user->role === 'user') {
            return redirect()->intended('/user/dashboard'); // Dashboard User
        }
        \Log::info('Authenticated as: ' . $user->role . ', redirecting to ' . $this->redirectTo());

        return redirect('/choose-dashboard');
        
    }
    
}
