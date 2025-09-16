<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $userVote = Vote::where('user_id', auth()->id())->first();
        return view('admin.dashboard', compact('users', 'userVote'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => isset($request->is_admin),
            'password' => Hash::make($request->password),
        ]);
    }
}
