<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TenantController extends Controller
{
    /**
     * Tampilkan halaman tenant untuk user tenant
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman tenant');
        }

        // Ambil tenant milik user beserta relasi events
        $tenants = Tenant::with('events')
            ->where('id', $user->tenant_id)
            ->orderBy('tenant_name')
            ->get();

        // Ambil semua event untuk dropdown modal
        $events = Event::all();

        return view('home', compact('tenants', 'events'));
    }

    /**
     * Tambah tenant baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak bisa menambah tenant');
        }

        $data = $request->validate([
            'tenant_name' => 'required|string|max:100',
            'location'    => 'nullable|string|max:100',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        $data['start_date'] = $data['start_date'] ? Carbon::parse($data['start_date'])->format('Y-m-d') : null;
        $data['end_date']   = $data['end_date'] ? Carbon::parse($data['end_date'])->format('Y-m-d') : null;

        $tenant = Tenant::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tenant berhasil ditambahkan',
            'tenant'  => $tenant
        ]);
    }

    /**
     * Update tenant
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak bisa mengubah tenant');
        }

        $tenant = Tenant::where('id', $user->tenant_id)->findOrFail($id);

        $data = $request->validate([
            'tenant_name' => 'required|string|max:100',
            'location'    => 'nullable|string|max:100',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        $data['start_date'] = $data['start_date'] ? Carbon::parse($data['start_date'])->format('Y-m-d') : null;
        $data['end_date']   = $data['end_date'] ? Carbon::parse($data['end_date'])->format('Y-m-d') : null;

        $tenant->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tenant berhasil diperbarui',
            'tenant'  => $tenant
        ]);
    }

    /**
     * Hapus tenant
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak bisa menghapus tenant');
        }

        $tenant = Tenant::where('id', $user->tenant_id)->findOrFail($id);
        $tenant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tenant berhasil dihapus'
        ]);
    }

    /**
     * Tambah tenant ke event
     */
    public function addToEvent(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak bisa menambahkan tenant ke event');
        }

        $data = $request->validate([
            'tenant_id'  => 'required|exists:tenants,id',
            'event_id'   => 'required|exists:events,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'active'     => 'boolean',
        ]);

        $tenant = Tenant::where('id', $user->tenant_id)->findOrFail($data['tenant_id']);

        // Attach ke pivot table tenant_event
        $tenant->events()->syncWithoutDetaching([
            $data['event_id'] => [
                'start_date' => $data['start_date'] ?? now()->format('Y-m-d'),
                'end_date'   => $data['end_date'] ?? now()->addDays(5)->format('Y-m-d'),
                'active'     => $data['active'] ?? true,
            ]
        ]);

        // Ambil data event untuk JSON agar JS bisa update tabel
        $event = Event::find($data['event_id']);

        return response()->json([
            'success' => true,
            'message' => 'Tenant berhasil ditambahkan ke event',
            'tenant'  => [
                'id'          => $tenant->id,
                'tenant_name' => $tenant->tenant_name,
                'location'    => $tenant->location,
                'event_id'    => $event->id,
                'event_name'  => $event->event_name,
                'start_date'  => $data['start_date'] ?? now()->format('d-m-Y'),
                'end_date'    => $data['end_date'] ?? now()->addDays(5)->format('d-m-Y'),
                'active'      => $data['active'] ?? true,
            ]
        ]);
    }
}