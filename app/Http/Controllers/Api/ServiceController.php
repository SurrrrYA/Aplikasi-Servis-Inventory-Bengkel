<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // =========================
    // GET SEMUA JASA
    // =========================

    public function index()
    {
        $services = Service::latest()->get();

        return response()->json([
            'message' => 'Data jasa berhasil diambil',
            'data' => $services,
        ]);
    }


    // =========================
    // TAMBAH JASA
    // =========================

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);


        // =========================
        // GENERATE KODE JASA
        // =========================

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

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $code = 'JASA-' . str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );


        // =========================
        // SIMPAN JASA
        // =========================

        $service = Service::create([
            'code' => $code,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);


        // =========================
        // ACTIVITY LOG
        // =========================

        ActivityLogService::log(
            $request->user()->id,
            'CREATE',
            'SERVICE',
            'Menambahkan jasa ' . $service->name,
            null,
            [
                'id' => $service->id,
                'code' => $service->code,
                'name' => $service->name,
                'price' => $service->price,
            ]
        );


        // =========================
        // RESPONSE
        // =========================

        return response()->json([
            'message' => 'Jasa berhasil ditambahkan',
            'data' => $service,
        ], 201);
    }


    // =========================
    // DETAIL JASA
    // =========================

    public function show(Service $service)
    {
        return response()->json([
            'message' => 'Detail jasa berhasil diambil',
            'data' => $service,
        ]);
    }


    // =========================
    // EDIT JASA
    // =========================

    public function update(
        Request $request,
        Service $service
    ) {

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);


        // =========================
        // DATA LAMA
        // =========================

        $oldData = [
            'code' => $service->code,
            'name' => $service->name,
            'price' => $service->price,
            'description' => $service->description,
        ];


        // =========================
        // UPDATE
        // =========================

        $service->update([
            // KODE TIDAK DIUBAH
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);


        // =========================
        // ACTIVITY LOG
        // =========================

        ActivityLogService::log(
            $request->user()->id,
            'UPDATE',
            'SERVICE',
            'Mengubah jasa ' . $service->name,
            $oldData,
            [
                'code' => $service->code,
                'name' => $service->name,
                'price' => $service->price,
                'description' => $service->description,
            ]
        );


        // =========================
        // RESPONSE
        // =========================

        return response()->json([
            'message' => 'Jasa berhasil diperbarui',
            'data' => $service,
        ]);
    }


    // =========================
    // HAPUS JASA
    // =========================

    public function destroy(
        Request $request,
        Service $service
    ) {

        // =========================
        // DATA LAMA
        // =========================

        $oldData = [
            'id' => $service->id,
            'code' => $service->code,
            'name' => $service->name,
            'price' => $service->price,
        ];


        $serviceName = $service->name;


        // =========================
        // HAPUS
        // =========================

        $service->delete();


        // =========================
        // ACTIVITY LOG
        // =========================

        ActivityLogService::log(
            $request->user()->id,
            'DELETE',
            'SERVICE',
            'Menghapus jasa ' . $serviceName,
            $oldData,
            null
        );


        // =========================
        // RESPONSE
        // =========================

        return response()->json([
            'message' => 'Jasa berhasil dihapus',
        ]);
    }
}