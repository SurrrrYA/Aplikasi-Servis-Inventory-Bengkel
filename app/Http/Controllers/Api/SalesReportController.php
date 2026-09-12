<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Transaction::where('status', 'completed');

        if ($request->filled('start_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->start_date
            );
        }

        if ($request->filled('end_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->end_date
            );
        }

        $transactions = $query
            ->with([
                'user',
                'items.product'
            ])
            ->latest()
            ->get();

        $totalTransactions = $transactions->count();

        $totalRevenue = $transactions->sum('total_amount');

        $transactionIds = $transactions->pluck('id');

        $totalItemsSold = TransactionItem::whereIn(
            'transaction_id',
            $transactionIds
        )->sum('quantity');

        $bestSellingProducts = TransactionItem::whereIn(
            'transaction_id',
            $transactionIds
        )
            ->selectRaw(
                'product_id, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue'
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Laporan penjualan berhasil diambil',

            'data' => [
                'filter' => [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ],

                'summary' => [
                    'total_transactions' => $totalTransactions,
                    'total_revenue' => $totalRevenue,
                    'total_items_sold' => $totalItemsSold,
                ],

                'best_selling_products' => $bestSellingProducts,

                'transactions' => $transactions,
            ]
        ]);
    }
}