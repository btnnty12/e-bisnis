<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    public function index()
    {
        return response()->json(
            Mood::with('categories')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mood_name'   => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $mood = Mood::create($data);

        return response()->json([
            'message' => 'Mood berhasil dibuat',
            'data' => $mood
        ], 201);
    }

    public function show($id)
    {
        return response()->json(
            Mood::with('categories')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $mood = Mood::findOrFail($id);

        $data = $request->validate([
            'mood_name'   => 'sometimes|string|max:50',
            'description' => 'nullable|string',
        ]);

        $mood->update($data);

        return response()->json([
            'message' => 'Mood berhasil diperbarui',
            'data' => $mood
        ]);
    }

    public function destroy($id)
    {
        Mood::destroy($id);

        return response()->json([
            'message' => 'Mood berhasil dihapus'
        ]);
    }
}