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

    public function show(Category $category)
    {
        return $category->load('mood');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:100',
            'mood_id'       => 'required|exists:moods,id',
        ]);

        $category->update($data);
        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Deleted']);
    }
}