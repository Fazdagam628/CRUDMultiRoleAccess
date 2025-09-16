<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Models\Token;

class VoteController extends Controller
{
    public function index()
    {
        $candidate = Candidate::all();
        $userVote = Vote::where('user_id', auth()->id())->first();
        return view('user.vote.index', compact('candidate', 'userVote'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id'
        ]);
        $tokenId = $request->session()->get('verified_token_id');
        $token = Token::find($tokenId);
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
        return redirect()->back()->with('success', 'Voting berhasil!');
        // return redirect()->back()->with('error', 'Voting error!');
    }
    public function results()
    {
        $results = Candidate::withCount('votes')->orderBy('no_urut')->get();
        return view('admin.votes.results', compact('results'));
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
