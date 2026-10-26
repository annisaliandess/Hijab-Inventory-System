<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Tampilkan daftar supplier beserta riwayat pembelian
     */
    public function index()
    {
        // withCount('purchases') untuk menghitung berapa kali kita restock dari supplier ini
        $suppliers = Supplier::withCount('purchases')->latest()->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Simpan supplier baru
     */
    public function store(SupplierRequest $request)
    {
        Supplier::create($request->validated());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Data pemasok baru berhasil disimpan!');
    }

    /**
     * Perbarui data supplier
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Data pemasok berhasil diperbarui!');
    }

    /**
     * Hapus supplier dengan proteksi riwayat transaksi
     */
    public function destroy(Supplier $supplier)
    {
        // Proteksi: Jangan hapus jika ada riwayat transaksi pembelian terkait
        if ($supplier->purchases()->count() > 0) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena memiliki riwayat pembelian barang.');
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Data pemasok berhasil dihapus!');
    }
}