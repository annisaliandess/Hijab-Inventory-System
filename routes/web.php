<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Redirect awal sesuai role pengguna
Route::get('/', function () {
    if (auth()->check()) {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }
        if ($user->isCustomer() || $user->isPelanggan()) {
            return redirect()->route('pelanggan.dashboard');
        }
        return redirect()->route('katalog');
    }
    return redirect()->route('login');
});

// Otentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Katalog Publik & Terbuka
Route::get('/katalog', function (Request $request) {
    $categories = Category::withCount('products')->get();
    $query = Product::with('category');

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('material', 'like', "%{$search}%");
        });
    }

    $products = $query->latest()->paginate(12)->withQueryString();
    return view('katalog', compact('categories', 'products'));
})->name('katalog');

// Modul Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalSales = Sale::sum('total_amount');
        $salesCount = Sale::count();
        $totalProducts = Product::count();
        $lowStockProducts = Product::with('category')->whereColumn('stock', '<=', 'minimum_stock')->get();
        $recentSales = Sale::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalSales', 'salesCount', 'totalProducts', 'lowStockProducts', 'recentSales'));
    })->name('dashboard');

    // Dibatasi sesuai method yang ada di controller (hanya index, store, update, destroy)
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('suppliers', SupplierController::class)->except(['create', 'show', 'edit']);
    Route::resource('products', ProductController::class)->except(['show']);
});

// Transaksi (Admin & Staf Kasir)
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store']);
    Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store']);
});

// Modul Staf
Route::middleware(['auth', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        $todaySalesCount = Sale::whereDate('created_at', today())->count();
        $todaySalesAmount = Sale::whereDate('created_at', today())->sum('total_amount');
        $recentSales = Sale::with('user')->latest()->take(5)->get();

        return view('staff.dashboard', compact('todaySalesCount', 'todaySalesAmount', 'recentSales'));
    })->name('dashboard');
});

// Modul Pelanggan / Customer
Route::middleware(['auth', 'role:customer,pelanggan'])->group(function () {
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/dashboard', function () {
            $categories = Category::withCount('products')->get();
            $featuredProducts = Product::with('category')->latest()->take(4)->get();
            return view('pelanggan.dashboard', compact('categories', 'featuredProducts'));
        })->name('dashboard');

        Route::get('/orders', [SaleController::class, 'customerOrders'])->name('orders');
        Route::post('/checkout', [SaleController::class, 'customerCheckout'])->name('checkout');
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/orders', [SaleController::class, 'customerOrders'])->name('orders');
        Route::post('/checkout', [SaleController::class, 'customerCheckout'])->name('checkout');
        Route::get('/orders/{sale}', [SaleController::class, 'show'])->name('orders.show');
    });
});

// Detail Nota Transaksi (Bisa diakses Admin, Staff, dan Customer pemilik transaksi via SalePolicy)
Route::middleware(['auth'])->group(function () {
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
});
