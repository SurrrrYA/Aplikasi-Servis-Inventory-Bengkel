@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('page-title', 'Manajemen User')

@section('content')

{{-- ================================================= --}}
{{-- HEADER --}}
{{-- ================================================= --}}

<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Manajemen User
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Kelola akun dan hak akses pengguna aplikasi.
        </p>

    </div>


    <a
        href="{{ route('admin.users.create') }}"
        class="inline-flex items-center justify-center
               gap-2 px-4 py-2.5
               bg-gray-900 text-white
               rounded-lg text-sm font-medium
               hover:bg-gray-800 transition"
    >

        <span>+</span>

        Tambah User

    </a>

</div>



{{-- ================================================= --}}
{{-- ALERT SUCCESS --}}
{{-- ================================================= --}}

@if(session('success'))

    <div
        class="mb-6 px-4 py-3
               rounded-lg
               bg-gray-50
               border border-gray-200
               text-sm text-gray-700"
    >

        {{ session('success') }}

    </div>

@endif



{{-- ================================================= --}}
{{-- ALERT ERROR --}}
{{-- ================================================= --}}

@if(session('error'))

    <div
        class="mb-6 px-4 py-3
               rounded-lg
               bg-gray-50
               border border-gray-200
               text-sm text-gray-700"
    >

        {{ session('error') }}

    </div>

@endif



{{-- ================================================= --}}
{{-- FILTER --}}
{{-- ================================================= --}}

<div
    class="bg-white
           border border-gray-200
           rounded-xl p-5 mb-6"
>

    <form
        method="GET"
        action="{{ route('admin.users.index') }}"
        class="grid grid-cols-1 md:grid-cols-4 gap-4"
    >

        {{-- SEARCH --}}

        <div class="md:col-span-2">

            <label
                class="block text-sm font-medium
                       text-gray-700 mb-2"
            >
                Cari User
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau email..."
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


        {{-- ROLE --}}

        <div>

            <label
                class="block text-sm font-medium
                       text-gray-700 mb-2"
            >
                Role
            </label>

            <select
                name="role"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none"
            >

                <option value="">
                    Semua Role
                </option>

                <option
                    value="owner"
                    @selected(request('role') === 'owner')
                >
                    Owner
                </option>

                <option
                    value="admin"
                    @selected(request('role') === 'admin')
                >
                    Admin
                </option>

                <option
                    value="kasir"
                    @selected(request('role') === 'kasir')
                >
                    Kasir
                </option>

            </select>

        </div>


        {{-- BUTTON --}}

        <div class="flex items-end gap-2">

            <button
                type="submit"
                class="px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm font-medium
                       hover:bg-gray-800"
            >
                Filter
            </button>


            <a
                href="{{ route('admin.users.index') }}"
                class="px-4 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50"
            >
                Reset
            </a>

        </div>

    </form>

</div>



{{-- ================================================= --}}
{{-- TABLE USER --}}
{{-- ================================================= --}}

<div
    class="bg-white
           border border-gray-200
           rounded-xl
           overflow-hidden"
>

    <div
        class="px-6 py-5
               border-b border-gray-200"
    >

        <h2 class="font-semibold text-gray-900">
            Daftar User
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            User yang memiliki akses ke aplikasi.
        </p>

    </div>


    @if($users->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead
                    class="bg-gray-50
                           border-b border-gray-200"
                >

                    <tr>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            User
                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Email
                        </th>


                        <th
                            class="px-6 py-4
                                   text-center
                                   font-semibold
                                   text-gray-600"
                        >
                            Role
                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Dibuat
                        </th>


                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y divide-gray-100"
                >

                    @foreach($users as $user)

                        <tr
                            class="hover:bg-gray-50"
                        >

                            {{-- USER --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="w-10 h-10
                                               rounded-full
                                               bg-gray-900
                                               text-white
                                               flex items-center
                                               justify-center
                                               font-semibold"
                                    >

                                        {{ strtoupper(
                                            substr($user->name, 0, 1)
                                        ) }}

                                    </div>


                                    <div>

                                        <p
                                            class="font-medium
                                                   text-gray-900"
                                        >
                                            {{ $user->name }}
                                        </p>

                                        <p
                                            class="text-xs
                                                   text-gray-500
                                                   mt-1"
                                        >
                                            User #{{ $user->id }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- EMAIL --}}

                            <td
                                class="px-6 py-4
                                       text-gray-600"
                            >

                                {{ $user->email }}

                            </td>


                            {{-- ROLE --}}

                            <td
                                class="px-6 py-4
                                       text-center"
                            >

                                @if($user->role === 'owner')

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-900
                                               text-white"
                                    >
                                        Owner
                                    </span>

                                @elseif($user->role === 'admin')

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-100
                                               text-gray-700"
                                    >
                                        Admin
                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-50
                                               text-gray-600
                                               border
                                               border-gray-200"
                                    >
                                        Kasir
                                    </span>

                                @endif

                            </td>


                            {{-- TANGGAL --}}

                            <td
                                class="px-6 py-4
                                       text-gray-600"
                            >

                                {{ $user->created_at
                                    ? $user->created_at->format('d M Y')
                                    : '-'
                                }}

                            </td>


                            {{-- AKSI --}}

                            <td
                                class="px-6 py-4
                                       text-right"
                            >

                                <a
                                    href="{{ route(
                                        'admin.users.show',
                                        $user
                                    ) }}"
                                    class="text-sm
                                           font-medium
                                           text-gray-700
                                           hover:text-black"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        <div
            class="px-6 py-4
                   border-t border-gray-200"
        >

            {{ $users->links() }}

        </div>

    @else

        <div
            class="py-16
                   text-center"
        >

            <div class="text-4xl mb-3">
                👤
            </div>

            <p
                class="font-medium
                       text-gray-700"
            >
                Belum ada user
            </p>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                User yang ditambahkan akan muncul di sini.
            </p>

        </div>

    @endif

</div>

@endsection