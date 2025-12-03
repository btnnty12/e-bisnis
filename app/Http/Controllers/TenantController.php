<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return Tenant::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_name' => 'required|string|max:100',
            'location'    => 'nullable|string|max:100',
        ]);

        return Tenant::create($data);
    }

    public function show(Tenant $tenant)
    {
        return $tenant;
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'tenant_name' => 'required|string|max:100',
            'location'    => 'nullable|string|max:100',
        ]);

        $tenant->update($data);
        return $tenant;
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json(['message' => 'Deleted']);
    }
}