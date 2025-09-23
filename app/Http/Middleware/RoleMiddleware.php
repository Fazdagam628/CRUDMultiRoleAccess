<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // $verified = $request->session()->get('is_token_verified', false);
        // $loginId  = $request->session()->get('verified_token_id',null);
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();

        if ($role === 'admin' && $user->is_admin) {
            return $next($request);
        }

        if ($role === 'user' && !$user->is_admin) {
            // blokir jika used_at atau session expired
            if ($user->used_at || $user->sessionExpired()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Sesi Anda berakhir atau akun sudah dipakai.');
            }
            return $next($request);
        }

        // Jika tidak sesuai role
        abort(403, 'Unauthorized');
    }
}
