<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Rules sederhana untuk mesin rekomendasi
     * Maps mood ke kategori makanan yang direkomendasikan
     */
    protected $rules = [
        'senang'  => ['dessert', 'minuman'],
        'stress'  => ['comfort', 'hangat'],
        'lelah'   => ['berat'],
        'sedih'   => ['manis'],
        'excited' => ['pedas'],
    ];
    public function recommendByMood($mood_id)
    {
        return \App\Models\Menu::whereHas('category', function ($q) use ($mood_id) {
            $q->where('mood_id', $mood_id);
        })->with('tenant')->get();
    }
    public function index()
    {
        return Recommendation::with(['mood', 'category'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mood_id'     => 'required|exists:moods,id',
            'category_id' => 'required|exists:categories,id',
            'score'       => 'required|integer',
        ]);

        return Recommendation::create($data);
    }

    public function show($id)
    {
        return Recommendation::with(['mood', 'category'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $rec = Recommendation::findOrFail($id);

        $data = $request->validate([
            'mood_id'     => 'sometimes|exists:moods,id',
            'category_id' => 'sometimes|exists:categories,id',
            'score'       => 'sometimes|integer',
        ]);

        $rec->update($data);

        return $rec;
    }

    public function destroy($id)
    {
        Recommendation::destroy($id);

        return response()->json(['message' => 'Recommendation dihapus']);
    }
}