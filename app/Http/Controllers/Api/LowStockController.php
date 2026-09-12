<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class LowStockController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json([
            'message' => 'Data stok menipis berhasil diambil',
            'total' => $products->count(),
            'data' => $products
        ]);
    }
}