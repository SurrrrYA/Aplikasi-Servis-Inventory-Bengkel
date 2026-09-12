<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptSetting extends Model
{
    protected $fillable = [
        'business_name',
        'subtitle',
        'address',
        'phone',
        'show_cashier',
        'show_customer',
        'show_customer_phone',
        'show_vehicle',
        'show_notes',
        'footer',
    ];

    protected $casts = [
        'show_cashier' => 'boolean',
        'show_customer' => 'boolean',
        'show_customer_phone' => 'boolean',
        'show_vehicle' => 'boolean',
        'show_notes' => 'boolean',
    ];
}