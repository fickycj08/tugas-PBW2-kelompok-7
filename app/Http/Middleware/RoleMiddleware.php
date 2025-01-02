<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{public function handle(Request $request, Closure $next, $role)
    {
        \Log::info('RoleMiddleware executed for user: ' . (Auth::check() ? Auth::user()->role : 'Not Authenticated'));
        \Log::info('Required Role: ' . $role);
    
        if (Auth::check() && Auth::user()->role === $role) {
            \Log::info('Access granted for role: ' . $role);
            return $next($request);
        }
    
        \Log::info('Access denied. Redirecting...');
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
    
}
