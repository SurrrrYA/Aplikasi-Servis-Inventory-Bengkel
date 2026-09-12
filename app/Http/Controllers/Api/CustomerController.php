<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // =========================
    // SEMUA CUSTOMER
    // =========================

    public function index()
    {
        $customers = Customer::with('vehicles')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data customer berhasil diambil',
            'data' => $customers
        ]);
    }


    // =========================
    // DETAIL CUSTOMER
    // =========================

    public function show(Customer $customer)
    {
        $customer->load('vehicles');

        return response()->json([
            'message' => 'Detail customer berhasil diambil',
            'data' => $customer
        ]);
    }


    // =========================
    // TAMBAH CUSTOMER
    // =========================

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'phone' => 'nullable|string|max:30',

            'address' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'name' =>
                $request->name,

            'phone' =>
                $request->phone,

            'address' =>
                $request->address,
        ]);

        return response()->json([
            'message' => 'Customer berhasil ditambahkan',
            'data' => $customer
        ], 201);
    }


    // =========================
    // UPDATE CUSTOMER
    // =========================

    public function update(
        Request $request,
        Customer $customer
    ) {

        $request->validate([
            'name' => 'sometimes|required|string|max:255',

            'phone' => 'nullable|string|max:30',

            'address' => 'nullable|string',
        ]);

        $customer->update([
            'name' =>
                $request->input(
                    'name',
                    $customer->name
                ),

            'phone' =>
                $request->input(
                    'phone',
                    $customer->phone
                ),

            'address' =>
                $request->input(
                    'address',
                    $customer->address
                ),
        ]);

        return response()->json([
            'message' => 'Customer berhasil diperbarui',
            'data' => $customer
        ]);
    }


    // =========================
    // HAPUS CUSTOMER
    // =========================

    public function destroy(
        Customer $customer
    ) {

        $customer->delete();

        return response()->json([
            'message' => 'Customer berhasil dihapus'
        ]);
    }
}