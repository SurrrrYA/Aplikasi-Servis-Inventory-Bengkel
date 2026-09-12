@extends('layouts.admin')

@section('title', 'Tambah User')

@section('page-title', 'Tambah User')

@section('content')

<div class="max-w-2xl">

    {{-- HEADER --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900">
            Tambah User
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Tambahkan akun baru untuk mengakses aplikasi.
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
            action="{{ route('admin.users.store') }}"
            class="p-6 space-y-6"
        >

            @csrf


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
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama user"
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
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
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

                    <option value="">
                        Pilih Role
                    </option>

                    <option
                        value="owner"
                        @selected(old('role') === 'owner')
                    >
                        Owner
                    </option>

                    <option
                        value="admin"
                        @selected(old('role') === 'admin')
                    >
                        Admin
                    </option>

                    <option
                        value="kasir"
                        @selected(old('role') === 'kasir')
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
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
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
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
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

            </div>


            {{-- BUTTON --}}

            <div
                class="pt-4
                       border-t border-gray-200
                       flex items-center
                       justify-end gap-3"
            >

                <a
                    href="{{ route('admin.users.index') }}"
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
                    Simpan User
                </button>

            </div>

        </form>

    </div>

</div>

@endsection