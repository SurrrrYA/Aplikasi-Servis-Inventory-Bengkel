<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        $totalCategories = Category::count();

        $lowStockCount = Product::whereColumn(
            'stock',
            '<=',
            'minimum_stock'
        )->count();

        $totalTransactions = Transaction::where(
            'status',
            'completed'
        )->count();

        $totalRevenue = Transaction::where(
            'status',
            'completed'
        )->sum('total_amount');

        $latestTransactions = Transaction::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereColumn(
                'stock',
                '<=',
                'minimum_stock'
            )
            ->orderBy('stock')
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Data dashboard berhasil diambil',

            'data' => [

                'summary' => [
                    'total_products' => $totalProducts,
                    'total_categories' => $totalCategories,
                    'low_stock_count' => $lowStockCount,
                    'total_transactions' => $totalTransactions,
                    'total_revenue' => $totalRevenue,
                ],

                'latest_transactions' => $latestTransactions,

                'low_stock_products' => $lowStockProducts,
            ]
        ]);
    }
}