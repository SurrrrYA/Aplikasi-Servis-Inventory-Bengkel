<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // =========================
    // SEMUA KENDARAAN CUSTOMER
    // =========================

    public function index(
        Customer $customer
    ) {

        $vehicles =
            $customer
                ->vehicles()
                ->latest()
                ->get();

        return response()->json([
            'message' =>
                'Data kendaraan berhasil diambil',

            'data' =>
                $vehicles
        ]);
    }


    // =========================
    // DETAIL KENDARAAN
    // =========================

    public function show(
        Customer $customer,
        Vehicle $vehicle
    ) {

        if (
            $vehicle->customer_id
            !== $customer->id
        ) {

            return response()->json([
                'message' =>
                    'Kendaraan bukan milik customer tersebut'
            ], 404);
        }

        return response()->json([
            'message' =>
                'Detail kendaraan berhasil diambil',

            'data' =>
                $vehicle
        ]);
    }


    // =========================
    // TAMBAH KENDARAAN
    // =========================

    public function store(
        Request $request,
        Customer $customer
    ) {

        $request->validate([
            'plate_number' =>
                'required|string|max:20',

            'brand' =>
                'required|string|max:100',

            'model' =>
                'nullable|string|max:100',

            'year' =>
                'nullable|string|max:4',
        ]);

        $vehicle =
            $customer->vehicles()->create([
                'plate_number' =>
                    strtoupper(
                        trim(
                            $request->plate_number
                        )
                    ),

                'brand' =>
                    $request->brand,

                'model' =>
                    $request->model,

                'year' =>
                    $request->year,
            ]);

        return response()->json([
            'message' =>
                'Kendaraan berhasil ditambahkan',

            'data' =>
                $vehicle
        ], 201);
    }


    // =========================
    // UPDATE KENDARAAN
    // =========================

    public function update(
        Request $request,
        Customer $customer,
        Vehicle $vehicle
    ) {

        if (
            $vehicle->customer_id
            !== $customer->id
        ) {

            return response()->json([
                'message' =>
                    'Kendaraan bukan milik customer tersebut'
            ], 404);
        }

        $request->validate([
            'plate_number' =>
                'sometimes|required|string|max:20',

            'brand' =>
                'sometimes|required|string|max:100',

            'model' =>
                'nullable|string|max:100',

            'year' =>
                'nullable|string|max:4',
        ]);

        $vehicle->update([
            'plate_number' =>
                $request->input(
                    'plate_number',
                    $vehicle->plate_number
                ),

            'brand' =>
                $request->input(
                    'brand',
                    $vehicle->brand
                ),

            'model' =>
                $request->input(
                    'model',
                    $vehicle->model
                ),

            'year' =>
                $request->input(
                    'year',
                    $vehicle->year
                ),
        ]);

        return response()->json([
            'message' =>
                'Kendaraan berhasil diperbarui',

            'data' =>
                $vehicle
        ]);
    }


    // =========================
    // HAPUS KENDARAAN
    // =========================

    public function destroy(
        Customer $customer,
        Vehicle $vehicle
    ) {

        if (
            $vehicle->customer_id
            !== $customer->id
        ) {

            return response()->json([
                'message' =>
                    'Kendaraan bukan milik customer tersebut'
            ], 404);
        }

        $vehicle->delete();

        return response()->json([
            'message' =>
                'Kendaraan berhasil dihapus'
        ]);
    }
}