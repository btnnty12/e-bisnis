<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return Tenant::with('menus')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_name' => 'required|string|max:100',
            'location'    => 'nullable|string|max:100',
        ]);

        return Tenant::create($data);
    }

    public function show($id)
    {
        return Tenant::with('menus')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $data = $request->validate([
            'tenant_name' => 'sometimes|string|max:100',
            'location'    => 'nullable|string|max:100',
        ]);

        $tenant->update($data);

        return $tenant;
    }

    public function destroy($id)
    {
        Tenant::destroy($id);

        return response()->json(['message' => 'Tenant dihapus']);
    }
}