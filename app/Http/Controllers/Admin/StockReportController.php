<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        // FILTER
        // =================================================

        $categoryId = $request->input('category_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');


        // =================================================
        // PRODUK
        // =================================================

        $productQuery = Product::with('category');

        if ($categoryId) {

            $productQuery->where(
                'category_id',
                $categoryId
            );
        }

        $products = $productQuery
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

        $outOfStockProducts =
            $products->filter(function ($product) {

                return $product->stock <= 0;

            })->count();

        $safeStockProducts =
            $products->filter(function ($product) {

                return $product->stock >
                    $product->minimum_stock;

            })->count();

        $totalInventoryValue =
            $products->sum(function ($product) {

                return $product->stock *
                    $product->purchase_price;

            });


        // =================================================
        // STOCK MOVEMENTS
        // =================================================

        $movementQuery =
            StockMovement::with([
                'product.category',
                'user',
            ]);


        // FILTER KATEGORI

        if ($categoryId) {

            $movementQuery->whereHas(
                'product',
                function ($query) use ($categoryId) {

                    $query->where(
                        'category_id',
                        $categoryId
                    );
                }
            );
        }


        // FILTER TIPE

        if ($type) {

            $movementQuery->where(
                'type',
                $type
            );
        }


        // FILTER TANGGAL MULAI

        if ($startDate) {

            $movementQuery->whereDate(
                'created_at',
                '>=',
                $startDate
            );
        }


        // FILTER TANGGAL AKHIR

        if ($endDate) {

            $movementQuery->whereDate(
                'created_at',
                '<=',
                $endDate
            );
        }


        $movements =
            $movementQuery
                ->latest()
                ->paginate(15)
                ->withQueryString();


        // =================================================
        // TOTAL MOVEMENT
        // =================================================

        $movementSummaryQuery =
            StockMovement::query();


        // FILTER KATEGORI

        if ($categoryId) {

            $movementSummaryQuery->whereHas(
                'product',
                function ($query) use ($categoryId) {

                    $query->where(
                        'category_id',
                        $categoryId
                    );
                }
            );
        }


        // FILTER TIPE

        if ($type) {

            $movementSummaryQuery->where(
                'type',
                $type
            );
        }


        // FILTER TANGGAL

        if ($startDate) {

            $movementSummaryQuery->whereDate(
                'created_at',
                '>=',
                $startDate
            );
        }


        if ($endDate) {

            $movementSummaryQuery->whereDate(
                'created_at',
                '<=',
                $endDate
            );
        }


        $filteredMovements =
            $movementSummaryQuery->get();


        $totalStockIn =
            $filteredMovements
                ->where('type', 'IN')
                ->sum('quantity');


        $totalStockOut =
            $filteredMovements
                ->where('type', 'OUT')
                ->sum('quantity');


        // =================================================
        // KATEGORI
        // =================================================

        $categories =
            Category::orderBy('name')->get();


        // =================================================
        // VIEW
        // =================================================

        return view(
            'admin.reports.stock',
            compact(
                'products',
                'movements',
                'categories',
                'totalProducts',
                'totalStock',
                'lowStockProducts',
                'outOfStockProducts',
                'safeStockProducts',
                'totalInventoryValue',
                'totalStockIn',
                'totalStockOut',
                'categoryId',
                'startDate',
                'endDate',
                'type'
            )
        );
    }
}