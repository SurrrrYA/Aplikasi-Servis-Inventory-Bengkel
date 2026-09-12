<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // =====================================================
    // DAFTAR USER
    // =====================================================

    public function index(Request $request)
    {
        $query = User::query();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        // FILTER ROLE
        if ($request->filled('role')) {
            $query->where(
                'role',
                $request->role
            );
        }

        $users = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.users.index',
            compact('users')
        );
    }


    // =====================================================
    // FORM TAMBAH USER
    // =====================================================

    public function create()
    {
        return view('admin.users.create');
    }


    // =====================================================
    // SIMPAN USER
    // =====================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'role' => [
                'required',
                'in:owner,admin,kasir'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ]);


        // =================================================
        // SIMPAN USER
        // =================================================

        $user = User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'role' =>
                $validated['role'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'is_active' => true,
        ]);


        // =================================================
        // ACTIVITY LOG
        // =================================================

        $currentUser = $request->user();

        if ($currentUser) {
            ActivityLogService::log(
                $currentUser->id,
                'CREATE',
                'USER',
                'Menambahkan user ' .
                    $user->name,
                null,
                [
                    'id' =>
                        $user->id,

                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,

                    'role' =>
                        $user->role,

                    'is_active' =>
                        $user->is_active,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }


    // =====================================================
    // DETAIL USER
    // =====================================================

    public function show(User $user)
    {
        return view(
            'admin.users.show',
            compact('user')
        );
    }


    // =====================================================
    // FORM EDIT USER
    // =====================================================

    public function edit(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }


    // =====================================================
    // UPDATE USER
    // =====================================================

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email,' . $user->id
            ],

            'role' => [
                'required',
                'in:owner,admin,kasir'
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
        ]);


        // =================================================
        // CEGAH USER MENGUBAH ROLE AKUN SENDIRI
        // =================================================

        if (
            $request->user() &&
            $request->user()->id === $user->id
        ) {
            if (
                $validated['role'] !==
                $user->role
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Role akun sendiri tidak dapat diubah.'
                    );
            }
        }


        // =================================================
        // DATA LAMA
        // =================================================

        $oldData = [
            'id' =>
                $user->id,

            'name' =>
                $user->name,

            'email' =>
                $user->email,

            'role' =>
                $user->role,

            'is_active' =>
                $user->is_active,
        ];


        // =================================================
        // UPDATE DATA
        // =================================================

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->role =
            $validated['role'];


        // PASSWORD

        if (
            !empty(
                $validated['password']
            )
        ) {
            $user->password =
                Hash::make(
                    $validated['password']
                );
        }

        $user->save();


        // =================================================
        // ACTIVITY LOG
        // =================================================

        $currentUser =
            $request->user();

        if ($currentUser) {
            ActivityLogService::log(
                $currentUser->id,
                'UPDATE',
                'USER',
                'Mengubah data user ' .
                    $user->name,
                $oldData,
                [
                    'id' =>
                        $user->id,

                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,

                    'role' =>
                        $user->role,

                    'is_active' =>
                        $user->is_active,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.users.show',
                $user
            )
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }


    // =====================================================
    // AKTIFKAN / NONAKTIFKAN USER
    // =====================================================

    public function toggleStatus(
        Request $request,
        User $user
    ) {
        // =================================================
        // CEGAH MENGUBAH STATUS AKUN SENDIRI
        // =================================================

        if (
            $request->user() &&
            $request->user()->id === $user->id
        ) {
            return back()
                ->with(
                    'error',
                    'Status akun sendiri tidak dapat diubah.'
                );
        }


        // =================================================
        // STATUS LAMA
        // =================================================

        $oldStatus =
            $user->is_active;


        // =================================================
        // UBAH STATUS
        // =================================================

        $user->is_active =
            !$user->is_active;

        $user->save();


        // =================================================
        // ACTIVITY LOG
        // =================================================

        $currentUser =
            $request->user();

        if ($currentUser) {
            ActivityLogService::log(
                $currentUser->id,
                'UPDATE',
                'USER',
                (
                    $user->is_active
                        ? 'Mengaktifkan user '
                        : 'Menonaktifkan user '
                ) . $user->name,
                [
                    'is_active' =>
                        $oldStatus,
                ],
                [
                    'is_active' =>
                        $user->is_active,
                ]
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return back()
            ->with(
                'success',
                $user->is_active
                    ? 'User berhasil diaktifkan.'
                    : 'User berhasil dinonaktifkan.'
            );
    }


    // =====================================================
    // HAPUS USER
    // =====================================================

    public function destroy(
        Request $request,
        User $user
    ) {
        // =================================================
        // CEGAH HAPUS AKUN SENDIRI
        // =================================================

        if (
            $request->user() &&
            $request->user()->id === $user->id
        ) {
            return back()
                ->with(
                    'error',
                    'Akun yang sedang digunakan tidak dapat dihapus.'
                );
        }


        // =================================================
        // DATA LAMA
        // =================================================

        $oldData = [
            'id' =>
                $user->id,

            'name' =>
                $user->name,

            'email' =>
                $user->email,

            'role' =>
                $user->role,

            'is_active' =>
                $user->is_active,
        ];


        $userName =
            $user->name;


        // =================================================
        // HAPUS USER
        // =================================================

        $user->delete();


        // =================================================
        // ACTIVITY LOG
        // =================================================

        $currentUser =
            $request->user();

        if ($currentUser) {
            ActivityLogService::log(
                $currentUser->id,
                'DELETE',
                'USER',
                'Menghapus user ' .
                    $userName,
                $oldData,
                null
            );
        }


        // =================================================
        // REDIRECT
        // =================================================

        return redirect()
            ->route(
                'admin.users.index'
            )
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }
}
