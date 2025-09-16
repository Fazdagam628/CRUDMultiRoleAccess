<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Token;

class EnsureTokenIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $verified = $request->session()->get('is_token_verified', false);
        $tokenId  = $request->session()->get('verified_token_id',null);

        if (!$verified || !$tokenId) {
            return redirect()->route('user.token');
        }

        $token = Token::find($tokenId);

        // Jika token expired atau sudah dipakai → logout paksa
        if (!$token || $token->isExpired() || $token->used_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Sesi Anda berakhir, silakan login ulang.');
        }

        return $next($request);
    }
}
