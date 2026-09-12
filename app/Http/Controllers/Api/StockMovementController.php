<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\ActivityLogService;
use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockMovementController extends Controller
{
    // =====================================================
    // RIWAYAT PERGERAKAN STOK
    // =====================================================

    public function index()
    {
        $movements = StockMovement::with([
            'product',
            'user'
        ])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Riwayat stok berhasil diambil',
            'data' => $movements
        ]);
    }


    // =====================================================
    // STOCK IN
    // =====================================================

    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' =>
                'required|exists:products,id',

            'quantity' =>
                'required|integer|min:1',

            'description' =>
                'nullable|string|max:255',
        ]);


        $movement = DB::transaction(function () use ($request) {

            // =================================================
            // AMBIL PRODUK
            // =================================================

            $product = Product::findOrFail(
                $request->product_id
            );


            // =================================================
            // HITUNG STOK
            // =================================================

            $stockBefore =
                $product->stock;

            $quantity =
                $request->quantity;

            $stockAfter =
                $stockBefore + $quantity;


            // =================================================
            // UPDATE STOK PRODUK
            // =================================================

            $product->update([
                'stock' =>
                    $stockAfter
            ]);


            // =================================================
            // SIMPAN STOCK MOVEMENT
            // =================================================

            $movement = StockMovement::create([

                'product_id' =>
                    $product->id,

                'user_id' =>
                    $request->user()->id,

                'type' =>
                    'IN',

                'quantity' =>
                    $quantity,

                'stock_before' =>
                    $stockBefore,

                'stock_after' =>
                    $stockAfter,

                'description' =>
                    $request->description,
            ]);


            // =================================================
            // ACTIVITY LOG
            // =================================================

            ActivityLogService::log(

                $request->user()->id,

                'STOCK_IN',

                'PRODUCT',

                'Menambahkan stok barang ' .
                    $product->name,

                [

                    'product_id' =>
                        $product->id,

                    'code' =>
                        $product->code,

                    'name' =>
                        $product->name,

                    'stock' =>
                        $stockBefore,
                ],

                [

                    'product_id' =>
                        $product->id,

                    'code' =>
                        $product->code,

                    'name' =>
                        $product->name,

                    'quantity' =>
                        $quantity,

                    'stock' =>
                        $stockAfter,

                    'description' =>
                        $request->description,
                ]
            );


            return $movement;
        });


        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([

            'message' =>
                'Stok berhasil ditambahkan',

            'data' =>
                $movement->load(
                    'product',
                    'user'
                )

        ], 201);
    }


    // =====================================================
    // STOCK OUT
    // =====================================================

    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' =>
                'required|exists:products,id',

            'quantity' =>
                'required|integer|min:1',

            'description' =>
                'nullable|string|max:255',
        ]);


        $movement = DB::transaction(function () use ($request) {

            // =================================================
            // AMBIL PRODUK
            // =================================================

            $product = Product::findOrFail(
                $request->product_id
            );


            // =================================================
            // CEK STOK
            // =================================================

            if (
                $product->stock
                <
                $request->quantity
            ) {

                abort(
                    422,
                    'Stok barang tidak mencukupi'
                );
            }


            // =================================================
            // HITUNG STOK
            // =================================================

            $stockBefore =
                $product->stock;

            $quantity =
                $request->quantity;

            $stockAfter =
                $stockBefore - $quantity;


            // =================================================
            // UPDATE STOK PRODUK
            // =================================================

            $product->update([
                'stock' =>
                    $stockAfter
            ]);


            // =================================================
            // SIMPAN STOCK MOVEMENT
            // =================================================

            $movement = StockMovement::create([

                'product_id' =>
                    $product->id,

                'user_id' =>
                    $request->user()->id,

                'type' =>
                    'OUT',

                'quantity' =>
                    $quantity,

                'stock_before' =>
                    $stockBefore,

                'stock_after' =>
                    $stockAfter,

                'description' =>
                    $request->description,
            ]);


            // =================================================
            // ACTIVITY LOG
            // =================================================

            ActivityLogService::log(

                $request->user()->id,

                'STOCK_OUT',

                'PRODUCT',

                'Mengurangi stok barang ' .
                    $product->name,

                [

                    'product_id' =>
                        $product->id,

                    'code' =>
                        $product->code,

                    'name' =>
                        $product->name,

                    'stock' =>
                        $stockBefore,
                ],

                [

                    'product_id' =>
                        $product->id,

                    'code' =>
                        $product->code,

                    'name' =>
                        $product->name,

                    'quantity' =>
                        $quantity,

                    'stock' =>
                        $stockAfter,

                    'description' =>
                        $request->description,
                ]
            );


            return $movement;
        });


        // =====================================================
        // NOTIFIKASI STOK MENIPIS
        // =====================================================

        $product = $movement->product;

        if (
            $movement->stock_before > $product->minimum_stock &&
            $movement->stock_after <= $product->minimum_stock
        ) {
            try {
                app(FcmNotificationService::class)->notifyOwners(
                    'Stok Menipis',
                    "Stok {$product->name} tersisa {$movement->stock_after} pcs. Batas minimum: {$product->minimum_stock} pcs.",
                    [
                        'type' =>
                            'low_stock',

                        'product_id' =>
                            (string) $product->id,

                        'product_code' =>
                            $product->code,

                        'stock' =>
                            (string) $movement->stock_after,

                        'minimum_stock' =>
                            (string) $product->minimum_stock,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error(
                    'Gagal mengirim notifikasi stok menipis',
                    [
                        'product_id' =>
                            $product->id,

                        'product_code' =>
                            $product->code,

                        'error' =>
                            $e->getMessage(),
                    ]
                );
            }
        }


        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([

            'message' =>
                'Stok berhasil dikurangi',

            'data' =>
                $movement->load(
                    'product',
                    'user'
                )

        ], 201);
    }
}