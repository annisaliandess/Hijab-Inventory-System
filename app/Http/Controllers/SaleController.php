<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleController extends Controller
{
    /**
     * Riwayat Transaksi Penjualan (Admin & Staff) dengan Pencarian & Filter Tanggal
     */
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'customer', 'items.product'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    /**
     * Form Kasir Penjualan POS (Staff & Admin)
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Simpan Transaksi Penjualan Kasir dengan DB::transaction & Pessimistic Locking
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'  => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash,transfer,qris'],
            'product_id'     => ['required', 'array', 'min:1'],
            'quantity'       => ['required', 'array', 'min:1'],
        ]);

        try {
            // DB::transaction menjamin atomisitas transaksi (ACID)
            $sale = DB::transaction(function () use ($request) {
                
                $totalAmount = 0;
                $itemsToInsert = [];

                // 1. Validasi Stok dengan Pessimistic Locking & Server-side Calculation
                foreach ($request->product_id as $index => $prodId) {
                    $qty = (int) $request->quantity[$index];
                    if ($qty <= 0) continue;

                    // Menggunakan lockForUpdate untuk mencegah race condition dan overselling
                    $product = Product::lockForUpdate()->findOrFail($prodId);

                    // Tolak jika kuantitas melebihi stok yang ada
                    if ($product->stock < $qty) {
                        throw new Exception("Stok untuk produk '{$product->name}' tidak mencukupi! Sisa stok hanya {$product->stock} pcs.");
                    }

                    $subtotal = $product->price * $qty;
                    $totalAmount += $subtotal;

                    $itemsToInsert[] = [
                        'product'    => $product,
                        'quantity'   => $qty,
                        'unit_price' => $product->price,
                        'subtotal'   => $subtotal,
                    ];
                }

                if (empty($itemsToInsert)) {
                    throw new Exception("Pilih minimal 1 produk dengan kuantitas lebih dari 0.");
                }

                // 2. Simpan Header Penjualan
                $sale = Sale::create([
                    'user_id'        => Auth::id(),
                    'customer_id'    => null,
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                    'customer_name'  => $request->customer_name,
                    'sale_date'      => now(),
                    'total_amount'   => $totalAmount,
                    'status'         => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => $request->payment_method,
                    'notes'          => $request->notes,
                ]);

                // 3. Simpan Detail Item & Kurangi Stok Konsisten
                foreach ($itemsToInsert as $item) {
                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $item['product']->id,
                        'quantity'   => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    // Otomatis kurangi stok produk
                    $item['product']->decrement('stock', $item['quantity']);
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale->id)
                ->with('success', 'Transaksi kasir berhasil dicatat! Stok produk telah berkurang otomatis.');

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Self-Checkout Mandiri oleh Customer dengan Pessimistic Locking
     */
    public function customerCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:cash,transfer,qris'],
            'product_id'     => ['required', 'array', 'min:1'],
            'quantity'       => ['required', 'array', 'min:1'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $user = Auth::user();
                $totalAmount = 0;
                $itemsToInsert = [];

                foreach ($request->product_id as $index => $prodId) {
                    $qty = (int) ($request->quantity[$index] ?? 0);
                    if ($qty <= 0) continue;

                    // Pessimistic Locking untuk mencegah race condition & overselling
                    $product = Product::lockForUpdate()->findOrFail($prodId);

                    // Validasi stok server-side
                    if ($product->stock < $qty) {
                        throw new Exception("Stok untuk produk '{$product->name}' tidak mencukupi! Sisa stok hanya {$product->stock} pcs.");
                    }

                    // Perhitungan total harga murni dari database (tamper-proof)
                    $subtotal = $product->price * $qty;
                    $totalAmount += $subtotal;

                    $itemsToInsert[] = [
                        'product'    => $product,
                        'quantity'   => $qty,
                        'unit_price' => $product->price,
                        'subtotal'   => $subtotal,
                    ];
                }

                if (empty($itemsToInsert)) {
                    throw new Exception("Pilih minimal 1 produk dengan kuantitas lebih dari 0.");
                }

                $sale = Sale::create([
                    'user_id'        => null,
                    'customer_id'    => $user->id,
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
                    'customer_name'  => $user->name,
                    'sale_date'      => now(),
                    'total_amount'   => $totalAmount,
                    'status'         => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => $request->payment_method,
                    'notes'          => $request->notes ?? ('Pesanan online oleh ' . $user->name),
                ]);

                foreach ($itemsToInsert as $item) {
                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $item['product']->id,
                        'quantity'   => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    $item['product']->decrement('stock', $item['quantity']);
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale->id)
                ->with('success', 'Pesanan Anda berhasil dibuat dan stok produk telah diamankan!');

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Riwayat Pesanan Pribadi Khusus Customer Terisolasi
     */
    public function customerOrders()
    {
        $sales = Sale::where('customer_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('pelanggan.orders', compact('sales'));
    }

    /**
     * Lihat Struk / Invoice Penjualan
     * Dilindungi SalePolicy: Pelanggan HANYA bisa melihat pesanannya sendiri (403 Forbidden jika orang lain)
     */
    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);

        $sale->load(['user', 'customer', 'items.product']);
        return view('sales.show', compact('sale'));
    }
}
