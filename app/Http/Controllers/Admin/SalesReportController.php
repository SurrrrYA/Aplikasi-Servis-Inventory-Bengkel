<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    // =====================================================
    // LAPORAN PENJUALAN
    // =====================================================

    public function index(Request $request)
    {
        // =================================================
        // FILTER
        // =================================================

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type', '');


        // =================================================
        // TRANSAKSI SERVIS & SPAREPART
        // =================================================

        $transactionQuery = Transaction::with([
            'customer',
            'vehicle',
            'user',
            'items.service',
            'items.product',
        ])
            ->where('status', 'completed');


        // Filter tanggal

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


        $transactions = $transactionQuery
            ->latest()
            ->get();


        // =================================================
        // DATA TRANSAKSI
        // =================================================

        $serviceTransactions = collect();

        $productTransactions = collect();


        // =================================================
        // TOTAL JASA
        // =================================================

        $totalServiceRevenue = 0;

        $totalServiceMechanic = 0;

        $totalServiceOwner = 0;


        // =================================================
        // TOTAL SPAREPART
        // =================================================

        $totalProductRevenue = 0;

        $totalProductCost = 0;

        $totalProductProfit = 0;


        // =================================================
        // HITUNG TRANSAKSI
        // =================================================

        foreach ($transactions as $transaction) {

            $serviceRevenue = 0;

            $productRevenue = 0;

            $productCost = 0;

            $productProfit = 0;


            foreach ($transaction->items as $item) {

                // =================================================
                // JASA
                // =================================================

                if ($item->service_id !== null) {

                    $serviceRevenue +=
                        (float) $item->subtotal;
                }


                // =================================================
                // SPAREPART
                // =================================================

                if ($item->product_id !== null) {

                    $quantity =
                        (int) $item->quantity;


                    $sellingPrice =
                        (float) $item->price;


                    $purchasePrice =
                        (float) $item->purchase_price;


                    // ---------------------------------------------
                    // OMZET SPAREPART
                    // ---------------------------------------------

                    $productRevenue +=
                        $sellingPrice * $quantity;


                    // ---------------------------------------------
                    // MODAL SPAREPART
                    // ---------------------------------------------

                    $productCost +=
                        $purchasePrice * $quantity;


                    // ---------------------------------------------
                    // PROFIT SPAREPART
                    // ---------------------------------------------

                    $productProfit +=
                        (
                            $sellingPrice -
                            $purchasePrice
                        ) * $quantity;
                }
            }


            // =================================================
            // TRANSAKSI JASA
            // =================================================

            if ($serviceRevenue > 0) {

                // ---------------------------------------------
                // PEMBAGIAN 60 : 40
                // ---------------------------------------------

                $mechanicShare =
                    $serviceRevenue * 0.60;


                $ownerShare =
                    $serviceRevenue * 0.40;


                // ---------------------------------------------
                // SIMPAN DATA KE TRANSAKSI
                // ---------------------------------------------

                $transaction->service_revenue =
                    $serviceRevenue;


                $transaction->mechanic_share =
                    $mechanicShare;


                $transaction->owner_share =
                    $ownerShare;


                // ---------------------------------------------
                // MASUKKAN KE COLLECTION
                // ---------------------------------------------

                $serviceTransactions->push(
                    $transaction
                );


                // ---------------------------------------------
                // TOTAL
                // ---------------------------------------------

                $totalServiceRevenue +=
                    $serviceRevenue;


                $totalServiceMechanic +=
                    $mechanicShare;


                $totalServiceOwner +=
                    $ownerShare;
            }


            // =================================================
            // TRANSAKSI SPAREPART
            // =================================================

            if ($productRevenue > 0) {

                // ---------------------------------------------
                // SIMPAN DATA KE TRANSAKSI
                // ---------------------------------------------

                $transaction->product_revenue =
                    $productRevenue;


                $transaction->product_cost =
                    $productCost;


                $transaction->product_profit =
                    $productProfit;


                // ---------------------------------------------
                // MASUKKAN KE COLLECTION
                // ---------------------------------------------

                $productTransactions->push(
                    $transaction
                );


                // ---------------------------------------------
                // TOTAL
                // ---------------------------------------------

                $totalProductRevenue +=
                    $productRevenue;


                $totalProductCost +=
                    $productCost;


                $totalProductProfit +=
                    $productProfit;
            }
        }


        // =====================================================
        // PROJECT
        // =====================================================

        $projectQuery = Project::with([
            'items.product',
            'items.service',
            'costs',
        ])
            ->where('status', 'completed');


        // Filter tanggal

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


        $projects = $projectQuery
            ->latest()
            ->get();


        // =================================================
        // TOTAL PROJECT
        // =================================================

        $totalProjectRevenue = 0;

        $totalProjectCost = 0;

        $totalProjectProfit = 0;


        foreach ($projects as $project) {

            // ---------------------------------------------
            // HARGA JUAL PROJECT
            // ---------------------------------------------

            $project->project_revenue =
                (float) $project->selling_price;


            // ---------------------------------------------
            // MODAL / TOTAL BIAYA PROJECT
            // ---------------------------------------------

            $project->project_cost =
                (float) $project->total;


            // ---------------------------------------------
            // PROFIT PROJECT
            // ---------------------------------------------

            $project->project_profit =
                (float) $project->selling_price
                -
                (float) $project->total;


            // ---------------------------------------------
            // TOTAL
            // ---------------------------------------------

            $totalProjectRevenue +=
                $project->project_revenue;


            $totalProjectCost +=
                $project->project_cost;


            $totalProjectProfit +=
                $project->project_profit;
        }


        // =====================================================
        // FILTER JENIS
        // =====================================================

        if ($type === 'service') {

            // Hanya jasa

            $projects = collect();

            $productTransactions = collect();


            $totalProductRevenue = 0;

            $totalProductCost = 0;

            $totalProductProfit = 0;


            $totalProjectRevenue = 0;

            $totalProjectCost = 0;

            $totalProjectProfit = 0;
        }


        elseif ($type === 'product') {

            // Hanya sparepart

            $serviceTransactions = collect();

            $projects = collect();


            $totalServiceRevenue = 0;

            $totalServiceMechanic = 0;

            $totalServiceOwner = 0;


            $totalProjectRevenue = 0;

            $totalProjectCost = 0;

            $totalProjectProfit = 0;
        }


        elseif ($type === 'project') {

            // Hanya project

            $serviceTransactions = collect();

            $productTransactions = collect();


            $totalServiceRevenue = 0;

            $totalServiceMechanic = 0;

            $totalServiceOwner = 0;


            $totalProductRevenue = 0;

            $totalProductCost = 0;

            $totalProductProfit = 0;
        }


        // =====================================================
        // JUMLAH DATA
        // =====================================================

        $totalServiceTransactions =
            $serviceTransactions->count();


        $totalProductTransactions =
            $productTransactions->count();


        $totalProjects =
            $projects->count();


        // =====================================================
        // TOTAL OMZET
        // =====================================================

        $totalRevenue =
            $totalServiceRevenue
            +
            $totalProductRevenue
            +
            $totalProjectRevenue;


        // =====================================================
        // TOTAL PENDAPATAN OWNER
        // =====================================================

        /*
         * Jasa:
         * 40% untuk owner
         *
         * Sparepart:
         * profit = harga jual - harga beli
         *
         * Project:
         * profit = harga jual - total biaya
         */

        $totalOwnerIncome =
            $totalServiceOwner
            +
            $totalProductProfit
            +
            $totalProjectProfit;


        // =====================================================
        // TOTAL PENDAPATAN MEKANIK
        // =====================================================

        $totalMechanicIncome =
            $totalServiceMechanic;


        // =====================================================
        // VIEW
        // =====================================================

        return view(
            'admin.reports.sales',
            compact(

                'serviceTransactions',

                'productTransactions',

                'projects',


                'startDate',

                'endDate',

                'type',


                'totalServiceTransactions',

                'totalProductTransactions',

                'totalProjects',


                'totalServiceRevenue',

                'totalProductRevenue',

                'totalProductCost',

                'totalProductProfit',


                'totalServiceMechanic',

                'totalServiceOwner',


                'totalProjectRevenue',

                'totalProjectCost',

                'totalProjectProfit',


                'totalRevenue',

                'totalOwnerIncome',

                'totalMechanicIncome'
            )
        );
    }
}