<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReceiptSetting;
use Illuminate\Http\Request;

class ReceiptSettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan struk.
     */
    public function edit()
    {
        $setting = ReceiptSetting::first();

        // Jika belum ada setting, buat default
        if (!$setting) {

            $setting = new ReceiptSetting();

            $setting->business_name = 'BENGKEL SEMPOELOER';
            $setting->subtitle = 'Servis & Sparepart Motor';
            $setting->address = null;
            $setting->phone = null;

            $setting->show_cashier = true;
            $setting->show_customer = true;
            $setting->show_customer_phone = true;
            $setting->show_vehicle = true;
            $setting->show_notes = true;

            $setting->footer = 'Terima kasih';

            $setting->save();
        }

        return view(
            'admin.receipt-settings',
            compact('setting')
        );
    }


    /**
     * Menyimpan perubahan pengaturan struk.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([

            'business_name' => [
                'required',
                'string',
                'max:255'
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255'
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'footer' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'show_cashier' => [
                'nullable',
                'boolean'
            ],

            'show_customer' => [
                'nullable',
                'boolean'
            ],

            'show_customer_phone' => [
                'nullable',
                'boolean'
            ],

            'show_vehicle' => [
                'nullable',
                'boolean'
            ],

            'show_notes' => [
                'nullable',
                'boolean'
            ],
        ]);


        $setting = ReceiptSetting::first();


        if (!$setting) {

            $setting = new ReceiptSetting();
        }


        // =====================================================
        // DATA UTAMA
        // =====================================================

        $setting->business_name =
            $validated['business_name'];

        $setting->subtitle =
            $validated['subtitle'] ?? null;

        $setting->address =
            $validated['address'] ?? null;

        $setting->phone =
            $validated['phone'] ?? null;

        $setting->footer =
            $validated['footer'] ?? null;


        // =====================================================
        // CHECKBOX
        // =====================================================

        $setting->show_cashier =
            $request->boolean('show_cashier');

        $setting->show_customer =
            $request->boolean('show_customer');

        $setting->show_customer_phone =
            $request->boolean('show_customer_phone');

        $setting->show_vehicle =
            $request->boolean('show_vehicle');

        $setting->show_notes =
            $request->boolean('show_notes');


        $setting->save();


        return redirect()
            ->route('admin.receipt-settings.edit')
            ->with(
                'success',
                'Pengaturan struk berhasil disimpan.'
            );
    }
}