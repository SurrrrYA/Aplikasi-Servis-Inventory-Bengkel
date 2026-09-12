<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\ActivityLogService;
use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    // =====================================================
    // SEMUA TRANSAKSI
    // =====================================================

    public function index()
    {
        $transactions = Transaction::with([
            'user',
            'customer',
            'vehicle',
            'items.product',
            'items.service'
        ])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data transaksi berhasil diambil',
            'data' => $transactions
        ]);
    }

    // =====================================================
    // DETAIL TRANSAKSI
    // =====================================================

    public function show(Transaction $transaction)
    {
        $transaction->load([
            'user',
            'customer',
            'vehicle',
            'items.product',
            'items.service'
        ]);

        return response()->json([
            'message' => 'Detail transaksi berhasil diambil',
            'data' => $transaction
        ]);
    }

    // =====================================================
    // TAMBAH TRANSAKSI
    // =====================================================

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => [
                'nullable',
                'exists:customers,id'
            ],

            'vehicle_id' => [
                'nullable',
                'exists:vehicles,id'
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'payment_method' => [
                'required',
                'in:cash,transfer,qris'
            ],

            'notes' => [
                'nullable',
                'string'
            ],

            'items' => [
                'required',
                'array',
                'min:1'
            ],

            'items.*.product_id' => [
                'nullable',
                'exists:products,id'
            ],

            'items.*.service_id' => [
                'nullable',
                'exists:services,id'
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ]);

        // =====================================================
        // SIMPAN TRANSAKSI
        // =====================================================

        $transaction = DB::transaction(function () use ($request) {

            $totalAmount = 0;
            $itemsData = [];

            // =================================================
            // VALIDASI CUSTOMER & KENDARAAN
            // =================================================

            if (
                $request->vehicle_id !== null &&
                $request->customer_id === null
            ) {
                abort(
                    422,
                    'Kendaraan harus memiliki customer'
                );
            }

            if (
                $request->vehicle_id !== null &&
                $request->customer_id !== null
            ) {
                $vehicleBelongsToCustomer =
                    \App\Models\Vehicle::where(
                        'id',
                        $request->vehicle_id
                    )
                    ->where(
                        'customer_id',
                        $request->customer_id
                    )
                    ->exists();

                if (!$vehicleBelongsToCustomer) {
                    abort(
                        422,
                        'Kendaraan tidak dimiliki oleh customer tersebut'
                    );
                }
            }

            // =================================================
            // PROSES ITEM
            // =================================================

            foreach ($request->items as $item) {

                // -------------------------------------------------
                // ITEM HARUS MEMILIKI PRODUCT ATAU SERVICE
                // -------------------------------------------------

                if (
                    empty($item['product_id']) &&
                    empty($item['service_id'])
                ) {
                    abort(
                        422,
                        'Setiap item harus memiliki product_id atau service_id'
                    );
                }

                // -------------------------------------------------
                // ITEM TIDAK BOLEH PRODUCT DAN SERVICE SEKALIGUS
                // -------------------------------------------------

                if (
                    !empty($item['product_id']) &&
                    !empty($item['service_id'])
                ) {
                    abort(
                        422,
                        'Satu item hanya boleh berupa barang atau jasa'
                    );
                }

                // =================================================
                // PRODUK / SPAREPART
                // =================================================

                if (!empty($item['product_id'])) {

                    $product =
                        Product::lockForUpdate()
                            ->findOrFail(
                                $item['product_id']
                            );

                    // -------------------------------------------------
                    // CEK STOK
                    // -------------------------------------------------

                    if (
                        $product->stock <
                        $item['quantity']
                    ) {
                        abort(
                            422,
                            'Stok ' .
                            $product->name .
                            ' tidak mencukupi'
                        );
                    }

                    // -------------------------------------------------
                    // HARGA JUAL & HARGA BELI
                    // -------------------------------------------------

                    $price =
                        (float) $product->selling_price;

                    $purchasePrice =
                        (float) $product->purchase_price;

                    // -------------------------------------------------
                    // SUBTOTAL
                    // -------------------------------------------------

                    $subtotal =
                        $price *
                        $item['quantity'];

                    $totalAmount +=
                        $subtotal;

                    // -------------------------------------------------
                    // SIMPAN DATA ITEM
                    // -------------------------------------------------

                    $itemsData[] = [
                        'type' =>
                            'product',

                        'product' =>
                            $product,

                        'service' =>
                            null,

                        'quantity' =>
                            $item['quantity'],

                        'price' =>
                            $price,

                        'purchase_price' =>
                            $purchasePrice,

                        'subtotal' =>
                            $subtotal,
                    ];
                }

                // =================================================
                // JASA
                // =================================================

                if (!empty($item['service_id'])) {

                    $service =
                        Service::findOrFail(
                            $item['service_id']
                        );

                    // -------------------------------------------------
                    // HARGA JASA
                    // -------------------------------------------------

                    $price =
                        (float) $service->price;

                    // -------------------------------------------------
                    // JASA TIDAK MEMILIKI HARGA BELI
                    // -------------------------------------------------

                    $purchasePrice = 0;

                    // -------------------------------------------------
                    // SUBTOTAL
                    // -------------------------------------------------

                    $subtotal =
                        $price *
                        $item['quantity'];

                    $totalAmount +=
                        $subtotal;

                    // -------------------------------------------------
                    // SIMPAN DATA ITEM
                    // -------------------------------------------------

                    $itemsData[] = [
                        'type' =>
                            'service',

                        'product' =>
                            null,

                        'service' =>
                            $service,

                        'quantity' =>
                            $item['quantity'],

                        'price' =>
                            $price,

                        'purchase_price' =>
                            $purchasePrice,

                        'subtotal' =>
                            $subtotal,
                    ];
                }
            }

            // =================================================
            // VALIDASI PEMBAYARAN
            // =================================================

            if (
                $request->paid_amount <
                $totalAmount
            ) {
                abort(
                    422,
                    'Jumlah pembayaran kurang dari total transaksi'
                );
            }

            // =================================================
            // NOMOR TRANSAKSI
            // =================================================

            $transactionCode =
                'TRX-' .
                now()->format('Ymd-His') .
                '-' .
                random_int(100, 999);

            // =================================================
            // KEMBALIAN
            // =================================================

            $changeAmount =
                $request->paid_amount -
                $totalAmount;

            // =================================================
            // SIMPAN TRANSAKSI
            // =================================================

            $transaction =
                Transaction::create([

                    'transaction_code' =>
                        $transactionCode,

                    'user_id' =>
                        $request->user()->id,

                    'customer_id' =>
                        $request->customer_id,

                    'vehicle_id' =>
                        $request->vehicle_id,

                    'total_amount' =>
                        $totalAmount,

                    'paid_amount' =>
                        $request->paid_amount,

                    'change_amount' =>
                        $changeAmount,

                    'payment_method' =>
                        $request->payment_method,

                    'status' =>
                        'completed',

                    'notes' =>
                        $request->notes,
                ]);

            // =================================================
            // SIMPAN ITEM & KURANGI STOK
            // =================================================

            foreach ($itemsData as $item) {

                // =================================================
                // PRODUK / SPAREPART
                // =================================================

                if (
                    $item['type'] === 'product' &&
                    $item['product'] !== null
                ) {

                    $product =
                        $item['product'];

                    // -------------------------------------------------
                    // STOK SEBELUM
                    // -------------------------------------------------

                    $stockBefore =
                        $product->stock;

                    // -------------------------------------------------
                    // STOK SESUDAH
                    // -------------------------------------------------

                    $stockAfter =
                        $stockBefore -
                        $item['quantity'];

                    // -------------------------------------------------
                    // SIMPAN TRANSACTION ITEM
                    // -------------------------------------------------

                    TransactionItem::create([

                        'transaction_id' =>
                            $transaction->id,

                        'product_id' =>
                            $product->id,

                        'service_id' =>
                            null,

                        'price' =>
                            $item['price'],

                        'purchase_price' =>
                            $item['purchase_price'],

                        'quantity' =>
                            $item['quantity'],

                        'subtotal' =>
                            $item['subtotal'],
                    ]);

                    // -------------------------------------------------
                    // UPDATE STOK
                    // -------------------------------------------------

                    $product->update([
                        'stock' =>
                            $stockAfter
                    ]);

                    // =================================================
                    // STOCK MOVEMENT
                    // =================================================

                    StockMovement::create([

                        'product_id' =>
                            $product->id,

                        'user_id' =>
                            $request->user()->id,

                        'type' =>
                            'OUT',

                        'quantity' =>
                            $item['quantity'],

                        'stock_before' =>
                            $stockBefore,

                        'stock_after' =>
                            $stockAfter,

                        'description' =>
                            'Penjualan melalui transaksi ' .
                            $transactionCode,
                    ]);

                    // =================================================
                    // ACTIVITY LOG PERUBAHAN STOK
                    // =================================================

                    ActivityLogService::log(

                        $request->user()->id,

                        'STOCK_OUT',

                        'PRODUCT',

                        'Stok berkurang karena transaksi ' .
                            $transactionCode .
                            ' - ' .
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
                                $item['quantity'],

                            'stock' =>
                                $stockAfter,

                            'transaction_code' =>
                                $transactionCode,

                        ]
                    );
                }

                // =================================================
                // JASA
                // =================================================

                if (
                    $item['type'] === 'service' &&
                    $item['service'] !== null
                ) {

                    $service =
                        $item['service'];

                    // -------------------------------------------------
                    // SIMPAN TRANSACTION ITEM
                    // -------------------------------------------------

                    TransactionItem::create([

                        'transaction_id' =>
                            $transaction->id,

                        'product_id' =>
                            null,

                        'service_id' =>
                            $service->id,

                        'price' =>
                            $item['price'],

                        'purchase_price' =>
                            0,

                        'quantity' =>
                            $item['quantity'],

                        'subtotal' =>
                            $item['subtotal'],
                    ]);
                }
            }

            // =================================================
            // ACTIVITY LOG TRANSAKSI
            // =================================================

            ActivityLogService::log(

                $request->user()->id,

                'CREATE',

                'TRANSACTION',

                'Membuat transaksi ' .
                    $transactionCode,

                null,

                [

                    'transaction_id' =>
                        $transaction->id,

                    'transaction_code' =>
                        $transactionCode,

                    'customer_id' =>
                        $request->customer_id,

                    'vehicle_id' =>
                        $request->vehicle_id,

                    'total_amount' =>
                        $totalAmount,

                    'paid_amount' =>
                        $request->paid_amount,

                    'change_amount' =>
                        $changeAmount,

                    'payment_method' =>
                        $request->payment_method,

                    'status' =>
                        'completed',

                ]
            );

            return $transaction;
        });

        // =====================================================
        // NOTIFIKASI OWNER
        // =====================================================
        //
        // Dijalankan SETELAH transaksi berhasil disimpan.
        //
        // Jika FCM gagal:
        // - transaksi tetap berhasil
        // - response API tetap berhasil
        // - error hanya masuk Log Laravel
        //
        // =====================================================

        try {

            // -------------------------------------------------
            // LOAD DATA CUSTOMER & KENDARAAN
            // -------------------------------------------------

            $transaction->load([
                'customer',
                'vehicle',
            ]);

            // -------------------------------------------------
            // NAMA CUSTOMER
            // -------------------------------------------------

            $customerName =
                $transaction->customer?->name
                ?? 'Customer';

            // -------------------------------------------------
            // INFORMASI KENDARAAN
            // -------------------------------------------------

            $vehicleInfo =
                'kendaraan';

            if ($transaction->vehicle) {

                $brand =
                    trim(
                        $transaction->vehicle->brand ?? ''
                    );

                $model =
                    trim(
                        $transaction->vehicle->model ?? ''
                    );

                $vehicleInfo =
                    trim(
                        $brand . ' ' . $model
                    );

                if ($vehicleInfo === '') {
                    $vehicleInfo =
                        'kendaraan';
                }
            }

            // =================================================
            // NOTIF 1
            // SERVIS MASUK
            // =================================================

            app(FcmNotificationService::class)
                ->notifyOwners(

                    'Servis Masuk',

                    "Servis baru dari {$customerName} ({$vehicleInfo}) telah masuk.",

                    [

                        'type' =>
                            'service_created',

                        'transaction_id' =>
                            (string) $transaction->id,

                        'transaction_code' =>
                            $transaction->transaction_code,

                    ]
                );

            // =================================================
            // NOTIF 2
            // PENDAPATAN MASUK
            // =================================================

            app(FcmNotificationService::class)
                ->notifyOwners(

                    'Pendapatan Masuk',

                    'Pendapatan sebesar Rp ' .
                        number_format(
                            $transaction->total_amount,
                            0,
                            ',',
                            '.'
                        ) .
                        ' berhasil masuk.',

                    [

                        'type' =>
                            'income_received',

                        'transaction_id' =>
                            (string) $transaction->id,

                        'transaction_code' =>
                            $transaction->transaction_code,

                        'amount' =>
                            (string) $transaction->total_amount,

                    ]
                );

        } catch (\Throwable $e) {

            // =================================================
            // LOG ERROR FCM
            // =================================================

            Log::error(

                'Gagal mengirim notifikasi transaksi ke Owner',

                [

                    'transaction_id' =>
                        $transaction->id,

                    'transaction_code' =>
                        $transaction->transaction_code,

                    'error' =>
                        $e->getMessage(),

                ]
            );
        }

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([

            'message' =>
                'Transaksi berhasil dibuat',

            'data' =>
                $transaction->load([

                    'user',
                    'customer',
                    'vehicle',
                    'items.product',
                    'items.service'

                ])

        ], 201);
    }

    // =====================================================
    // BATALKAN TRANSAKSI
    // =====================================================

    public function cancel(
        Transaction $transaction,
        Request $request
    ) {

        if (
            $transaction->status ===
            'cancelled'
        ) {

            return response()->json([

                'message' =>
                    'Transaksi sudah dibatalkan'

            ], 422);
        }

        DB::transaction(function () use (
            $transaction,
            $request
        ) {

            $transaction->load([

                'items.product',
                'items.service'

            ]);

            foreach ($transaction->items as $item) {

                // =================================================
                // KEMBALIKAN STOK PRODUK
                // =================================================

                if ($item->product_id) {

                    $product =
                        Product::lockForUpdate()
                            ->findOrFail(
                                $item->product_id
                            );

                    $stockBefore =
                        $product->stock;

                    $stockAfter =
                        $stockBefore +
                        $item->quantity;

                    $product->update([

                        'stock' =>
                            $stockAfter

                    ]);

                    // =================================================
                    // STOCK MOVEMENT
                    // =================================================

                    StockMovement::create([

                        'product_id' =>
                            $product->id,

                        'user_id' =>
                            $request->user()->id,

                        'type' =>
                            'IN',

                        'quantity' =>
                            $item->quantity,

                        'stock_before' =>
                            $stockBefore,

                        'stock_after' =>
                            $stockAfter,

                        'description' =>
                            'Pembatalan transaksi ' .
                            $transaction->transaction_code,

                    ]);

                    // =================================================
                    // ACTIVITY LOG STOK
                    // =================================================

                    ActivityLogService::log(

                        $request->user()->id,

                        'STOCK_IN',

                        'PRODUCT',

                        'Mengembalikan stok karena pembatalan transaksi ' .
                            $transaction->transaction_code .
                            ' - ' .
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
                                $item->quantity,

                            'stock' =>
                                $stockAfter,

                            'transaction_code' =>
                                $transaction->transaction_code,

                        ]

                    );
                }
            }

            // =================================================
            // DATA STATUS LAMA
            // =================================================

            $oldData = [

                'transaction_id' =>
                    $transaction->id,

                'transaction_code' =>
                    $transaction->transaction_code,

                'status' =>
                    $transaction->status,

            ];

            // =================================================
            // UPDATE STATUS
            // =================================================

            $transaction->update([

                'status' =>
                    'cancelled'

            ]);

            // =================================================
            // ACTIVITY LOG TRANSAKSI
            // =================================================

            ActivityLogService::log(

                $request->user()->id,

                'CANCEL',

                'TRANSACTION',

                'Membatalkan transaksi ' .
                    $transaction->transaction_code,

                $oldData,

                [

                    'transaction_id' =>
                        $transaction->id,

                    'transaction_code' =>
                        $transaction->transaction_code,

                    'status' =>
                        'cancelled',

                ]

            );
        });

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([

            'message' =>
                'Transaksi berhasil dibatalkan',

            'data' =>
                $transaction->fresh([

                    'user',
                    'customer',
                    'vehicle',
                    'items.product',
                    'items.service'

                ])

        ]);
    }
}