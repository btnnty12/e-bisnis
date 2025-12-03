<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function index()
    {
        return Interaction::with(['user', 'mood', 'menu'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'mood_id' => 'required|exists:moods,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        return Interaction::create($data);
    }

    public function show(Interaction $interaction)
    {
        return $interaction->load(['user', 'mood', 'menu']);
    }

    public function destroy(Interaction $interaction)
    {
        $interaction->delete();
        return response()->json(['message' => 'Deleted']);
    }
}