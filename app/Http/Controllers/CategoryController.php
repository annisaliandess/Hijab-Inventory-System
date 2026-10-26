<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori beserta jumlah produk di dalamnya
     */
    public function index()
    {
        // withCount('products') untuk menghitung otomatis jumlah hijab di tiap kategori
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Simpan kategori baru ke database
     */
    public function store(CategoryRequest $request)
    {
        Category::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name), // Contoh: "Pashmina Silk" -> "pashmina-silk"
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori hijab baru berhasil ditambahkan!');
    }

    /**
     * Perbarui data kategori
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori hijab berhasil diperbarui!');
    }

    /**
     * Hapus kategori dengan proteksi integritas data
     */
    public function destroy(Category $category)
    {
        // Proteksi: Jika kategori masih memiliki produk, tolak penghapusan!
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memuat produk hijab aktif.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}