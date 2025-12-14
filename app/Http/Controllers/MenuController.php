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
            'image'       => 'nullable|string',
        ]);

        return Menu::create($data);
    }

    public function show($id)
    {
        return Menu::with(['tenant', 'category'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $menu->update($request->only([
            'tenant_id',
            'category_id',
            'menu_name',
            'price',
            'description',
            'image'
        ]));

        return $menu;
    }

    public function destroy($id)
    {
        Menu::destroy($id);

        return response()->json([
            'message' => 'Menu berhasil dihapus'
        ]);
    }
}