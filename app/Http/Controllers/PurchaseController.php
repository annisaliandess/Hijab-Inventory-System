<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseController extends Controller
{
    /**
     * Riwayat Pembelian Restock dengan Pencarian & Filter Tanggal
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items.product'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $purchases = $query->paginate(10)->withQueryString();
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Form Input Barang Masuk dari Supplier
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Simpan Pembelian & Tambah Stok Otomatis dengan DB::transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => ['required', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'product_id'    => ['required', 'array', 'min:1'],
            'quantity'      => ['required', 'array', 'min:1'],
            'unit_cost'     => ['required', 'array', 'min:1'],
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $items = [];

                foreach ($request->product_id as $index => $prodId) {
                    $qty = (int) $request->quantity[$index];
                    $cost = (float) $request->unit_cost[$index];
                    if ($qty <= 0) continue;

                    $subtotal = $qty * $cost;
                    $totalAmount += $subtotal;

                    $items[] = [
                        'product_id' => $prodId,
                        'quantity'   => $qty,
                        'unit_cost'  => $cost,
                        'subtotal'   => $subtotal,
                    ];
                }

                if (empty($items)) {
                    throw new Exception("Masukkan minimal 1 produk dengan jumlah barang lebih dari 0.");
                }

                // 1. Simpan Header Pembelian
                $purchase = Purchase::create([
                    'supplier_id'    => $request->supplier_id,
                    'user_id'        => Auth::id(),
                    'invoice_number' => 'PUR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                    'purchase_date'  => $request->purchase_date,
                    'total_amount'   => $totalAmount,
                    'notes'          => $request->notes,
                ]);

                // 2. Simpan Detail Item & Tambah Stok Otomatis
                foreach ($items as $item) {
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id'  => $item['product_id'],
                        'quantity'    => $item['quantity'],
                        'unit_cost'   => $item['unit_cost'],
                        'subtotal'    => $item['subtotal'],
                    ]);

                    // Otomatis tambah kuantitas stok produk
                    Product::where('id', $item['product_id'])->increment('stock', $item['quantity']);
                }

                return $purchase;
            });

            return redirect()->route('purchases.index')
                ->with('success', 'Transaksi restock berhasil dicatat! Stok barang otomatis bertambah.');

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}