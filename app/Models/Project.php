<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'code',
        'name',
        'customer_name',
        'vehicle_id',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // =========================
    // CUSTOMER / KENDARAAN
    // =========================

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // =========================
    // PROJECT ITEMS
    // =========================

    public function items(): HasMany
    {
        return $this->hasMany(ProjectItem::class);
    }

    // =========================
    // PROJECT COSTS
    // =========================

    public function costs(): HasMany
    {
        return $this->hasMany(ProjectCost::class);
    }
}