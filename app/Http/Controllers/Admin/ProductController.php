<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // =====================================================
    // DAFTAR PRODUK
    // =====================================================
    public function index(Request $request)
    {
        $query = Product::with('category');

        // =================================================
        // SEARCH
        // =================================================
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'code',
                    'like',
                    "%{$search}%"
                );
            });
        }

        // =================================================
        // FILTER KATEGORI
        // =================================================
        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        // =================================================
        // FILTER STOK MENIPIS
        // =================================================
        if ($request->stock_status === 'low') {
            $query->whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            );
        }

        // =================================================
        // AMBIL DATA PRODUK
        // =================================================
        $products = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // =================================================
        // HITUNG STOK MENIPIS
        // =================================================
        // Tidak mengikuti filter search/kategori.
        // Menghitung seluruh produk yang stoknya
        // <= minimum_stock.
        //
        // Stok 0 juga ikut dihitung.
        $lowStockCount = Product::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->count();

        // =================================================
        // KATEGORI
        // =================================================
        $categories = Category::orderBy('name')->get();

        // =================================================
        // VIEW
        // =================================================
        return view(
            'admin.products.index',
            compact(
                'products',
                'categories',
                'lowStockCount'
            )
        );
    }

    // =====================================================
    // FORM TAMBAH PRODUK
    // =====================================================
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'admin.products.create',
            compact('categories')
        );
    }

    // =====================================================
    // SIMPAN PRODUK
    // =====================================================
    public function store(Request $request)
    {
        // =================================================
        // VALIDASI
        // =================================================
        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);

        // =================================================
        // USER LOGIN
        // =================================================
        $user = $request->user();

        // =================================================
        // GENERATE KODE + SIMPAN PRODUK + STOCK MOVEMENT
        // =================================================
        $product = DB::transaction(function () use (
            $validated,
            $user
        ) {
            // =============================================
            // GENERATE KODE PRODUK
            // =============================================
            $lastProduct = Product::latest('id')->first();

            $number = $lastProduct
                ? $lastProduct->id + 1
                : 1;

            $code = 'PRD' . str_pad(
                $number,
                4,
                '0',
                STR_PAD_LEFT
            );

            // =============================================
            // SIMPAN PRODUK
            // =============================================
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'code' => $code,
                'name' => $validated['name'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'stock' => $validated['stock'],
                'minimum_stock' => $validated['minimum_stock'],
                'unit' => $validated['unit'] ?? 'pcs',
                'description' => $validated['description'] ?? null,
            ]);

            // =============================================
            // CATAT STOK AWAL
            // =============================================
            if ($product->stock > 0 && $user) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'type' => 'IN',
                    'quantity' => $product->stock,
                    'stock_before' => 0,
                    'stock_after' => $product->stock,
                    'description' => 'Stok awal barang',
                ]);
            }

            return $product;
        });

        // =================================================
        // ACTIVITY LOG
        // =================================================
        if ($user) {
            ActivityLogService::log(
                $user->id,
                'CREATE',
                'PRODUCT',
                'Menambahkan barang ' . $product->name,
                null,
                [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'stock' => $product->stock,
                ]
            );
        }

        // =================================================
        // REDIRECT
        // =================================================
        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Produk berhasil ditambahkan.'
            );
    }

    // =====================================================
    // DETAIL PRODUK
    // =====================================================
    public function show(Product $product)
    {
        $product->load('category');

        return view(
            'admin.products.show',
            compact('product')
        );
    }

    // =====================================================
    // FORM EDIT
    // =====================================================
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }

    // =====================================================
    // UPDATE PRODUK
    // =====================================================
    public function update(
        Request $request,
        Product $product
    ) {
        // =================================================
        // VALIDASI
        // =================================================
        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);

        // =================================================
        // DATA LAMA
        // =================================================
        $oldData = [
            'category_id' => $product->category_id,
            'code' => $product->code,
            'name' => $product->name,
            'purchase_price' => $product->purchase_price,
            'selling_price' => $product->selling_price,
            'stock' => $product->stock,
            'minimum_stock' => $product->minimum_stock,
            'unit' => $product->unit,
            'description' => $product->description,
        ];

        // =================================================
        // SIMPAN STOK LAMA
        // =================================================
        $oldStock = (int) $product->stock;
        $newStock = (int) $validated['stock'];

        // Selisih stok
        $stockDifference = $newStock - $oldStock;

        // User login
        $user = $request->user();

        // =================================================
        // UPDATE PRODUK + STOCK MOVEMENT
        // =================================================
        DB::transaction(function () use (
            $product,
            $validated,
            $oldStock,
            $newStock,
            $stockDifference,
            $user
        ) {
            // =============================================
            // UPDATE DATA PRODUK
            // =============================================
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'stock' => $newStock,
                'minimum_stock' => $validated['minimum_stock'],
                'unit' => $validated['unit'] ?? 'pcs',
                'description' => $validated['description'] ?? null,
            ]);

            // =============================================
            // CATAT PERGERAKAN STOK
            // =============================================
            if ($stockDifference != 0 && $user) {

                // -----------------------------------------
                // STOK BERTAMBAH
                // -----------------------------------------
                if ($stockDifference > 0) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'type' => 'IN',
                        'quantity' => $stockDifference,
                        'stock_before' => $oldStock,
                        'stock_after' => $newStock,
                        'description' => 'Penambahan stok barang',
                    ]);
                }

                // -----------------------------------------
                // STOK BERKURANG
                // -----------------------------------------
                else {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'type' => 'OUT',
                        'quantity' => abs($stockDifference),
                        'stock_before' => $oldStock,
                        'stock_after' => $newStock,
                        'description' => 'Pengurangan stok barang',
                    ]);
                }
            }
        });

        // =================================================
        // DATA BARU UNTUK ACTIVITY LOG
        // =================================================
        $newData = [
            'category_id' => $product->category_id,
            'code' => $product->code,
            'name' => $product->name,
            'purchase_price' => $product->purchase_price,
            'selling_price' => $product->selling_price,
            'stock' => $product->stock,
            'minimum_stock' => $product->minimum_stock,
            'unit' => $product->unit,
            'description' => $product->description,
        ];

        // =================================================
        // ACTIVITY LOG
        // =================================================
        if ($user) {
            ActivityLogService::log(
                $user->id,
                'UPDATE',
                'PRODUCT',
                'Mengubah barang ' . $product->name,
                $oldData,
                $newData
            );
        }

        // =================================================
        // REDIRECT
        // =================================================
        return redirect()
            ->route(
                'admin.products.show',
                $product
            )
            ->with(
                'success',
                'Produk berhasil diperbarui.'
            );
    }

    // =====================================================
    // HAPUS PRODUK
    // =====================================================
    public function destroy(
        Request $request,
        Product $product
    ) {
        // =================================================
        // DATA LAMA
        // =================================================
        $oldData = [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'code' => $product->code,
            'name' => $product->name,
            'stock' => $product->stock,
        ];

        // =================================================
        // NAMA PRODUK
        // =================================================
        $productName = $product->name;

        // =================================================
        // HAPUS PRODUK
        // =================================================
        $product->delete();

        // =================================================
        // ACTIVITY LOG
        // =================================================
        $user = $request->user();

        if ($user) {
            ActivityLogService::log(
                $user->id,
                'DELETE',
                'PRODUCT',
                'Menghapus barang ' . $productName,
                $oldData,
                null
            );
        }

        // =================================================
        // REDIRECT
        // =================================================
        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Produk berhasil dihapus.'
            );
    }
}