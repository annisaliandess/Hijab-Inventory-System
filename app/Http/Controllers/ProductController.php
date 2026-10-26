<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk hijab dengan pencarian, filter kategori, dan Eager Loading
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        // Eager load relasi category
        $query = Product::with('category')->latest();

        // Fitur Pencarian Kata Kunci (Nama, SKU, Bahan, atau Warna)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%");
            });
        }

        // Filter Berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(8)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Form Tambah Produk Baru
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Simpan Produk Baru beserta Upload Gambar
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);

        // Upload Gambar jika ada file yang dipilih
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'hijab_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/products');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk hijab baru berhasil ditambahkan ke katalog!');
    }

    /**
     * Form Edit Produk
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui Data Produk & Ganti Foto jika ada foto baru
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Hapus foto lama dari penyimpanan jika ada
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $file = $request->file('image');
            $filename = 'hijab_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/products');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Data produk hijab berhasil diperbarui!');
    }

    /**
     * Hapus Produk dengan proteksi riwayat transaksi
     */
    public function destroy(Product $product)
    {
        // Proteksi integritas transaksi
        if ($product->saleItems()->count() > 0 || $product->purchaseItems()->count() > 0) {
            return back()->with('error', 'Produk ini tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        // Hapus file fisik gambar jika ada
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk hijab berhasil dihapus!');
    }
}