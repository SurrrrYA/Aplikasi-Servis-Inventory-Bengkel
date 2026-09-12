<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\ProjectCost;
use App\Models\Product;
use App\Models\Service;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    // =====================================================
    // PROJECT INDEX
    // =====================================================

    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.projects.index',
            compact('projects')
        );
    }


    // =====================================================
    // CREATE PROJECT
    // =====================================================

    public function create()
    {
        return view('admin.projects.create');
    }


    // =====================================================
    // STORE PROJECT
    // =====================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'customer_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'in:draft,process,completed,cancelled'
            ],
        ]);

        $lastProject = Project::latest('id')->first();

        $number = $lastProject
            ? $lastProject->id + 1
            : 1;

        $code = 'PJ' . str_pad(
            $number,
            3,
            '0',
            STR_PAD_LEFT
        );

        /*
         * Jika project langsung dibuat dengan status
         * completed, maka completed_at langsung diisi.
         *
         * Untuk status lain, completed_at tetap null.
         */
        $completedAt = $validated['status'] === 'completed'
            ? now()
            : null;

        $project = Project::create([
            'code' => $code,
            'name' => $validated['name'],
            'customer_name' => $validated['customer_name'] ?? null,
            'total' => 0,
            'status' => $validated['status'],
            'completed_at' => $completedAt,
        ]);

        $user = $request->user();

        if ($user) {
            ActivityLogService::log(
                $user->id,
                'CREATE',
                'PROJECT',
                'Menambahkan project ' . $project->name,
                null,
                [
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'customer_name' => $project->customer_name,
                    'total' => $project->total,
                    'status' => $project->status,
                    'completed_at' => $project->completed_at,
                ]
            );
        }

        return redirect()
            ->route('admin.projects.index')
            ->with(
                'success',
                'Project berhasil ditambahkan.'
            );
    }


    // =====================================================
    // SHOW PROJECT
    // =====================================================

    public function show(Project $project)
    {
        $project->load([
            'vehicle',
            'items.product',
            'items.service',
            'costs',
        ]);

        return view(
            'admin.projects.show',
            compact('project')
        );
    }


    // =====================================================
    // EDIT PROJECT
    // =====================================================

    public function edit(Project $project)
    {
        return view(
            'admin.projects.edit',
            compact('project')
        );
    }


    // =====================================================
    // UPDATE PROJECT
    // =====================================================

    public function update(
        Request $request,
        Project $project
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'customer_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'in:draft,process,completed,cancelled'
            ],
        ]);

        // Simpan status lama sebelum diubah
        $oldStatus = $project->status;

        $oldData = [
            'id' => $project->id,
            'code' => $project->code,
            'name' => $project->name,
            'customer_name' => $project->customer_name,
            'total' => $project->total,
            'status' => $project->status,
            'completed_at' => $project->completed_at,
        ];

        $newStatus = $validated['status'];

        /*
         * ==================================================
         * ATUR COMPLETED_AT
         * ==================================================
         *
         * Jika status berubah menjadi completed:
         * - isi completed_at dengan waktu sekarang
         *
         * Jika sebelumnya sudah completed dan tetap completed:
         * - pertahankan tanggal selesai lama
         *
         * Jika status berubah menjadi selain completed:
         * - kosongkan completed_at
         */

        $completedAt = $project->completed_at;

        if (
            $newStatus === 'completed' &&
            $oldStatus !== 'completed'
        ) {
            $completedAt = now();
        } elseif ($newStatus !== 'completed') {
            $completedAt = null;
        }

        $project->update([
            'name' => $validated['name'],
            'customer_name' => $validated['customer_name'] ?? null,
            'status' => $newStatus,
            'completed_at' => $completedAt,
        ]);

        // Hitung ulang total project
        $this->recalculateTotal($project);

        $project->refresh();

        $user = $request->user();

        if ($user) {
            ActivityLogService::log(
                $user->id,
                'UPDATE',
                'PROJECT',
                'Mengubah project ' . $project->name,
                $oldData,
                [
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'customer_name' => $project->customer_name,
                    'total' => $project->total,
                    'status' => $project->status,
                    'completed_at' => $project->completed_at,
                ]
            );
        }

        return redirect()
            ->route(
                'admin.projects.show',
                $project
            )
            ->with(
                'success',
                'Project berhasil diperbarui.'
            );
    }


    // =====================================================
    // DELETE PROJECT
    // =====================================================

    public function destroy(
        Request $request,
        Project $project
    ) {
        DB::transaction(function () use (
            $request,
            $project
        ) {

            $oldData = [
                'id' => $project->id,
                'code' => $project->code,
                'name' => $project->name,
                'customer_name' => $project->customer_name,
                'total' => $project->total,
                'status' => $project->status,
                'completed_at' => $project->completed_at,
            ];

            $projectName = $project->name;

            /*
             * Kembalikan stok barang yang digunakan
             * oleh project.
             */
            $items = $project->items()->get();

            foreach ($items as $item) {

                if (
                    $item->type === 'product' &&
                    $item->product_id
                ) {

                    $product = Product::lockForUpdate()
                        ->find($item->product_id);

                    if ($product) {
                        $product->increment(
                            'stock',
                            $item->quantity
                        );
                    }
                }
            }

            $project->delete();

            $user = $request->user();

            if ($user) {
                ActivityLogService::log(
                    $user->id,
                    'DELETE',
                    'PROJECT',
                    'Menghapus project ' . $projectName,
                    $oldData,
                    null
                );
            }
        });

        return redirect()
            ->route('admin.projects.index')
            ->with(
                'success',
                'Project berhasil dihapus.'
            );
    }


    // =====================================================
    // STORE PROJECT ITEM
    // =====================================================

    public function storeItem(
        Request $request,
        Project $project
    ) {
        $validated = $request->validate([
            'type' => [
                'required',
                'in:product,service'
            ],

            'product_id' => [
                'nullable',
                'required_if:type,product',
                'exists:products,id'
            ],

            'service_id' => [
                'nullable',
                'required_if:type,service',
                'exists:services,id'
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ]);

        return DB::transaction(function () use (
            $request,
            $project,
            $validated
        ) {

            // =================================================
            // BARANG / PRODUCT
            // =================================================

            if ($validated['type'] === 'product') {

                $product = Product::lockForUpdate()
                    ->findOrFail(
                        $validated['product_id']
                    );

                $quantity = (int) $validated['quantity'];

                // Cek stok
                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'quantity' =>
                            "Stok {$product->name} tidak mencukupi. "
                            . "Stok tersedia: {$product->stock}."
                    ]);
                }

                $price = (float) $product->selling_price;

                $subtotal = $price * $quantity;

                // Kurangi stok
                $product->decrement(
                    'stock',
                    $quantity
                );

                $item = ProjectItem::create([
                    'project_id' => $project->id,
                    'type' => 'product',
                    'product_id' => $product->id,
                    'service_id' => null,
                    'name' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            // =================================================
            // JASA / SERVICE
            // =================================================

            else {

                $service = Service::findOrFail(
                    $validated['service_id']
                );

                $quantity = (int) $validated['quantity'];

                $price = (float) $service->price;

                $subtotal = $price * $quantity;

                $item = ProjectItem::create([
                    'project_id' => $project->id,
                    'type' => 'service',
                    'product_id' => null,
                    'service_id' => $service->id,
                    'name' => $service->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            // Hitung ulang total project
            $this->recalculateTotal($project);

            $project->refresh();

            // =================================================
            // ACTIVITY LOG
            // =================================================

            $user = $request->user();

            if ($user) {

                ActivityLogService::log(
                    $user->id,
                    'CREATE',
                    'PROJECT_ITEM',
                    'Menambahkan item ke project ' . $project->name,
                    null,
                    [
                        'id' => $item->id,
                        'project_id' => $project->id,
                        'type' => $item->type,
                        'product_id' => $item->product_id,
                        'service_id' => $item->service_id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                        'project_total' => $project->total,
                    ]
                );
            }

            return redirect()
                ->route(
                    'admin.projects.show',
                    $project
                )
                ->with(
                    'success',
                    'Item project berhasil ditambahkan.'
                );
        });
    }


    // =====================================================
    // UPDATE PROJECT ITEM
    // =====================================================

    public function updateItem(
        Request $request,
        Project $project,
        ProjectItem $item
    ) {
        // Pastikan item memang milik project tersebut
        if ($item->project_id !== $project->id) {
            abort(404);
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ]);

        return DB::transaction(function () use (
            $request,
            $project,
            $item,
            $validated
        ) {

            $oldQuantity = (int) $item->quantity;

            $newQuantity = (int) $validated['quantity'];

            $difference =
                $newQuantity - $oldQuantity;


            // =================================================
            // JIKA ITEM PRODUCT
            // =================================================

            if (
                $item->type === 'product' &&
                $item->product_id
            ) {

                $product = Product::lockForUpdate()
                    ->findOrFail(
                        $item->product_id
                    );


                // Quantity bertambah
                if ($difference > 0) {

                    if (
                        $product->stock < $difference
                    ) {
                        throw ValidationException::withMessages([
                            'quantity' =>
                                "Stok {$product->name} tidak mencukupi. "
                                . "Stok tersedia: {$product->stock}."
                        ]);
                    }

                    $product->decrement(
                        'stock',
                        $difference
                    );
                }


                // Quantity berkurang
                elseif ($difference < 0) {

                    $product->increment(
                        'stock',
                        abs($difference)
                    );
                }
            }


            // =================================================
            // HITUNG SUBTOTAL BARU
            // =================================================

            $price = (float) $item->price;

            $subtotal =
                $price * $newQuantity;

            $oldData = [
                'id' => $item->id,
                'project_id' => $project->id,
                'type' => $item->type,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $oldQuantity,
                'subtotal' => $item->subtotal,
            ];

            $item->update([
                'quantity' => $newQuantity,
                'subtotal' => $subtotal,
            ]);


            // Hitung ulang total
            $this->recalculateTotal($project);

            $project->refresh();

            // =================================================
            // ACTIVITY LOG
            // =================================================

            $user = $request->user();

            if ($user) {

                ActivityLogService::log(
                    $user->id,
                    'UPDATE',
                    'PROJECT_ITEM',
                    'Mengubah item project ' . $project->name,
                    $oldData,
                    [
                        'id' => $item->id,
                        'project_id' => $project->id,
                        'type' => $item->type,
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                        'project_total' => $project->total,
                    ]
                );
            }

            return redirect()
                ->route(
                    'admin.projects.show',
                    $project
                )
                ->with(
                    'success',
                    'Item project berhasil diperbarui.'
                );
        });
    }


    // =====================================================
    // DELETE PROJECT ITEM
    // =====================================================

    public function destroyItem(
        Request $request,
        Project $project,
        ProjectItem $item
    ) {
        // Pastikan item milik project
        if ($item->project_id !== $project->id) {
            abort(404);
        }

        return DB::transaction(function () use (
            $request,
            $project,
            $item
        ) {

            $oldData = [
                'id' => $item->id,
                'project_id' => $project->id,
                'type' => $item->type,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ];


            // =================================================
            // KEMBALIKAN STOK PRODUCT
            // =================================================

            if (
                $item->type === 'product' &&
                $item->product_id
            ) {

                $product = Product::lockForUpdate()
                    ->find(
                        $item->product_id
                    );

                if ($product) {

                    $product->increment(
                        'stock',
                        $item->quantity
                    );
                }
            }


            // Hapus item
            $item->delete();


            // Hitung ulang total
            $this->recalculateTotal($project);

            $project->refresh();


            // =================================================
            // ACTIVITY LOG
            // =================================================

            $user = $request->user();

            if ($user) {

                ActivityLogService::log(
                    $user->id,
                    'DELETE',
                    'PROJECT_ITEM',
                    'Menghapus item dari project ' . $project->name,
                    $oldData,
                    null
                );
            }


            return redirect()
                ->route(
                    'admin.projects.show',
                    $project
                )
                ->with(
                    'success',
                    'Item project berhasil dihapus.'
                );
        });
    }


    // =====================================================
    // STORE PROJECT COST
    // =====================================================

    public function storeCost(
        Request $request,
        Project $project
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],
        ]);

        return DB::transaction(function () use (
            $request,
            $project,
            $validated
        ) {

            $cost = ProjectCost::create([
                'project_id' => $project->id,
                'name' => $validated['name'],
                'amount' => $validated['amount'],
            ]);

            // Hitung ulang total
            $this->recalculateTotal($project);

            $project->refresh();


            // =================================================
            // ACTIVITY LOG
            // =================================================

            $user = $request->user();

            if ($user) {

                ActivityLogService::log(
                    $user->id,
                    'CREATE',
                    'PROJECT_COST',
                    'Menambahkan biaya tambahan ke project '
                        . $project->name,
                    null,
                    [
                        'id' => $cost->id,
                        'project_id' => $project->id,
                        'name' => $cost->name,
                        'amount' => $cost->amount,
                        'project_total' => $project->total,
                    ]
                );
            }


            return redirect()
                ->route(
                    'admin.projects.show',
                    $project
                )
                ->with(
                    'success',
                    'Biaya tambahan berhasil ditambahkan.'
                );
        });
    }


    // =====================================================
    // DELETE PROJECT COST
    // =====================================================

    public function destroyCost(
        Request $request,
        Project $project,
        ProjectCost $cost
    ) {
        // Pastikan cost milik project
        if ($cost->project_id !== $project->id) {
            abort(404);
        }

        return DB::transaction(function () use (
            $request,
            $project,
            $cost
        ) {

            $oldData = [
                'id' => $cost->id,
                'project_id' => $project->id,
                'name' => $cost->name,
                'amount' => $cost->amount,
            ];

            $cost->delete();

            // Hitung ulang total
            $this->recalculateTotal($project);

            $project->refresh();

            // =================================================
            // ACTIVITY LOG
            // =================================================

            $user = $request->user();

            if ($user) {

                ActivityLogService::log(
                    $user->id,
                    'DELETE',
                    'PROJECT_COST',
                    'Menghapus biaya tambahan dari project '
                        . $project->name,
                    $oldData,
                    null
                );
            }


            return redirect()
                ->route(
                    'admin.projects.show',
                    $project
                )
                ->with(
                    'success',
                    'Biaya tambahan berhasil dihapus.'
                );
        });
    }


    // =====================================================
    // RECALCULATE PROJECT TOTAL
    // =====================================================

    private function recalculateTotal(
        Project $project
    ) {
        $totalItems = $project
            ->items()
            ->sum('subtotal');

        $totalCosts = $project
            ->costs()
            ->sum('amount');

        $total =
            (float) $totalItems +
            (float) $totalCosts;

        $project->update([
            'total' => $total
        ]);
    }
}