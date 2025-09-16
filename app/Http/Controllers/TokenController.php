<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);

        $token = Token::create([
            'user_id' => $user->id,
            'token' => Str::random(12),
            'expires_at' => null
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Token berhasil dibuat untuk ' . $user->name)
            ->with('new_token', $token->token);
    }

    public function markAsUsed(Request $request)
    {
        $tokenId = $request->session()->get('verified_token_id');
        $token = Token::find($tokenId);
        if ($token && $token->isValid()) {
            $token->markAsUsed();
            // Hapus flag token_verified dari session
            $request->session()->forget('is_token_verified');
            $request->session()->forget('verified_token_id');
            return redirect()->route('user.token')->with('success', 'Token telah ditandai sebagai digunakan.');
        }

        return redirect()->route('user.token')->with('error', 'Token tidak valid atau sudah digunakan.');
    }
}
