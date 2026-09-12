<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select(
            'id',
            'name',
            'email',
            'role',
            'created_at'
        )->get();

        return response()->json([
            'message' => 'Data user berhasil diambil',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:owner,admin,kasir',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        ActivityLogService::log(
            $request->user()->id,
            'CREATE',
            'USER',
            'Menambahkan user ' . $user->name,
            null,
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        );

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'message' => 'Detail user berhasil diambil',
            'data' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|required|in:owner,admin,kasir',
            'password' => 'nullable|min:8',
        ]);

        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->role = $request->input('role', $user->role);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        ActivityLogService::log(
            $request->user()->id,
            'UPDATE',
            'USER',
            'Mengubah data user ' . $user->name,
            $oldData,
            [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        );

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'data' => $user
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        $oldData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $userName = $user->name;

        $user->delete();

        ActivityLogService::log(
            $request->user()->id,
            'DELETE',
            'USER',
            'Menghapus user ' . $userName,
            $oldData,
            null
        );

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }
}