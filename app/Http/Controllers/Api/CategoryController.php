<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'message' => 'Data kategori berhasil diambil',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        ActivityLogService::log(
            $request->user()->id,
            'CREATE',
            'CATEGORY',
            'Menambahkan kategori ' . $category->name,
            null,
            [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
            ]
        );

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'message' => 'Detail kategori berhasil diambil',
            'data' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $oldData = [
            'name' => $category->name,
            'description' => $category->description,
        ];

        $category->update([
            'name' => $request->input('name', $category->name),
            'description' => $request->input(
                'description',
                $category->description
            ),
        ]);

        ActivityLogService::log(
            $request->user()->id,
            'UPDATE',
            'CATEGORY',
            'Mengubah kategori ' . $category->name,
            $oldData,
            [
                'name' => $category->name,
                'description' => $category->description,
            ]
        );

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category
        ]);
    }

    public function destroy(Request $request, Category $category)
    {
        $oldData = [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
        ];

        $categoryName = $category->name;

        $category->delete();

        ActivityLogService::log(
            $request->user()->id,
            'DELETE',
            'CATEGORY',
            'Menghapus kategori ' . $categoryName,
            $oldData,
            null
        );

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}