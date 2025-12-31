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

    // =====================
    // INTERACTION (USER LOGIN)
    // =====================
    public function store(Request $request)
    {
        $data = $request->validate([
            'mood_id'  => 'required|exists:moods,id',
            'menu_id'  => 'required|exists:menus,id',
            'event_id' => 'nullable|exists:events,id',
        ]);

        return Interaction::create([
            'user_id'    => Auth::id(),
            'mood_id'    => $data['mood_id'],
            'menu_id'    => $data['menu_id'],
            'event_id'   => $data['event_id'] ?? null,
            'type'       => 'mood_click', // 🔥 PENTING
            'session_id' => null,
        ]);
    }

    // =====================
    // INTERACTION (PUBLIC / GUEST)
    // =====================
    public function storePublic(Request $request)
    {
        $request->validate([
            'mood_id' => 'required|exists:moods,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        return Interaction::create([
            'mood_id'    => $request->mood_id,
            'menu_id'    => $request->menu_id,
            'type'       => 'mood_click', // 🔥 PENTING
            'session_id' => session()->getId(),
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