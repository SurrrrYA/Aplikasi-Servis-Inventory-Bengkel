@extends('layouts.admin')

@section('title', 'Customer')

@section('page-title', 'Customer')

@section('content')

{{-- ================================================= --}}
{{-- HEADER --}}
{{-- ================================================= --}}

<div class="mb-8">

    <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-4">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Customer
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola data customer Bengkel Sempoeloer.
            </p>

        </div>


        {{-- TAMBAH CUSTOMER --}}

        <a
            href="{{ route('admin.customers.create') }}"
            class="inline-flex items-center justify-center
                   gap-2 px-5 py-3
                   rounded-lg bg-gray-900
                   text-white text-sm font-medium
                   hover:bg-gray-800 transition"
        >

            <span>+</span>

            <span>
                Tambah Customer
            </span>

        </a>

    </div>

</div>



{{-- ================================================= --}}
{{-- SUCCESS MESSAGE --}}
{{-- ================================================= --}}

@if(session('success'))

    <div
        class="mb-6 rounded-lg
               border border-green-200
               bg-green-50
               px-5 py-4"
    >

        <p class="text-sm font-medium text-green-700">

            {{ session('success') }}

        </p>

    </div>

@endif



{{-- ================================================= --}}
{{-- SEARCH --}}
{{-- ================================================= --}}

<div
    class="bg-white rounded-xl
           border border-gray-200
           p-5 mb-6"
>

    <form
        method="GET"
        action="{{ route('admin.customers.index') }}"
    >

        <div
            class="flex flex-col
                   sm:flex-row gap-3"
        >

            <div class="flex-1">

                <label
                    for="search"
                    class="block text-sm
                           font-medium text-gray-700
                           mb-2"
                >
                    Cari Customer
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Nama, nomor telepon, atau alamat..."
                    class="w-full rounded-lg
                           border border-gray-300
                           px-4 py-3
                           text-sm
                           focus:border-gray-900
                           focus:ring-1
                           focus:ring-gray-900
                           outline-none"
                >

            </div>


            <div
                class="flex items-end gap-2"
            >

                <button
                    type="submit"
                    class="px-5 py-3
                           rounded-lg
                           bg-gray-900
                           text-white
                           text-sm font-medium
                           hover:bg-gray-800
                           transition"
                >
                    Cari
                </button>


                @if($search)

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="px-5 py-3
                               rounded-lg
                               border border-gray-300
                               text-gray-700
                               text-sm font-medium
                               hover:bg-gray-50
                               transition"
                    >
                        Reset
                    </a>

                @endif

            </div>

        </div>

    </form>

</div>



{{-- ================================================= --}}
{{-- TABLE --}}
{{-- ================================================= --}}

<div
    class="bg-white rounded-xl
           border border-gray-200
           overflow-hidden"
>


    {{-- TABLE HEADER --}}

    <div
        class="px-6 py-5
               border-b border-gray-200
               flex items-center
               justify-between"
    >

        <div>

            <h2
                class="font-semibold text-gray-900"
            >
                Daftar Customer
            </h2>

            <p
                class="text-sm text-gray-500 mt-1"
            >
                {{ $customers->total() }}
                customer terdaftar.
            </p>

        </div>

    </div>



    {{-- TABLE --}}

    @if($customers->count() > 0)

        <div class="overflow-x-auto">

            <table
                class="w-full text-sm"
            >

                <thead>

                    <tr
                        class="bg-gray-50
                               border-b border-gray-200"
                    >

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            #
                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Customer
                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Telepon
                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Alamat
                        </th>


                        <th
                            class="px-6 py-4
                                   text-center
                                   font-semibold
                                   text-gray-600"
                        >
                            Kendaraan
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
                    class="divide-y
                           divide-gray-100"
                >

                    @foreach($customers as $customer)

                        <tr
                            class="hover:bg-gray-50
                                   transition"
                        >

                            {{-- NOMOR --}}

                            <td
                                class="px-6 py-4
                                       text-gray-500"
                            >

                                {{
                                    $customers->firstItem()
                                    + $loop->index
                                }}

                            </td>


                            {{-- CUSTOMER --}}

                            <td
                                class="px-6 py-4"
                            >

                                <div
                                    class="flex items-center
                                           gap-3"
                                >

                                    <div
                                        class="w-10 h-10
                                               rounded-full
                                               bg-gray-900
                                               text-white
                                               flex items-center
                                               justify-center
                                               font-semibold"
                                    >

                                        {{
                                            strtoupper(
                                                substr(
                                                    $customer->name,
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>


                                    <div>

                                        <p
                                            class="font-semibold
                                                   text-gray-900"
                                        >

                                            {{ $customer->name }}

                                        </p>

                                        <p
                                            class="text-xs
                                                   text-gray-500"
                                        >

                                            ID #{{ $customer->id }}

                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- TELEPON --}}

                            <td
                                class="px-6 py-4
                                       text-gray-600"
                            >

                                {{ $customer->phone ?: '-' }}

                            </td>


                            {{-- ALAMAT --}}

                            <td
                                class="px-6 py-4
                                       text-gray-600
                                       max-w-xs"
                            >

                                <span
                                    class="line-clamp-2"
                                >

                                    {{ $customer->address ?: '-' }}

                                </span>

                            </td>


                            {{-- KENDARAAN --}}

                            <td
                                class="px-6 py-4
                                       text-center"
                            >

                                <span
                                    class="inline-flex
                                           items-center
                                           justify-center
                                           min-w-8
                                           px-2 py-1
                                           rounded-full
                                           bg-gray-100
                                           text-gray-700
                                           text-xs
                                           font-semibold"
                                >

                                    {{ $customer->vehicles->count() }}

                                </span>

                            </td>


                            {{-- AKSI --}}

                            <td
                                class="px-6 py-4"
                            >

                                <div
                                    class="flex items-center
                                           justify-end gap-2"
                                >

                                    {{-- DETAIL --}}

                                    <a
                                        href="{{
                                            route(
                                                'admin.customers.show',
                                                $customer
                                            )
                                        }}"
                                        class="px-3 py-2
                                               rounded-lg
                                               border
                                               border-gray-300
                                               text-gray-700
                                               text-xs
                                               font-medium
                                               hover:bg-gray-50
                                               transition"
                                    >
                                        Detail
                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{
                                            route(
                                                'admin.customers.edit',
                                                $customer
                                            )
                                        }}"
                                        class="px-3 py-2
                                               rounded-lg
                                               bg-gray-900
                                               text-white
                                               text-xs
                                               font-medium
                                               hover:bg-gray-800
                                               transition"
                                    >
                                        Edit
                                    </a>


                                    {{-- HAPUS --}}

                                    <form
                                        method="POST"
                                        action="{{
                                            route(
                                                'admin.customers.destroy',
                                                $customer
                                            )
                                        }}"
                                        onsubmit="return confirm(
                                            'Yakin ingin menghapus customer ini?'
                                        )"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-3 py-2
                                                   rounded-lg
                                                   border
                                                   border-red-200
                                                   text-red-600
                                                   text-xs
                                                   font-medium
                                                   hover:bg-red-50
                                                   transition"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ================================================= --}}
        {{-- PAGINATION --}}
        {{-- ================================================= --}}

        @if($customers->hasPages())

            <div
                class="px-6 py-5
                       border-t border-gray-200"
            >

                {{ $customers->links() }}

            </div>

        @endif


    @else

        {{-- ================================================= --}}
        {{-- EMPTY STATE --}}
        {{-- ================================================= --}}

        <div
            class="px-6 py-16
                   text-center"
        >

            <div
                class="text-5xl mb-4"
            >
                👥
            </div>


            @if($search)

                <p
                    class="font-semibold
                           text-gray-700"
                >
                    Customer tidak ditemukan
                </p>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Tidak ada customer yang cocok
                    dengan pencarian "{{ $search }}".
                </p>


                <a
                    href="{{
                        route('admin.customers.index')
                    }}"
                    class="inline-block
                           mt-5 px-5 py-3
                           rounded-lg
                           bg-gray-900
                           text-white
                           text-sm font-medium
                           hover:bg-gray-800"
                >
                    Tampilkan Semua
                </a>

            @else

                <p
                    class="font-semibold
                           text-gray-700"
                >
                    Belum ada customer
                </p>

                <p
                    class="text-sm
                           text-gray-500
                           mt-1"
                >
                    Data customer dari aplikasi kasir
                    akan muncul di sini.
                </p>


                <a
                    href="{{
                        route('admin.customers.create')
                    }}"
                    class="inline-block
                           mt-5 px-5 py-3
                           rounded-lg
                           bg-gray-900
                           text-white
                           text-sm font-medium
                           hover:bg-gray-800"
                >
                    Tambah Customer
                </a>

            @endif

        </div>

    @endif

</div>

@endsection