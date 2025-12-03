<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        return Recommendation::with(['mood', 'category'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mood_id'     => 'required|exists:moods,id',
            'category_id' => 'required|exists:categories,id',
            'score'       => 'required|integer|min:0',
        ]);

        return Recommendation::create($data);
    }

    public function show(Recommendation $recommendation)
    {
        return $recommendation->load(['mood', 'category']);
    }

    public function update(Request $request, Recommendation $recommendation)
    {
        $data = $request->validate([
            'mood_id'     => 'required|exists:moods,id',
            'category_id' => 'required|exists:categories,id',
            'score'       => 'required|integer|min:0',
        ]);

        $recommendation->update($data);
        return $recommendation;
    }

    public function destroy(Recommendation $recommendation)
    {
        $recommendation->delete();
        return response()->json(['message' => 'Deleted']);
    }
}