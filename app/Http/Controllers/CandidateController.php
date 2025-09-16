<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();
        return view('admin.candidates.index', compact('candidates'));
    }
    public function create()
    {
        return view('admin.candidates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'candidate_photo' => 'required|image|max:2048|mimes:png,jpg,jpeg',
            'leader_name'    => 'required|string|max:255',
            'coleader_name'  => 'required|string|max:255',
            'vision'         => 'required|string',
            'mission'        => 'required|string',
            'no_urut'        => 'required|string',
        ]);

        // Handle file upload
        if ($request->hasFile('candidate_photo')) {
            $file = $request->file('candidate_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('candidates', $filename, 'public');
        } else {
            return back()->withErrors(['candidate_photo' => 'Photo is required.'])->withInput();
        }

        // Create candidate
        Candidate::create([
            'candidate_photo' => $filePath,
            'leader_name'    => $request->input('leader_name'),
            'coleader_name'  => $request->input('coleader_name'),
            'vision'         => $request->input('vision'),
            'mission'        => $request->input('mission'),
            'no_urut'        => $request->input('no_urut'),
        ]);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate created successfully.');
    }

    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('admin.candidates.update', compact('candidate'));
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'candidate_photo' => 'nullable|image|max:2048|mimes:png,jpg,jpeg',
            'leader_name'    => 'required|string|max:255',
            'coleader_name'  => 'required|string|max:255',
            'vision'         => 'required|string',
            'mission'        => 'required|string',
            'no_urut'        => 'required|integer',
        ]);
        $data = [
            'leader_name'    => $request->input('leader_name'),
            'coleader_name'  => $request->input('coleader_name'),
            'vision'         => $request->input('vision'),
            'mission'        => $request->input('mission'),
            'no_urut'        => $request->input('no_urut'),
        ];

        // Handle file upload jika ada file baru
        if ($request->hasFile('candidate_photo')) {
            $file = $request->file('candidate_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('candidates', $filename, 'public');
            $data['candidate_photo'] = $filePath;
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate update successfully.');
    }
    public function delete($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate delete successfully.');
    }
}
