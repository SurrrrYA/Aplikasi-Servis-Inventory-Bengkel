<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReceiptSetting;
use Illuminate\Http\Request;

class ReceiptSettingController extends Controller
{
    // =====================================================
    // GET PENGATURAN STRUK
    // =====================================================

    public function show()
    {
        $setting = ReceiptSetting::first();

        // Kalau belum ada, buat pengaturan default
        if (!$setting) {

            $setting = ReceiptSetting::create([
                'business_name' => 'BENGKEL SEMPOELOER',
                'subtitle' => 'Servis & Sparepart Motor',
                'address' => null,
                'phone' => null,

                'show_cashier' => true,
                'show_customer' => true,
                'show_customer_phone' => true,
                'show_vehicle' => true,
                'show_notes' => true,

                'footer' => 'TERIMA KASIH',
            ]);
        }

        return response()->json([
            'message' => 'Pengaturan struk berhasil diambil',
            'data' => $setting
        ]);
    }


    // =====================================================
    // UPDATE PENGATURAN STRUK
    // =====================================================

    public function update(Request $request)
    {
        $request->validate([
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
                'string'
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'show_cashier' => [
                'required',
                'boolean'
            ],

            'show_customer' => [
                'required',
                'boolean'
            ],

            'show_customer_phone' => [
                'required',
                'boolean'
            ],

            'show_vehicle' => [
                'required',
                'boolean'
            ],

            'show_notes' => [
                'required',
                'boolean'
            ],

            'footer' => [
                'nullable',
                'string'
            ],
        ]);


        $setting =
            ReceiptSetting::first();


        if (!$setting) {

            $setting =
                ReceiptSetting::create(
                    $request->all()
                );

        } else {

            $setting->update(
                $request->all()
            );
        }


        return response()->json([
            'message' => 'Pengaturan struk berhasil disimpan',
            'data' => $setting
        ]);
    }
}