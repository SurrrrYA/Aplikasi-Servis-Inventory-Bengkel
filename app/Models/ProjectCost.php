<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCost extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Biaya ini milik satu project
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}