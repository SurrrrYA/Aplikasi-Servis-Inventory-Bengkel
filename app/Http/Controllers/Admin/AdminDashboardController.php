<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * =====================================================
     * DASHBOARD ADMIN
     * =====================================================
     */
    public function index()
    {
        // =================================================
        // TANGGAL HARI INI
        // =================================================

        $today = now()->toDateString();


        // =================================================
        // TRANSAKSI HARI INI
        // =================================================

        $todayTransactions = Transaction::query()
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();


        // =================================================
        // PENDAPATAN HARI INI
        // =================================================

        $todayRevenue = Transaction::query()
            ->whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total_amount');


        // =================================================
        // TOTAL CUSTOMER
        // =================================================

        $totalCustomers =
            Customer::count();


        // =================================================
        // PRODUK STOK MENIPIS
        // =================================================

        $lowStockCount = Product::query()
            ->whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            )
            ->count();


        // =================================================
        // TRANSAKSI TERBARU
        // =================================================

        $latestTransactions =
            Transaction::with([
                'customer',
                'vehicle',
                'user',
                'items.product',
                'items.service',
            ])
            ->latest()
            ->take(5)
            ->get();


        // =================================================
        // PRODUK STOK MENIPIS
        // =================================================

        $lowStockProducts =
            Product::with('category')
                ->whereColumn(
                    'stock',
                    '<=',
                    'minimum_stock'
                )
                ->orderBy('stock')
                ->take(10)
                ->get();


        // =================================================
        // TOTAL PRODUK
        // =================================================

        $totalProducts =
            Product::count();


        // =================================================
        // TOTAL JASA
        // =================================================

        $totalServices =
            \App\Models\Service::count();


        // =================================================
        // TRANSAKSI BULAN INI
        // =================================================

        $monthlyTransactions =
            Transaction::query()
                ->whereMonth(
                    'created_at',
                    now()->month
                )
                ->whereYear(
                    'created_at',
                    now()->year
                )
                ->where(
                    'status',
                    'completed'
                )
                ->count();


        // =================================================
        // PENDAPATAN BULAN INI
        // =================================================

        $monthlyRevenue =
            Transaction::query()
                ->whereMonth(
                    'created_at',
                    now()->month
                )
                ->whereYear(
                    'created_at',
                    now()->year
                )
                ->where(
                    'status',
                    'completed'
                )
                ->sum('total_amount');


        // =================================================
        // RETURN VIEW
        // =================================================

        return view(
            'admin.dashboard',
            compact(
                'todayTransactions',
                'todayRevenue',
                'totalCustomers',
                'lowStockCount',
                'latestTransactions',
                'lowStockProducts',
                'totalProducts',
                'totalServices',
                'monthlyTransactions',
                'monthlyRevenue'
            )
        );
    }
}