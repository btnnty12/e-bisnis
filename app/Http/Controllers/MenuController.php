<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return Menu::with(['tenant', 'category'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id'   => 'required|exists:tenants,id',
            'category_id' => 'required|exists:categories,id',
            'menu_name'   => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|string|max:255',
        ]);

        return Menu::create($data);
    }

    public function show(Menu $menu)
    {
        return $menu->load(['tenant', 'category']);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'tenant_id'   => 'required|exists:tenants,id',
            'category_id' => 'required|exists:categories,id',
            'menu_name'   => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|string|max:255',
        ]);

        $menu->update($data);
        return $menu;
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Deleted']);
    }
}