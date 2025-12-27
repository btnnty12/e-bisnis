<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return Event::orderBy('id', 'desc')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        return Event::create($data);
    }

    public function show($id)
    {
        return Event::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $data = $request->validate([
            'event_name' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
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
