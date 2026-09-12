<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // =====================================================
    // DAFTAR JASA
    // =====================================================

    public function index(Request $request)
    {
        $query = Service::query();

        // SEARCH
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'code',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $services = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.services.index',
            compact('services')
        );
    }


    // =====================================================
    // FORM TAMBAH JASA
    // =====================================================

    public function create()
    {
        return view(
            'admin.services.create'
        );
    }


    // =====================================================
    // SIMPAN JASA
    // =====================================================

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);


        // =================================================
        // GENERATE KODE JASA
        // =================================================

        $lastService = Service::where(
            'code',
            'like',
            'JASA-%'
        )
            ->orderBy('id', 'desc')
            ->first();

        if ($lastService) {

            $lastNumber = (int) str_replace(
                'JASA-',
                '',
                $lastService->code
            );

            $nextNumber =
                $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $code = 'JASA-' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );


        // =================================================
        // SIMPAN JASA
        // =================================================

        $service = Service::create([

            'code' =>
                $code,

            'name' =>
                $validated['name'],

            'price' =>
                $validated['price'],

            'description' =>
                $validated['description'] ?? null,
        ]);


        // =================================================
        // ACTIVITY LOG - CREATE
        // =================================================

        $user = $request->user();

        if ($user) {

            ActivityLogService::log(

                $user->id,

                'CREATE',

                'SERVICE',

                'Menambahkan jasa ' .
                    $service->name,

                null,

                [

                    'id' =>
                        $service->id,

                    'code' =>
                        $service->code,

                    'name' =>
                        $service->name,

                    'price' =>
                        $service->price,

                    'description' =>
                        $service->description,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.services.index'
            )
            ->with(
                'success',
                'Jasa berhasil ditambahkan.'
            );
    }


    // =====================================================
    // DETAIL JASA
    // =====================================================

    public function show(Service $service)
    {
        return view(
            'admin.services.show',
            compact('service')
        );
    }


    // =====================================================
    // FORM EDIT JASA
    // =====================================================

    public function edit(Service $service)
    {
        return view(
            'admin.services.edit',
            compact('service')
        );
    }


    // =====================================================
    // UPDATE JASA
    // =====================================================

    public function update(
        Request $request,
        Service $service
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);


        // =================================================
        // DATA LAMA
        // =================================================

        $oldData = [

            'id' =>
                $service->id,

            'code' =>
                $service->code,

            'name' =>
                $service->name,

            'price' =>
                $service->price,

            'description' =>
                $service->description,
        ];


        // =================================================
        // UPDATE JASA
        // =================================================

        $service->update([

            // KODE TIDAK DIUBAH

            'name' =>
                $validated['name'],

            'price' =>
                $validated['price'],

            'description' =>
                $validated['description'] ?? null,
        ]);


        // =================================================
        // ACTIVITY LOG - UPDATE
        // =================================================

        $user = $request->user();

        if ($user) {

            ActivityLogService::log(

                $user->id,

                'UPDATE',

                'SERVICE',

                'Mengubah jasa ' .
                    $service->name,

                $oldData,

                [

                    'id' =>
                        $service->id,

                    'code' =>
                        $service->code,

                    'name' =>
                        $service->name,

                    'price' =>
                        $service->price,

                    'description' =>
                        $service->description,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.services.show',
                $service
            )
            ->with(
                'success',
                'Jasa berhasil diperbarui.'
            );
    }


    // =====================================================
    // HAPUS JASA
    // =====================================================

    public function destroy(
        Request $request,
        Service $service
    ) {

        // =================================================
        // DATA LAMA
        // =================================================

        $oldData = [

            'id' =>
                $service->id,

            'code' =>
                $service->code,

            'name' =>
                $service->name,

            'price' =>
                $service->price,

            'description' =>
                $service->description,
        ];


        $serviceName =
            $service->name;


        // =================================================
        // HAPUS JASA
        // =================================================

        $service->delete();


        // =================================================
        // ACTIVITY LOG - DELETE
        // =================================================

        $user = $request->user();

        if ($user) {

            ActivityLogService::log(

                $user->id,

                'DELETE',

                'SERVICE',

                'Menghapus jasa ' .
                    $serviceName,

                $oldData,

                null
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.services.index'
            )
            ->with(
                'success',
                'Jasa berhasil dihapus.'
            );
    }
}