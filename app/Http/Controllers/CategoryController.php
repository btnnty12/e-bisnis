<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('mood')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:100',
            'mood_id'       => 'required|exists:moods,id',
        ]);

        return Category::create($data);
    }

    public function show($id)
    {
        return Category::with('mood')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'category_name' => 'sometimes|string|max:100',
            'mood_id'       => 'sometimes|exists:moods,id',
        ]);

        $category->update($data);

        return $category;
    }

    public function destroy($id)
    {
        Category::destroy($id);

        return response()->json(['message' => 'Category dihapus']);
    }
}