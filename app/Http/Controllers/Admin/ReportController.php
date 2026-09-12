<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        /*
        |--------------------------------------------------------------------------
        | QUERY TRANSAKSI SERVIS BIASA
        |--------------------------------------------------------------------------
        */

        $transactionQuery = Transaction::query()
            ->where('status', 'completed')
            ->with([
                'customer',
                'vehicle',
                'items.service',
                'items.product',
            ])
            ->latest();

        if ($startDate) {
            $transactionQuery->whereDate(
                'created_at',
                '>=',
                $startDate
            );
        }

        if ($endDate) {
            $transactionQuery->whereDate(
                'created_at',
                '<=',
                $endDate
            );
        }

        $transactions = $transactionQuery->get();

        /*
        |--------------------------------------------------------------------------
        | PERHITUNGAN TRANSAKSI
        |--------------------------------------------------------------------------
        */

        $totalTransactionRevenue = 0;
        $totalServiceRevenue = 0;
        $totalProductRevenue = 0;

        foreach ($transactions as $transaction) {

            $totalTransactionRevenue +=
                (float) $transaction->total_amount;

            foreach ($transaction->items as $item) {

                if ($item->service_id) {
                    $totalServiceRevenue +=
                        (float) $item->subtotal;
                }

                if ($item->product_id) {
                    $totalProductRevenue +=
                        (float) $item->subtotal;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMBAGIAN JASA
        |--------------------------------------------------------------------------
        |
        | 60% = Mekanik
        | 40% = Owner
        |
        */

        $mechanicShare =
            $totalServiceRevenue * 0.60;

        $ownerServiceShare =
            $totalServiceRevenue * 0.40;

        /*
        |--------------------------------------------------------------------------
        | QUERY PROJECT
        |--------------------------------------------------------------------------
        */

        $projectQuery = Project::query()
            ->with([
                'items.product',
                'items.service',
                'costs',
            ])
            ->latest();

        if ($startDate) {
            $projectQuery->whereDate(
                'created_at',
                '>=',
                $startDate
            );
        }

        if ($endDate) {
            $projectQuery->whereDate(
                'created_at',
                '<=',
                $endDate
            );
        }

        $projects = $projectQuery->get();

        /*
        |--------------------------------------------------------------------------
        | PERHITUNGAN PROJECT
        |--------------------------------------------------------------------------
        */

        $totalProjectRevenue = 0;
        $totalProjectCost = 0;
        $totalProjectProfit = 0;

        foreach ($projects as $project) {

            $totalProjectRevenue +=
                (float) $project->selling_price;

            $totalProjectCost +=
                (float) $project->total;

            $totalProjectProfit +=
                (float) $project->profit;
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL KESELURUHAN
        |--------------------------------------------------------------------------
        */

        $totalSales =
            $totalTransactionRevenue +
            $totalProjectRevenue;

        /*
        |--------------------------------------------------------------------------
        | DATA RINGKASAN
        |--------------------------------------------------------------------------
        */

        $summary = [
            'total_sales' =>
                $totalSales,

            'transaction_revenue' =>
                $totalTransactionRevenue,

            'service_revenue' =>
                $totalServiceRevenue,

            'product_revenue' =>
                $totalProductRevenue,

            'mechanic_share' =>
                $mechanicShare,

            'owner_service_share' =>
                $ownerServiceShare,

            'project_revenue' =>
                $totalProjectRevenue,

            'project_cost' =>
                $totalProjectCost,

            'project_profit' =>
                $totalProjectProfit,

            'transaction_count' =>
                $transactions->count(),

            'project_count' =>
                $projects->count(),
        ];

        return view(
            'admin.reports.index',
            compact(
                'transactions',
                'projects',
                'summary',
                'startDate',
                'endDate'
            )
        );
    }
}