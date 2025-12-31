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

    // Halaman explore
    public function explore()
    {
        $moods = Mood::with([
            'categories.menus.tenant'
        ])->get();

        return view('explore', compact('moods'));
    }

    // AJAX endpoint untuk reload menu
    public function ajaxMenus(Request $request, $moodId)
    {
        $categoryId = $request->query('category');

        $menus = \App\Models\Menu::with(['tenant', 'category'])
            ->whereHas('category', function($q) use ($moodId, $categoryId) {
                $q->where('mood_id', $moodId);
                if ($categoryId && $categoryId !== 'all') {
                    $q->where('id', $categoryId);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($menu){
                return [
                    'id' => $menu->id,
                    'menu_name' => $menu->menu_name,
                    'description' => $menu->description,
                    'category_id' => $menu->category_id,
                    'category_name' => $menu->category->category_name ?? '',
                    'tenant_name' => $menu->tenant->tenant_name ?? '',
                    'location' => $menu->tenant->location ?? '',
                    'image' => $menu->image,
                    'price_formatted' => number_format($menu->price ?? 0,0,',','.'),
                ];
            });

        return response()->json($menus);
    }
}