<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    // =====================================================
    // LAPORAN STOK
    // =====================================================

    public function index(Request $request)
    {
        // =================================================
        // VALIDASI FILTER
        // =================================================

        $request->validate([
            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'type' => [
                'nullable',
                'in:IN,OUT',
            ],
        ]);


        // =================================================
        // QUERY PRODUK
        // =================================================

        $products = Product::with('category')

            ->when(
                $request->filled('category_id'),
                function ($query) use ($request) {

                    $query->where(
                        'category_id',
                        $request->category_id
                    );
                }
            )

            ->orderBy('name')

            ->get();


        // =================================================
        // RINGKASAN
        // =================================================

        $totalProducts =
            $products->count();


        $totalStock =
            $products->sum('stock');


        $lowStockProducts =
            $products->filter(function ($product) {

                return $product->stock <=
                    $product->minimum_stock;

            })->count();


        $totalInventoryValue =
            $products->sum(function ($product) {

                return
                    $product->stock *
                    $product->purchase_price;

            });


        // =================================================
        // STATUS STOK
        // =================================================

        $outOfStockProducts =
            $products->filter(function ($product) {

                return $product->stock <= 0;

            })->count();


        $safeStockProducts =
            $products->filter(function ($product) {

                return
                    $product->stock >
                    $product->minimum_stock;

            })->count();


        // =================================================
        // QUERY RIWAYAT PERGERAKAN STOK
        // =================================================

        $movements =
            StockMovement::with([
                'product.category',
                'user',
            ])

            // FILTER KATEGORI
            ->when(
                $request->filled('category_id'),
                function ($query) use ($request) {

                    $query->whereHas(
                        'product',
                        function ($productQuery) use ($request) {

                            $productQuery->where(
                                'category_id',
                                $request->category_id
                            );
                        }
                    );
                }
            )

            // FILTER TIPE
            ->when(
                $request->filled('type'),
                function ($query) use ($request) {

                    $query->where(
                        'type',
                        $request->type
                    );
                }
            )

            // FILTER TANGGAL MULAI
            ->when(
                $request->filled('start_date'),
                function ($query) use ($request) {

                    $query->whereDate(
                        'created_at',
                        '>=',
                        $request->start_date
                    );
                }
            )

            // FILTER TANGGAL AKHIR
            ->when(
                $request->filled('end_date'),
                function ($query) use ($request) {

                    $query->whereDate(
                        'created_at',
                        '<=',
                        $request->end_date
                    );
                }
            )

            ->latest()

            ->get();


        // =================================================
        // TOTAL PERGERAKAN
        // =================================================

        $totalStockIn =
            $movements
                ->where('type', 'IN')
                ->sum('quantity');


        $totalStockOut =
            $movements
                ->where('type', 'OUT')
                ->sum('quantity');


        // =================================================
        // DATA PRODUK
        // =================================================

        $productData =
            $products->map(function ($product) {

                return [

                    'id' =>
                        $product->id,

                    'code' =>
                        $product->code,

                    'name' =>
                        $product->name,

                    'category' =>
                        $product->category
                            ? $product->category->name
                            : null,

                    'purchase_price' =>
                        $product->purchase_price,

                    'selling_price' =>
                        $product->selling_price,

                    'stock' =>
                        $product->stock,

                    'minimum_stock' =>
                        $product->minimum_stock,

                    'unit' =>
                        $product->unit,

                    'inventory_value' =>
                        $product->stock *
                        $product->purchase_price,

                    'stock_status' =>
                        $product->stock <= 0
                            ? 'out_of_stock'
                            : (
                                $product->stock <=
                                $product->minimum_stock
                                    ? 'low'
                                    : 'safe'
                            ),
                ];
            });


        // =================================================
        // DATA MOVEMENT
        // =================================================

        $movementData =
            $movements->map(function ($movement) {

                return [

                    'id' =>
                        $movement->id,

                    'date' =>
                        $movement->created_at,

                    'product' =>
                        $movement->product
                            ? [
                                'id' =>
                                    $movement->product->id,

                                'code' =>
                                    $movement->product->code,

                                'name' =>
                                    $movement->product->name,

                                'category' =>
                                    $movement->product->category
                                        ? $movement->product->category->name
                                        : null,
                            ]
                            : null,

                    'user' =>
                        $movement->user
                            ? [
                                'id' =>
                                    $movement->user->id,

                                'name' =>
                                    $movement->user->name,

                                'role' =>
                                    $movement->user->role,
                            ]
                            : null,

                    'type' =>
                        $movement->type,

                    'quantity' =>
                        $movement->quantity,

                    'stock_before' =>
                        $movement->stock_before,

                    'stock_after' =>
                        $movement->stock_after,

                    'description' =>
                        $movement->description,
                ];
            });


        // =================================================
        // RESPONSE
        // =================================================

        return response()->json([

            'message' =>
                'Laporan stok berhasil diambil',

            'data' => [

                // =========================================
                // FILTER
                // =========================================

                'filter' => [

                    'category_id' =>
                        $request->category_id,

                    'start_date' =>
                        $request->start_date,

                    'end_date' =>
                        $request->end_date,

                    'type' =>
                        $request->type,
                ],


                // =========================================
                // SUMMARY
                // =========================================

                'summary' => [

                    'total_products' =>
                        $totalProducts,

                    'total_stock' =>
                        $totalStock,

                    'low_stock_products' =>
                        $lowStockProducts,

                    'out_of_stock_products' =>
                        $outOfStockProducts,

                    'safe_stock_products' =>
                        $safeStockProducts,

                    'total_inventory_value' =>
                        $totalInventoryValue,

                    'total_stock_in' =>
                        $totalStockIn,

                    'total_stock_out' =>
                        $totalStockOut,
                ],


                // =========================================
                // PRODUCTS
                // =========================================

                'products' =>
                    $productData,


                // =========================================
                // STOCK MOVEMENTS
                // =========================================

                'movements' =>
                    $movementData,
            ],
        ]);
    }
}