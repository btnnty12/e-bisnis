<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function index()
    {
        return Interaction::with(['user', 'mood', 'menu'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mood_id' => 'required|exists:moods,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        return Interaction::create([
            'user_id' => Auth::id(),
            'mood_id' => $data['mood_id'],
            'menu_id' => $data['menu_id'],
        ]);
    }

    public function show($id)
    {
        return Interaction::with(['user', 'mood', 'menu'])->findOrFail($id);
    }

    public function destroy($id)
    {
        Interaction::destroy($id);

        return response()->json(['message' => 'Interaction dihapus']);
    }
}