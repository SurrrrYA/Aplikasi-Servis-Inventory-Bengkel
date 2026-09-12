<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // =====================================================
    // DAFTAR CUSTOMER
    // =====================================================

    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::with('vehicles')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'address',
                        'like',
                        '%' . $search . '%'
                    );
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.customers.index',
            compact('customers', 'search')
        );
    }


    // =====================================================
    // DETAIL CUSTOMER
    // =====================================================

    public function show(Customer $customer)
    {
        $customer->load('vehicles');

        return view(
            'admin.customers.show',
            compact('customer')
        );
    }


    // =====================================================
    // FORM TAMBAH CUSTOMER
    // =====================================================

    public function create()
    {
        return view(
            'admin.customers.create'
        );
    }


    // =====================================================
    // SIMPAN CUSTOMER
    // =====================================================

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30'
            ],

            'address' => [
                'nullable',
                'string'
            ],
        ]);


        // =================================================
        // SIMPAN CUSTOMER
        // =================================================

        $customer = Customer::create(
            $validated
        );


        // =================================================
        // ACTIVITY LOG
        // =================================================

        if (Auth::check()) {

            ActivityLogService::log(

                Auth::id(),

                'CREATE',

                'CUSTOMER',

                'Menambahkan customer ' .
                    $customer->name,

                null,

                [

                    'id' =>
                        $customer->id,

                    'name' =>
                        $customer->name,

                    'phone' =>
                        $customer->phone,

                    'address' =>
                        $customer->address,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.customers.index'
            )
            ->with(
                'success',
                'Customer berhasil ditambahkan.'
            );
    }


    // =====================================================
    // FORM EDIT CUSTOMER
    // =====================================================

    public function edit(Customer $customer)
    {
        return view(
            'admin.customers.edit',
            compact('customer')
        );
    }


    // =====================================================
    // UPDATE CUSTOMER
    // =====================================================

    public function update(
        Request $request,
        Customer $customer
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30'
            ],

            'address' => [
                'nullable',
                'string'
            ],
        ]);


        // =================================================
        // SIMPAN DATA LAMA
        // =================================================

        $oldData = [

            'id' =>
                $customer->id,

            'name' =>
                $customer->name,

            'phone' =>
                $customer->phone,

            'address' =>
                $customer->address,
        ];


        // =================================================
        // UPDATE CUSTOMER
        // =================================================

        $customer->update(
            $validated
        );


        // =================================================
        // ACTIVITY LOG
        // =================================================

        if (Auth::check()) {

            ActivityLogService::log(

                Auth::id(),

                'UPDATE',

                'CUSTOMER',

                'Mengubah customer ' .
                    $customer->name,

                $oldData,

                [

                    'id' =>
                        $customer->id,

                    'name' =>
                        $customer->name,

                    'phone' =>
                        $customer->phone,

                    'address' =>
                        $customer->address,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.customers.show',
                $customer
            )
            ->with(
                'success',
                'Customer berhasil diperbarui.'
            );
    }


    // =====================================================
    // HAPUS CUSTOMER
    // =====================================================

    public function destroy(
        Customer $customer
    ) {

        // =================================================
        // SIMPAN DATA LAMA
        // =================================================

        $oldData = [

            'id' =>
                $customer->id,

            'name' =>
                $customer->name,

            'phone' =>
                $customer->phone,

            'address' =>
                $customer->address,
        ];


        $customerName =
            $customer->name;


        // =================================================
        // HAPUS CUSTOMER
        // =================================================

        $customer->delete();


        // =================================================
        // ACTIVITY LOG
        // =================================================

        if (Auth::check()) {

            ActivityLogService::log(

                Auth::id(),

                'DELETE',

                'CUSTOMER',

                'Menghapus customer ' .
                    $customerName,

                $oldData,

                null
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.customers.index'
            )
            ->with(
                'success',
                'Customer berhasil dihapus.'
            );
    }
}