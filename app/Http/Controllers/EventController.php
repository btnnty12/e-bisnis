<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        // tracking akses halaman list event (public)
        PageView::create([
            'user_id' => Auth::id(),
            'page' => 'event_list',
        ]);

        return Event::orderBy('id', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return Event::create($data);
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        // tracking akses halaman detail event (public)
        PageView::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'page' => 'event_detail',
        ]);

        return $event;
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $data = $request->validate([
            'event_name' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
        ]);

        $event->update($data);

        return $event;
    }

    public function destroy($id)
    {
        Event::destroy($id);

        return response()->json(['message' => 'Event dihapus']);
    }
}