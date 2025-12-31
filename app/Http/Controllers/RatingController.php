<?php

// app/Http/Controllers/RatingController.php
namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        Rating::updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $request->rating]
        );

        return response()->json(['message' => 'Rating berhasil disimpan']);
    }
}