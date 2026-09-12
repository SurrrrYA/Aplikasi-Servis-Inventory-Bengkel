@extends('layouts.admin')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')

<div class="max-w-2xl">

    {{-- HEADER --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900">
            Edit User
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi akun pengguna.
        </p>

    </div>


    {{-- FORM --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl"
    >

        <form
            method="POST"
            action="{{ route('admin.users.update', $user) }}"
            class="p-6 space-y-6"
        >

            @csrf
            @method('PUT')


            {{-- NAMA --}}

            <div>

                <label
                    for="name"
                    class="block text-sm font-medium
                           text-gray-700 mb-2"
                >
                    Nama
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500
                           outline-none"
                >

                @error('name')
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- EMAIL --}}

            <div>

                <label
                    for="email"
                    class="block text-sm font-medium
                           text-gray-700 mb-2"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500
                           outline-none"
                >

                @error('email')
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ROLE --}}

            <div>

                <label
                    for="role"
                    class="block text-sm font-medium
                           text-gray-700 mb-2"
                >
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    required
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           outline-none"
                >

                    <option
                        value="owner"
                        @selected(
                            old('role', $user->role)
                            === 'owner'
                        )
                    >
                        Owner
                    </option>

                    <option
                        value="admin"
                        @selected(
                            old('role', $user->role)
                            === 'admin'
                        )
                    >
                        Admin
                    </option>

                    <option
                        value="kasir"
                        @selected(
                            old('role', $user->role)
                            === 'kasir'
                        )
                    >
                        Kasir
                    </option>

                </select>

                @error('role')
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- PASSWORD --}}

            <div>

                <label
                    for="password"
                    class="block text-sm font-medium
                           text-gray-700 mb-2"
                >
                    Password Baru
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Kosongkan jika tidak ingin mengubah"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500
                           outline-none"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Minimal 8 karakter.
                </p>

                @error('password')
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- KONFIRMASI PASSWORD --}}

            <div>

                <label
                    for="password_confirmation"
                    class="block text-sm font-medium
                           text-gray-700 mb-2"
                >
                    Konfirmasi Password Baru
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password baru"
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-2.5
                           text-sm
                           focus:border-gray-500
                           focus:ring-1
                           focus:ring-gray-500
                           outline-none"
                >

            </div>


            {{-- BUTTON --}}

            <div
                class="pt-4
                       border-t border-gray-200
                       flex items-center
                       justify-end gap-3"
            >

                <a
                    href="{{ route(
                        'admin.users.show',
                        $user
                    ) }}"
                    class="px-4 py-2.5
                           border border-gray-300
                           rounded-lg
                           text-sm font-medium
                           text-gray-700
                           hover:bg-gray-50"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="px-4 py-2.5
                           bg-gray-900
                           text-white
                           rounded-lg
                           text-sm font-medium
                           hover:bg-gray-800"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection