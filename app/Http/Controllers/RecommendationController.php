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
        'senang'     => ['dessert', 'minuman'],
        'stress'     => ['comfort', 'hangat'],
        'lelah'      => ['berat'],
        'sedih'      => ['manis'],
        'excited'    => ['pedas'],
        'marah'      => ['pedas', 'berbumbu', 'goreng'],
        'biasa-aja'  => ['ringan', 'snack', 'salad'],
    ];

    public function recommendByMood($mood_id)
    {
        $mood = \App\Models\Mood::find($mood_id);

        if (! $mood) {
            return response()->json([], 404);
        }

        $key = strtolower(str_replace(' ', '-', $mood->mood_name));

        // If we have rule-based categories for this mood, prefer keyword matching
        if (! empty($this->rules[$key])) {
            $terms = $this->rules[$key];

            return \App\Models\Menu::whereHas('category', function ($q) use ($terms) {
                $q->where(function ($q2) use ($terms) {
                    foreach ($terms as $term) {
                        $q2->orWhereRaw('LOWER(category_name) LIKE ?', ['%' . strtolower($term) . '%']);
                    }
                });
            })->with('tenant')->get();
        }

        // Fallback: return menus whose category is associated with the mood
        return \App\Models\Menu::whereHas('category', function ($q) use ($mood_id) {
            $q->where('mood_id', $mood_id);
        })->with('tenant')->get();
    }
    public function index()
    {
        return Recommendation::with(['mood', 'category'])->get();
    }

    /**
     * Expose the rules for external use (routes, view logic, tests)
     *
     * @return array
     */
    public static function getRules(): array
    {
        return (new static)->rules;
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