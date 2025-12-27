<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Mood;
use App\Models\Tenant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard utama
     */
    public function index()
    {
        $stats = [
            'total_menus' => Menu::count(),
            'total_categories' => Category::count(),
            'total_moods' => Mood::count(),
            'total_tenants' => Tenant::count(),
        ];

        return view('dashboard.index', compact('stats'));
    }

    /**
     * Halaman pengelolaan menu
     */
    public function menus()
    {
        $menus = Menu::with(['tenant', 'category.mood'])->orderBy('created_at', 'desc')->get();
        $tenants = Tenant::all();
        $categories = Category::with('mood')->get();
        
        return view('dashboard.menus', compact('menus', 'tenants', 'categories'));
    }

    /**
     * Simpan menu baru
     */
    public function storeMenu(Request $request)
    {
        $data = $request->validate([
            'menu_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'tenant_id' => 'required|exists:tenants,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu', 'public');
            $data['image'] = 'storage/'.$path;
        }

        Menu::create($data);

        return redirect()->route('dashboard.menus')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Update menu
     */
    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'menu_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'tenant_id' => 'required|exists:tenants,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu', 'public');
            $data['image'] = 'storage/'.$path;
        }

        $menu->update($data);

        return redirect()->route('dashboard.menus')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Hapus menu
     */
    public function deleteMenu($id)
    {
        Menu::destroy($id);

        return redirect()->route('dashboard.menus')->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Halaman pengelolaan kategori mood
     */
    public function categories()
    {
        $categories = Category::with('mood')->orderBy('mood_id')->get();
        $moods = Mood::all();
        
        return view('dashboard.categories', compact('categories', 'moods'));
    }

    /**
     * Simpan kategori baru
     */
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:100',
            'mood_id' => 'required|exists:moods,id',
        ]);

        Category::create($data);

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update kategori
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'category_name' => 'required|string|max:100',
            'mood_id' => 'required|exists:moods,id',
        ]);

        $category->update($data);

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori
     */
    public function deleteCategory($id)
    {
        Category::destroy($id);

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Halaman pengelolaan mood
     */
    public function moods()
    {
        $moods = Mood::withCount('categories')->orderBy('id')->get();
        
        return view('dashboard.moods', compact('moods'));
    }

    /**
     * Simpan mood baru
     */
    public function storeMood(Request $request)
    {
        $data = $request->validate([
            'mood_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Mood::create($data);

        return redirect()->route('dashboard.moods')->with('success', 'Mood berhasil ditambahkan!');
    }

    /**
     * Update mood
     */
    public function updateMood(Request $request, $id)
    {
        $mood = Mood::findOrFail($id);

        $data = $request->validate([
            'mood_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $mood->update($data);

        return redirect()->route('dashboard.moods')->with('success', 'Mood berhasil diperbarui!');
    }

    /**
     * Hapus mood
     */
    public function deleteMood($id)
    {
        Mood::destroy($id);

        return redirect()->route('dashboard.moods')->with('success', 'Mood berhasil dihapus!');
    }
}
