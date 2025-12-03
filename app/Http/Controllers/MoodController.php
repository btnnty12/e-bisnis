<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    /**
     * Display a listing of the moods.
     */
    public function index()
    {
        // Ambil data mood milik user yang sedang login
        $moods = Mood::where('user_id', auth()->id())->get();

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

    /**
     * Store a newly created mood.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mood' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $mood = Mood::create([
            'user_id' => auth()->id(),
            'mood' => $request->mood,
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mood berhasil dibuat',
            'data' => $mood
        ], 201);
    }

    /**
     * Display a specific mood.
     */
    public function show($id)
    {
        $mood = Mood::where('user_id', auth()->id())->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $mood
        ]);
    }

    /**
     * Update a mood.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'mood' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $mood = Mood::where('user_id', auth()->id())->findOrFail($id);

        $mood->update([
            'mood' => $request->mood,
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mood berhasil diperbarui',
            'data' => $mood
        ]);
    }

    /**
     * Remove a mood.
     */
    public function destroy($id)
    {
        $mood = Mood::where('user_id', auth()->id())->findOrFail($id);
        $mood->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mood berhasil dihapus'
        ]);
    }
}