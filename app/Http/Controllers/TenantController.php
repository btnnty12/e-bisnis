<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TenantController extends Controller
{
    /**
     * ============================
     * TAMPILKAN HALAMAN TENANT
     * ============================
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user || $user->role === 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman tenant');
        }

        $tenants = Tenant::with('events')
            ->where('id', $user->tenant_id)
            ->orderBy('tenant_name')
            ->get();

        $events = Event::orderBy('event_name')->get();

        return view('home', compact('tenants', 'events'));
    }

    /**
     * ============================
     * TAMBAH TENANT KE EVENT
     * (FIX TOTAL)
     * ============================
     */
    public function addToEvent(Request $request)
{
    $user = auth()->user();

    if (!$user || $user->role === 'admin') {
        abort(403);
    }

    $data = $request->validate([
        'event_id'   => 'required|exists:events,id',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
        'active'     => 'nullable|boolean',
    ]);

    $tenant = Tenant::findOrFail($user->tenant_id);
    $event  = Event::findOrFail($data['event_id']);

    $payload = [
        'start_date' => $data['start_date'],
        'end_date'   => $data['end_date'],
        'active'     => $data['active'] ?? 0,
    ];

    // ✅ updateExistingPivot / attach
    if ($tenant->events()->where('events.id', $event->id)->exists()) {
        $tenant->events()->updateExistingPivot($event->id, $payload);
    } else {
        $tenant->events()->attach($event->id, $payload);
    }

    $pivot = $tenant->events()->where('events.id', $event->id)->first()->pivot;

    return response()->json([
        'success' => true,
        'tenant' => [
            'id'          => $tenant->id,
            'tenant_name' => $tenant->tenant_name,
            'location'    => $tenant->location,
        ],
        'event' => [
            'id'         => $event->id,
            'event_name' => $event->event_name,
        ],
        'pivot' => [
            'start_date' => Carbon::parse($pivot->start_date)->format('d-m-Y'),
            'end_date'   => Carbon::parse($pivot->end_date)->format('d-m-Y'),
            'active'     => (int) $pivot->active,
        ],
    ]);
}
}