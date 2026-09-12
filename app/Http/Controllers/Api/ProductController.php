<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' => 'Data barang berhasil diambil',
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
        ]);

        // Ambil kategori pertama sebagai kategori default
        $category = Category::first();

        if (!$category) {
            return response()->json([
                'message' => 'Kategori produk belum tersedia'
            ], 422);
        }

        // Generate kode produk otomatis
        $lastProduct = Product::latest('id')->first();

        if ($lastProduct) {
            $number = $lastProduct->id + 1;
        } else {
            $number = 1;
        }

        $code = 'PRD' . str_pad(
            $number,
            4,
            '0',
            STR_PAD_LEFT
        );

        $product = Product::create([
            'category_id' => $category->id,
            'code' => $code,
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
            'minimum_stock' => $request->minimum_stock,
            'unit' => $request->unit ?? 'pcs',
            'description' => null,
        ]);

        ActivityLogService::log(
            $request->user()->id,
            'CREATE',
            'PRODUCT',
            'Menambahkan barang ' . $product->name,
            null,
            [
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'stock' => $product->stock,
            ]
        );

        return response()->json([
            'message' => 'Barang berhasil ditambahkan',
            'data' => $product->load('category')
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'message' => 'Detail barang berhasil diambil',
            'data' => $product->load('category')
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'code' => 'sometimes|required|string|max:255|unique:products,code,' . $product->id,
            'name' => 'sometimes|required|string|max:255',
            'purchase_price' => 'sometimes|required|numeric|min:0',
            'selling_price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'minimum_stock' => 'sometimes|required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $oldData = [
            'category_id' => $product->category_id,
            'code' => $product->code,
            'name' => $product->name,
            'purchase_price' => $product->purchase_price,
            'selling_price' => $product->selling_price,
            'stock' => $product->stock,
            'minimum_stock' => $product->minimum_stock,
            'unit' => $product->unit,
            'description' => $product->description,
        ];

        $product->update($request->only([
            'category_id',
            'code',
            'name',
            'purchase_price',
            'selling_price',
            'stock',
            'minimum_stock',
            'unit',
            'description',
        ]));

        ActivityLogService::log(
            $request->user()->id,
            'UPDATE',
            'PRODUCT',
            'Mengubah barang ' . $product->name,
            $oldData,
            [
                'category_id' => $product->category_id,
                'code' => $product->code,
                'name' => $product->name,
                'purchase_price' => $product->purchase_price,
                'selling_price' => $product->selling_price,
                'stock' => $product->stock,
                'minimum_stock' => $product->minimum_stock,
                'unit' => $product->unit,
                'description' => $product->description,
            ]
        );

        return response()->json([
            'message' => 'Barang berhasil diperbarui',
            'data' => $product->load('category')
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        $oldData = [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'code' => $product->code,
            'name' => $product->name,
            'stock' => $product->stock,
        ];

        $productName = $product->name;

        $product->delete();

        ActivityLogService::log(
            $request->user()->id,
            'DELETE',
            'PRODUCT',
            'Menghapus barang ' . $productName,
            $oldData,
            null
        );

        return response()->json([
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}