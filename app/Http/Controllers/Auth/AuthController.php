<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // reset flag token saat login
            $request->session()->forget('is_token_verified');

            return Auth::user()->is_admin
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.token');
        }

        return redirect()->route('login')->with('error', 'Email atau password salah.');
    }


    public function token(Request $request)
    {
        $credentials = $request->validate([
            'token' => 'required'
        ]);
        $user = Auth::user();

        // cek token di tabel tokens
        $validToken = Token::where('user_id', $user->id)
            ->where('token', $credentials['token'])
            ->first();

        if ($validToken && $validToken->isValid()) {
            // tandai sudah dipakai (hangus)
            // $validToken->markAsUsed();
            $validToken->update([
                'expires_at' => now()->addMinutes(5)
            ]);
            // simpan flag token_verified di session
            $request->session()->put('is_token_verified', true);
            $request->session()->put('verified_token_id', $validToken->id);

            // return redirect()->route('user.dashboard');
            return redirect()->route('user.vote.index');
        }
        return redirect()->route('user.token')->with('error', 'Token tidak valid.');
    }

    public function logout(Request $request)
    {
        // $validToken->markAsUsed();
        if (Auth::user()->isAdmin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        } else {
            $tokenId = $request->session()->get('verified_token_id');
            $token = Token::find($tokenId);
            if ($token && $token->isValid()) {
                $token->markAsUsed();
            }
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }
    }
}
