<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'unit',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}