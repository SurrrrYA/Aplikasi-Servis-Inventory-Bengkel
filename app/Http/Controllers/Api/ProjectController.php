<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\ProjectCost;
use App\Models\Product;
use App\Models\Service;
use App\Models\StockMovement;
use App\Services\ActivityLogService;
use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    // =====================================================
    // GET SEMUA PROJECT
    // =====================================================

    public function index()
    {
        $projects = Project::with([
            'vehicle',
            'items.product',
            'items.service',
            'costs',
        ])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data project berhasil diambil',
            'data' => $projects,
        ]);
    }

    // =====================================================
    // TAMBAH PROJECT
    // =====================================================

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'customer_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id',
            ],

            'status' => [
                'nullable',
                'in:draft,process,completed,cancelled',
            ],
        ]);

        // =====================================================
        // BUAT CODE PROJECT
        // =====================================================

        $lastProject = Project::orderBy('id', 'desc')->first();

        if ($lastProject) {
            $lastNumber = (int) str_replace(
                'PJ',
                '',
                $lastProject->code
            );

            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $code = 'PJ' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );

        $status = $request->status ?? 'process';

        // =====================================================
        // TANGGAL SELESAI
        // =====================================================

        $completedAt = $status === 'completed'
            ? now()
            : null;

        // =====================================================
        // CREATE PROJECT
        // =====================================================

        $project = Project::create([
            'code' => $code,
            'name' => $request->name,
            'customer_name' => $request->customer_name,
            'vehicle_id' => $request->vehicle_id,
            'total' => 0,
            'status' => $status,
            'completed_at' => $completedAt,
        ]);

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'CREATE',
            'PROJECT',
            'Menambahkan project ' . $project->name,
            null,
            [
                'id' => $project->id,
                'code' => $project->code,
                'name' => $project->name,
                'customer_name' => $project->customer_name,
                'vehicle_id' => $project->vehicle_id,
                'total' => $project->total,
                'status' => $project->status,
                'completed_at' => $project->completed_at,
            ]
        );

        // =====================================================
        // NOTIFIKASI PROJECT BARU
        // =====================================================

        try {
            app(FcmNotificationService::class)->notifyOwners(
                'Project Baru',
                "Project {$project->name} telah ditambahkan.",
                [
                    'type' => 'project_created',
                    'project_id' => (string) $project->id,
                    'project_code' => $project->code,
                ]
            );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim notifikasi project baru',
                [
                    'project_id' => $project->id,
                    'project_code' => $project->code,
                    'error' => $e->getMessage(),
                ]
            );
        }

        // =====================================================
        // JIKA LANGSUNG COMPLETED
        // KIRIM PROJECT SELESAI + PENDAPATAN
        // =====================================================

        if ($status === 'completed') {
            try {
                app(FcmNotificationService::class)->notifyOwners(
                    'Project Selesai',
                    "Project {$project->name} telah selesai.",
                    [
                        'type' => 'project_completed',
                        'project_id' => (string) $project->id,
                        'project_code' => $project->code,
                        'total' => (string) $project->total,
                    ]
                );

                app(FcmNotificationService::class)->notifyOwners(
                    'Pendapatan Masuk',
                    'Pendapatan project sebesar Rp ' .
                        number_format(
                            $project->total,
                            0,
                            ',',
                            '.'
                        ) .
                        ' berhasil masuk.',
                    [
                        'type' => 'project_income_received',
                        'project_id' => (string) $project->id,
                        'project_code' => $project->code,
                        'amount' => (string) $project->total,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error(
                    'Gagal mengirim notifikasi project selesai',
                    [
                        'project_id' => $project->id,
                        'project_code' => $project->code,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }

        // =====================================================
        // LOAD RELATION
        // =====================================================

        $project->load([
            'vehicle',
            'items.product',
            'items.service',
            'costs',
        ]);

        return response()->json([
            'message' => 'Project berhasil ditambahkan',
            'data' => $project,
        ], 201);
    }

    // =====================================================
    // DETAIL PROJECT
    // =====================================================

    public function show(Project $project)
    {
        $project->load([
            'vehicle',
            'items.product',
            'items.service',
            'costs',
        ]);

        return response()->json([
            'message' => 'Detail project berhasil diambil',
            'data' => $project,
        ]);
    }

    // =====================================================
    // EDIT PROJECT
    // =====================================================

    public function update(
        Request $request,
        Project $project
    ) {
        $request->validate([
            'name' => 'required|string|max:255',

            'customer_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id',
            ],

            'status' => [
                'required',
                'in:draft,process,completed,cancelled',
            ],
        ]);

        $oldStatus = $project->status;
        $newStatus = $request->status;

        $oldData = [
            'id' => $project->id,
            'code' => $project->code,
            'name' => $project->name,
            'customer_name' => $project->customer_name,
            'vehicle_id' => $project->vehicle_id,
            'total' => $project->total,
            'status' => $project->status,
            'completed_at' => $project->completed_at,
        ];

        // =====================================================
        // UPDATE PROJECT
        // =====================================================

        DB::transaction(function () use (
            $project,
            $request,
            $oldStatus,
            $newStatus
        ) {
            // =================================================
            // PROJECT DIBATALKAN
            // KEMBALIKAN STOK
            // =================================================

            if (
                $oldStatus !== 'cancelled' &&
                $newStatus === 'cancelled'
            ) {
                $items = $project->items()
                    ->where('type', 'product')
                    ->whereNotNull('product_id')
                    ->get();

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()
                        ->find($item->product_id);

                    if (!$product) {
                        continue;
                    }

                    $stockBefore = (int) $product->stock;

                    $product->increment(
                        'stock',
                        $item->quantity
                    );

                    $stockAfter =
                        $stockBefore +
                        (int) $item->quantity;

                    $this->createStockMovement(
                        $product,
                        $project,
                        'IN',
                        (int) $item->quantity,
                        $stockBefore,
                        $stockAfter,
                        'Pengembalian material karena project ' .
                        $project->code .
                        ' dibatalkan'
                    );
                }
            }

            // =================================================
            // PROJECT DIBUKA KEMBALI
            // KURANGI STOK LAGI
            // =================================================

            if (
                $oldStatus === 'cancelled' &&
                $newStatus !== 'cancelled'
            ) {
                $items = $project->items()
                    ->where('type', 'product')
                    ->whereNotNull('product_id')
                    ->get();

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()
                        ->find($item->product_id);

                    if (!$product) {
                        continue;
                    }

                    $stockBefore = (int) $product->stock;

                    if (
                        $stockBefore <
                        (int) $item->quantity
                    ) {
                        abort(
                            422,
                            'Stok produk ' .
                            $product->name .
                            ' tidak mencukupi. ' .
                            'Stok tersedia: ' .
                            $stockBefore .
                            '.'
                        );
                    }

                    $product->decrement(
                        'stock',
                        $item->quantity
                    );

                    $stockAfter =
                        $stockBefore -
                        (int) $item->quantity;

                    $this->createStockMovement(
                        $product,
                        $project,
                        'OUT',
                        (int) $item->quantity,
                        $stockBefore,
                        $stockAfter,
                        'Penggunaan kembali material karena project ' .
                        $project->code .
                        ' dibuka kembali'
                    );
                }
            }

            // =================================================
            // TANGGAL SELESAI PROJECT
            // =================================================

            $completedAt = $project->completed_at;

            if (
                $oldStatus !== 'completed' &&
                $newStatus === 'completed'
            ) {
                $completedAt = now();
            } elseif (
                $newStatus !== 'completed'
            ) {
                $completedAt = null;
            }

            // =================================================
            // UPDATE PROJECT
            // =================================================

            $project->update([
                'name' => $request->name,
                'customer_name' => $request->customer_name,
                'vehicle_id' => $request->vehicle_id,
                'status' => $newStatus,
                'completed_at' => $completedAt,
            ]);
        });

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'UPDATE',
            'PROJECT',
            'Mengubah project ' . $project->name,
            $oldData,
            [
                'id' => $project->id,
                'code' => $project->code,
                'name' => $project->name,
                'customer_name' => $project->customer_name,
                'vehicle_id' => $project->vehicle_id,
                'total' => $project->total,
                'status' => $project->status,
                'completed_at' => $project->completed_at,
            ]
        );

        // =====================================================
        // NOTIFIKASI PROJECT SELESAI
        // =====================================================

        if (
            $oldStatus !== 'completed' &&
            $newStatus === 'completed'
        ) {
            try {
                app(FcmNotificationService::class)->notifyOwners(
                    'Project Selesai',
                    "Project {$project->name} telah selesai.",
                    [
                        'type' => 'project_completed',
                        'project_id' => (string) $project->id,
                        'project_code' => $project->code,
                        'total' => (string) $project->total,
                    ]
                );

                // =================================================
                // NOTIFIKASI PENDAPATAN
                // =================================================

                app(FcmNotificationService::class)->notifyOwners(
                    'Pendapatan Masuk',
                    'Pendapatan project sebesar Rp ' .
                        number_format(
                            $project->total,
                            0,
                            ',',
                            '.'
                        ) .
                        ' berhasil masuk.',
                    [
                        'type' => 'project_income_received',
                        'project_id' => (string) $project->id,
                        'project_code' => $project->code,
                        'amount' => (string) $project->total,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error(
                    'Gagal mengirim notifikasi project selesai',
                    [
                        'project_id' => $project->id,
                        'project_code' => $project->code,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }

        // =====================================================
        // LOAD RELATION
        // =====================================================

        $project->load([
            'vehicle',
            'items.product',
            'items.service',
            'costs',
        ]);

        return response()->json([
            'message' => 'Project berhasil diperbarui',
            'data' => $project,
        ]);
    }

    // =====================================================
    // HAPUS PROJECT
    // =====================================================

    public function destroy(
        Request $request,
        Project $project
    ) {
        $oldData = [
            'id' => $project->id,
            'code' => $project->code,
            'name' => $project->name,
            'customer_name' => $project->customer_name,
            'vehicle_id' => $project->vehicle_id,
            'total' => $project->total,
            'status' => $project->status,
            'completed_at' => $project->completed_at,
        ];

        $projectName = $project->name;

        DB::transaction(function () use (
            $project
        ) {
            // =================================================
            // PROJECT AKTIF
            // STOK MASIH TERPAKAI
            // KEMBALIKAN STOK
            // =================================================

            if ($project->status !== 'cancelled') {
                $items = $project->items()
                    ->where('type', 'product')
                    ->whereNotNull('product_id')
                    ->get();

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()
                        ->find($item->product_id);

                    if (!$product) {
                        continue;
                    }

                    $stockBefore = (int) $product->stock;

                    $product->increment(
                        'stock',
                        $item->quantity
                    );

                    $stockAfter =
                        $stockBefore +
                        (int) $item->quantity;

                    $this->createStockMovement(
                        $product,
                        $project,
                        'IN',
                        (int) $item->quantity,
                        $stockBefore,
                        $stockAfter,
                        'Pengembalian material karena project ' .
                        $project->code .
                        ' dihapus'
                    );
                }
            }

            // =================================================
            // HAPUS ITEM
            // =================================================

            $project->items()->delete();

            // =================================================
            // HAPUS COST
            // =================================================

            $project->costs()->delete();

            // =================================================
            // HAPUS PROJECT
            // =================================================

            $project->delete();
        });

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'DELETE',
            'PROJECT',
            'Menghapus project ' . $projectName,
            $oldData,
            null
        );

        return response()->json([
            'message' => 'Project berhasil dihapus',
        ]);
    }

    // =====================================================
    // TAMBAH ITEM PROJECT
    // =====================================================

    public function storeItem(
        Request $request,
        Project $project
    ) {
        $request->validate([
            'type' => [
                'required',
                'in:product,service',
            ],

            'product_id' => [
                'nullable',
                'integer',
                'exists:products,id',
            ],

            'service_id' => [
                'nullable',
                'integer',
                'exists:services,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        // =====================================================
        // PROJECT CANCELLED
        // =====================================================

        if ($project->status === 'cancelled') {
            return response()->json([
                'message' =>
                    'Project yang dibatalkan tidak dapat ditambahkan item.',
            ], 422);
        }

        // Untuk menyimpan data stok menipis
        // dan dikirim setelah transaksi DB berhasil.
        $lowStockData = null;

        // =====================================================
        // TRANSACTION
        // =====================================================

        $item = DB::transaction(function () use (
            $request,
            $project,
            &$lowStockData
        ) {
            $productId = null;
            $serviceId = null;
            $price = 0;
            $name = '';
            $quantity = (int) $request->quantity;

            // =================================================
            // PRODUCT
            // =================================================

            if ($request->type === 'product') {
                if (!$request->product_id) {
                    return response()->json([
                        'message' =>
                            'product_id wajib diisi untuk item barang.',
                    ], 422);
                }

                $product = Product::lockForUpdate()
                    ->findOrFail($request->product_id);

                // =================================================
                // CEK STOK
                // =================================================

                if ($product->stock < $quantity) {
                    return response()->json([
                        'message' =>
                            'Stok produk tidak mencukupi. ' .
                            'Stok tersedia: ' .
                            $product->stock .
                            '.',
                    ], 422);
                }

                $productId = $product->id;
                $price = (float) $product->selling_price;
                $name = $product->name;

                // =================================================
                // STOCK SEBELUM
                // =================================================

                $stockBefore = (int) $product->stock;

                // =================================================
                // KURANGI STOK
                // =================================================

                $product->decrement(
                    'stock',
                    $quantity
                );

                $stockAfter =
                    $stockBefore -
                    $quantity;

                // =================================================
                // SIAPKAN NOTIF STOK MENIPIS
                // =================================================

                if (
                    $stockBefore > (int) $product->minimum_stock &&
                    $stockAfter <= (int) $product->minimum_stock
                ) {
                    $lowStockData = [
                        'product_id' => $product->id,
                        'product_code' => $product->code,
                        'product_name' => $product->name,
                        'stock' => $stockAfter,
                        'minimum_stock' => (int) $product->minimum_stock,
                        'unit' => $product->unit,
                    ];
                }

                // =================================================
                // STOCK MOVEMENT
                // =================================================

                $this->createStockMovement(
                    $product,
                    $project,
                    'OUT',
                    $quantity,
                    $stockBefore,
                    $stockAfter,
                    'Material digunakan untuk project ' .
                    $project->code
                );
            }

            // =================================================
            // SERVICE
            // =================================================

            if ($request->type === 'service') {
                if (!$request->service_id) {
                    return response()->json([
                        'message' =>
                            'service_id wajib diisi untuk item jasa.',
                    ], 422);
                }

                $service = Service::findOrFail(
                    $request->service_id
                );

                $serviceId = $service->id;
                $price = (float) $service->price;
                $name = $service->name;

                // Jasa selalu quantity 1
                $quantity = 1;
            }

            // =================================================
            // SUBTOTAL
            // =================================================

            $subtotal =
                $price *
                $quantity;

            // =================================================
            // CREATE PROJECT ITEM
            // =================================================

            $item = ProjectItem::create([
                'project_id' => $project->id,
                'type' => $request->type,
                'product_id' => $productId,
                'service_id' => $serviceId,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);

            // =================================================
            // HITUNG TOTAL PROJECT
            // =================================================

            $this->recalculateTotal($project);

            return $item;
        });

        // =====================================================
        // CEK RESPONSE VALIDASI
        // =====================================================

        if (
            $item instanceof
            \Illuminate\Http\JsonResponse
        ) {
            return $item;
        }

        // =====================================================
        // NOTIFIKASI STOK MENIPIS
        // DIKIRIM SETELAH TRANSAKSI DB BERHASIL
        // =====================================================

        if ($lowStockData) {
            $this->sendLowStockNotification(
                $lowStockData
            );
        }

        // =====================================================
        // LOAD RELATION
        // =====================================================

        $item->load([
            'product',
            'service',
        ]);

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'CREATE',
            'PROJECT_ITEM',
            'Menambahkan item project ' .
            $item->name,
            null,
            [
                'id' => $item->id,
                'project_id' => $item->project_id,
                'type' => $item->type,
                'product_id' => $item->product_id,
                'service_id' => $item->service_id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'created_at' => $item->created_at,
            ]
        );

        return response()->json([
            'message' =>
                'Item project berhasil ditambahkan',
            'data' => $item,
        ], 201);
    }

    // =====================================================
    // EDIT ITEM PROJECT
    // =====================================================

    public function updateItem(
        Request $request,
        Project $project,
        ProjectItem $item
    ) {
        if (
            $item->project_id !==
            $project->id
        ) {
            return response()->json([
                'message' =>
                    'Item tidak termasuk dalam project ini.',
            ], 404);
        }

        if ($project->status === 'cancelled') {
            return response()->json([
                'message' =>
                    'Project yang dibatalkan tidak dapat diubah.',
            ], 422);
        }

        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $oldData = [
            'id' => $item->id,
            'type' => $item->type,
            'product_id' => $item->product_id,
            'service_id' => $item->service_id,
            'name' => $item->name,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'subtotal' => $item->subtotal,
            'created_at' => $item->created_at,
        ];

        $oldQuantity =
            (int) $item->quantity;

        $newQuantity =
            (int) $request->quantity;

        // Untuk menyimpan data stok menipis
        $lowStockData = null;

        // =====================================================
        // TRANSACTION
        // =====================================================

        DB::transaction(function () use (
            $item,
            $project,
            $oldQuantity,
            $newQuantity,
            &$lowStockData
        ) {
            // =================================================
            // PRODUCT
            // =================================================

            if (
                $item->type === 'product' &&
                $item->product_id
            ) {
                $product = Product::lockForUpdate()
                    ->findOrFail($item->product_id);

                $difference =
                    $newQuantity -
                    $oldQuantity;

                // =================================================
                // QTY NAIK
                // =================================================

                if ($difference > 0) {
                    $stockBefore =
                        (int) $product->stock;

                    if (
                        $stockBefore <
                        $difference
                    ) {
                        abort(
                            422,
                            'Stok produk tidak mencukupi. ' .
                            'Stok tersedia: ' .
                            $stockBefore .
                            '.'
                        );
                    }

                    $product->decrement(
                        'stock',
                        $difference
                    );

                    $stockAfter =
                        $stockBefore -
                        $difference;

                    // =================================================
                    // SIAPKAN NOTIF STOK MENIPIS
                    // =================================================

                    if (
                        $stockBefore > (int) $product->minimum_stock &&
                        $stockAfter <= (int) $product->minimum_stock
                    ) {
                        $lowStockData = [
                            'product_id' => $product->id,
                            'product_code' => $product->code,
                            'product_name' => $product->name,
                            'stock' => $stockAfter,
                            'minimum_stock' => (int) $product->minimum_stock,
                            'unit' => $product->unit,
                        ];
                    }

                    $this->createStockMovement(
                        $product,
                        $project,
                        'OUT',
                        $difference,
                        $stockBefore,
                        $stockAfter,
                        'Penambahan penggunaan material project ' .
                        $project->code
                    );
                }

                // =================================================
                // QTY TURUN
                // =================================================

                if ($difference < 0) {
                    $restoreQuantity =
                        abs($difference);

                    $stockBefore =
                        (int) $product->stock;

                    $product->increment(
                        'stock',
                        $restoreQuantity
                    );

                    $stockAfter =
                        $stockBefore +
                        $restoreQuantity;

                    $this->createStockMovement(
                        $product,
                        $project,
                        'IN',
                        $restoreQuantity,
                        $stockBefore,
                        $stockAfter,
                        'Pengurangan penggunaan material project ' .
                        $project->code
                    );
                }
            }

            // =================================================
            // UPDATE ITEM
            // =================================================

            $subtotal =
                (float) $item->price *
                $newQuantity;

            $item->update([
                'quantity' => $newQuantity,
                'subtotal' => $subtotal,
            ]);

            // =================================================
            // HITUNG ULANG TOTAL
            // =================================================

            $this->recalculateTotal($project);
        });

        // =====================================================
        // NOTIFIKASI STOK MENIPIS
        // =====================================================

        if ($lowStockData) {
            $this->sendLowStockNotification(
                $lowStockData
            );
        }

        // =====================================================
        // LOAD RELATION
        // =====================================================

        $item->load([
            'product',
            'service',
        ]);

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'UPDATE',
            'PROJECT_ITEM',
            'Mengubah item project ' .
            $item->name,
            $oldData,
            [
                'id' => $item->id,
                'type' => $item->type,
                'product_id' => $item->product_id,
                'service_id' => $item->service_id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'created_at' => $item->created_at,
            ]
        );

        return response()->json([
            'message' =>
                'Item project berhasil diperbarui',
            'data' => $item,
        ]);
    }

    // =====================================================
    // HAPUS ITEM PROJECT
    // =====================================================

    public function destroyItem(
        Request $request,
        Project $project,
        ProjectItem $item
    ) {
        if (
            $item->project_id !==
            $project->id
        ) {
            return response()->json([
                'message' =>
                    'Item tidak termasuk dalam project ini.',
            ], 404);
        }

        $oldData = [
            'id' => $item->id,
            'project_id' => $item->project_id,
            'type' => $item->type,
            'product_id' => $item->product_id,
            'service_id' => $item->service_id,
            'name' => $item->name,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'subtotal' => $item->subtotal,
            'created_at' => $item->created_at,
        ];

        $itemName = $item->name;

        // =====================================================
        // TRANSACTION
        // =====================================================

        DB::transaction(function () use (
            $item,
            $project
        ) {
            // =================================================
            // KEMBALIKAN STOK
            // =================================================

            if (
                $project->status !== 'cancelled' &&
                $item->type === 'product' &&
                $item->product_id
            ) {
                $product = Product::lockForUpdate()
                    ->find($item->product_id);

                if ($product) {
                    $stockBefore =
                        (int) $product->stock;

                    $product->increment(
                        'stock',
                        $item->quantity
                    );

                    $stockAfter =
                        $stockBefore +
                        (int) $item->quantity;

                    $this->createStockMovement(
                        $product,
                        $project,
                        'IN',
                        (int) $item->quantity,
                        $stockBefore,
                        $stockAfter,
                        'Pengembalian material karena item project ' .
                        $project->code .
                        ' dihapus'
                    );
                }
            }

            // =================================================
            // HAPUS ITEM
            // =================================================

            $item->delete();

            // =================================================
            // HITUNG ULANG TOTAL
            // =================================================

            $this->recalculateTotal($project);
        });

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'DELETE',
            'PROJECT_ITEM',
            'Menghapus item project ' .
            $itemName,
            $oldData,
            null
        );

        return response()->json([
            'message' =>
                'Item project berhasil dihapus',
        ]);
    }

    // =====================================================
    // TAMBAH BIAYA TAMBAHAN
    // =====================================================

    public function storeCost(
        Request $request,
        Project $project
    ) {
        if ($project->status === 'cancelled') {
            return response()->json([
                'message' =>
                    'Project yang dibatalkan tidak dapat ditambahkan biaya.',
            ], 422);
        }

        $request->validate([
            'name' =>
                'required|string|max:255',

            'amount' =>
                'required|numeric|min:0',
        ]);

        $cost = DB::transaction(function () use (
            $request,
            $project
        ) {
            $cost = ProjectCost::create([
                'project_id' => $project->id,
                'name' => $request->name,
                'amount' => $request->amount,
            ]);

            $this->recalculateTotal(
                $project
            );

            return $cost;
        });

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'CREATE',
            'PROJECT_COST',
            'Menambahkan biaya project ' .
            $cost->name,
            null,
            [
                'id' => $cost->id,
                'project_id' => $cost->project_id,
                'name' => $cost->name,
                'amount' => $cost->amount,
                'created_at' => $cost->created_at,
            ]
        );

        return response()->json([
            'message' =>
                'Biaya project berhasil ditambahkan',
            'data' => $cost,
        ], 201);
    }

    // =====================================================
    // HAPUS BIAYA TAMBAHAN
    // =====================================================

    public function destroyCost(
        Request $request,
        Project $project,
        ProjectCost $cost
    ) {
        if (
            $cost->project_id !==
            $project->id
        ) {
            return response()->json([
                'message' =>
                    'Biaya tidak termasuk dalam project ini.',
            ], 404);
        }

        $oldData = [
            'id' => $cost->id,
            'project_id' => $cost->project_id,
            'name' => $cost->name,
            'amount' => $cost->amount,
            'created_at' => $cost->created_at,
        ];

        $costName = $cost->name;

        DB::transaction(function () use (
            $cost,
            $project
        ) {
            $cost->delete();

            $this->recalculateTotal(
                $project
            );
        });

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityLogService::log(
            $request->user()?->id,
            'DELETE',
            'PROJECT_COST',
            'Menghapus biaya project ' .
            $costName,
            $oldData,
            null
        );

        return response()->json([
            'message' =>
                'Biaya project berhasil dihapus',
        ]);
    }

    // =====================================================
    // HITUNG TOTAL PROJECT
    // =====================================================

    private function recalculateTotal(
        Project $project
    ) {
        $totalItems =
            $project->items()
                ->sum('subtotal');

        $totalCosts =
            $project->costs()
                ->sum('amount');

        $total =
            (float) $totalItems +
            (float) $totalCosts;

        $project->update([
            'total' => $total,
        ]);
    }

    // =====================================================
    // NOTIFIKASI STOK MENIPIS
    // =====================================================

    private function sendLowStockNotification(
        array $lowStockData
    ): void {
        try {
            app(FcmNotificationService::class)->notifyOwners(
                'Stok Menipis',
                "Stok {$lowStockData['product_name']} tersisa " .
                    "{$lowStockData['stock']} " .
                    "{$lowStockData['unit']}.",
                [
                    'type' => 'stock_low',
                    'product_id' => (string) $lowStockData['product_id'],
                    'product_code' => $lowStockData['product_code'],
                    'stock' => (string) $lowStockData['stock'],
                    'minimum_stock' => (string) $lowStockData['minimum_stock'],
                ]
            );
        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim notifikasi stok menipis',
                [
                    'product_id' => $lowStockData['product_id'],
                    'product_code' => $lowStockData['product_code'],
                    'stock' => $lowStockData['stock'],
                    'minimum_stock' => $lowStockData['minimum_stock'],
                    'error' => $e->getMessage(),
                ]
            );
        }
    }

    // =====================================================
    // CATAT STOCK MOVEMENT
    // =====================================================

    private function createStockMovement(
        Product $product,
        Project $project,
        string $type,
        int $quantity,
        int $stockBefore,
        int $stockAfter,
        string $description
    ) {
        $userId = Auth::id();

        // User login wajib tersedia
        if (!$userId) {
            return;
        }

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'description' => $description,
        ]);
    }
}