<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Vote;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $userVote = Vote::where('user_id', auth()->id())->first();
        return view('admin.dashboard', compact('users', 'userVote'));
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Log::info('Proses import dimulai...');

        Excel::import(new UsersImport, $request->file('file'));

        // Log::info('Proses import selesai.');

        return redirect()->back()->with('success', 'Data berhasil diimport!');
    }

    public function resetAccount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        if ($user) {
            // Hapus vote terkait user
            Vote::where('user_id', $user->id)->delete();
            // Reset used_at dan session
            $user->used_at = null;
            $user->expires_at = null;
            $user->save();

            return redirect()->back()->with('success', 'Akun ' . $user->name . ' berhasil direset.');
        }

        return redirect()->back()->with('error', 'Akun tidak ditemukan.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email',
            // 'password' => 'required|string|min:6'
            'token' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            // 'email' => $request->email,
            'is_admin' => isset($request->is_admin),
            // 'password' => Hash::make($request->password),
            'token' => $request->token,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }
}
