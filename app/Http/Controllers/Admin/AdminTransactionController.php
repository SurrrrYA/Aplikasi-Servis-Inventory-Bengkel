<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    /**
     * =====================================================
     * DAFTAR TRANSAKSI
     * =====================================================
     */
    public function index(Request $request)
    {
        $query = Transaction::with([
            'customer',
            'vehicle',
            'user',
        ])
        ->latest();


        // =================================================
        // SEARCH
        // =================================================

        if ($request->filled('search')) {

            $search =
                $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'transaction_code',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhereHas(
                    'customer',
                    function ($customerQuery) use ($search) {

                        $customerQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                )

                ->orWhereHas(
                    'vehicle',
                    function ($vehicleQuery) use ($search) {

                        $vehicleQuery->where(
                            'plate_number',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }


        // =================================================
        // FILTER STATUS
        // =================================================

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        // =================================================
        // PAGINATION
        // =================================================

        $transactions =
            $query
                ->paginate(15)
                ->withQueryString();


        return view(
            'admin.transactions.index',
            compact('transactions')
        );
    }


    /**
     * =====================================================
     * DETAIL TRANSAKSI
     * =====================================================
     */
    public function show(
        Transaction $transaction
    ) {

        $transaction->load([
            'customer',
            'vehicle',
            'user',
            'items.product',
            'items.service',
        ]);


        return view(
            'admin.transactions.show',
            compact('transaction')
        );
    }
}