<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Token;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function index()
    {
        $candidate = Candidate::all();
        $userVote = Vote::where('user_id', auth()->id())->first();
        $user = Auth::user();
        return view('user.vote.index', compact('candidate', 'userVote', 'user'));
    }

    public function getData()
    {
        // Ambil semua kandidat dan hitung jumlah vote masing-masing
        $candidates = Candidate::withCount('votes')->get();

        $labels = $candidates->pluck('no_urut');
        $data = $candidates->pluck('votes_count');

        return response()->json([
            'labels' => $labels->toArray(),
            'data' => $data->toArray()
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id'
        ]);
        // $tokenId = $request->session()->get('verified_token_id');
        // $token = Token::find($tokenId);
        // Cek apakah user sudah pernah vote
        $existingVote = Vote::where('user_id', auth()->id())->first();

        if ($existingVote) {
            return redirect()->back()->with('error', 'Anda sudah pernah memberikan suara.');
        }

        // Simpan Vote
        Vote::create([
            'user_id' => auth()->id(),
            'candidate_id' => $request->candidate_id
        ]);

        // if ($token && $token->isValid()) {
        //     $token->markAsUsed();
        //     // Hapus flag token_verified dari session
        //     $request->session()->forget('is_token_verified');
        //     $request->session()->forget('verified_token_id');
        //     if ($existingVote) {
        //         return redirect()->back()->with('error', 'Anda sudah pernah memberikan suara.');
        //     }
        //     return redirect()->back()->with('success', 'Voting berhasil!');
        // }
        // return redirect()->back()->with('success', 'Voting berhasil!');
        // return redirect()->back()->with('error', 'Voting error!');

        // Tandai user sudah terpakai (used_at) → tidak bisa login lagi
        $user = Auth::user();
        $user->markSessionUsed();

        // logout user setelah voting sukses
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Voting berhasil! Akun Anda sekarang tidak bisa digunakan lagi.');
    }
    public function results()
    {
        // $results = Candidate::withCount('votes')->orderBy('no_urut')->get();
        // return view('admin.votes.results', compact('results'));
        return view('admin.votes.results');
    }

    public function reset(Request $request)
    {
        $vote = Vote::where('user_id', $request->user_id)->first();
        if ($vote) {
            $vote->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Vote Anda sudah direset, silakan voting lagi.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'Anda belum pernah voting.');
    }

    public function resetAll()
    {
        Vote::truncate(); // kosongkan tabel votes
        return redirect()->route('admin.dashboard')
            ->with('success', 'Semua vote berhasil direset.');
    }
}
