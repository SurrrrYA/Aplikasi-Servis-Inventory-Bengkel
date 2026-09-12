@extends('layouts.admin')

@section('title', 'Inventory')

@section('page-title', 'Inventory')

@section('content')

{{-- HEADER --}}

<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Inventory
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Kelola produk, stok, dan harga barang.
        </p>

    </div>


    <a
        href="{{ route('admin.products.create') }}"
        class="inline-flex items-center justify-center
               gap-2 px-4 py-2.5
               bg-gray-900 text-white
               rounded-lg text-sm font-medium
               hover:bg-gray-800 transition"
    >
        <span class="text-lg leading-none">+</span>
        Tambah Produk
    </a>

</div>


{{-- SUCCESS MESSAGE --}}

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


{{-- STATISTIC --}}

<div
    class="grid grid-cols-1
           sm:grid-cols-3
           gap-5 mb-6"
>

    {{-- TOTAL PRODUK --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl p-5"
    >

        <p class="text-sm text-gray-500">
            Total Produk
        </p>

        <p
            class="mt-2 text-2xl
                   font-bold text-gray-900"
        >
            {{ $products->total() }}
        </p>

    </div>


    {{-- STOK MENIPIS --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl p-5"
    >

        <p class="text-sm text-gray-500">
            Stok Menipis
        </p>

        <p
            class="mt-2 text-2xl
                   font-bold text-gray-900"
        >
            {{ $lowStockCount ?? 0 }}
        </p>

    </div>


    {{-- KATEGORI --}}

    <div
        class="bg-white
               border border-gray-200
               rounded-xl p-5"
    >

        <p class="text-sm text-gray-500">
            Total Kategori
        </p>

        <p
            class="mt-2 text-2xl
                   font-bold text-gray-900"
        >
            {{ $categories->count() }}
        </p>

    </div>

</div>


{{-- FILTER --}}

<div
    class="bg-white
           border border-gray-200
           rounded-xl p-5 mb-6"
>

    <form
        method="GET"
        action="{{ route('admin.products.index') }}"
        class="grid grid-cols-1
               md:grid-cols-4
               gap-4"
    >

        {{-- SEARCH --}}

        <div class="md:col-span-2">

            <label
                class="block text-sm font-medium
                       text-gray-700 mb-2"
            >
                Cari Produk
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau kode produk..."
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


        {{-- KATEGORI --}}

        <div>

            <label
                class="block text-sm font-medium
                       text-gray-700 mb-2"
            >
                Kategori
            </label>

            <select
                name="category_id"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

                <option value="">
                    Semua Kategori
                </option>

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        @selected(
                            request('category_id') == $category->id
                        )
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- STATUS STOK --}}

        <div>

            <label
                class="block text-sm font-medium
                       text-gray-700 mb-2"
            >
                Status Stok
            </label>

            <select
                name="stock_status"
                class="w-full rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

                <option value="">
                    Semua
                </option>

                <option
                    value="low"
                    @selected(
                        request('stock_status') === 'low'
                    )
                >
                    Stok Menipis
                </option>

            </select>

        </div>


        {{-- BUTTON --}}

        <div
            class="md:col-span-4
                   flex gap-2"
        >

            <button
                type="submit"
                class="px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm font-medium
                       hover:bg-gray-800
                       transition"
            >
                Filter
            </button>


            <a
                href="{{ route('admin.products.index') }}"
                class="px-4 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm font-medium
                       text-gray-700
                       hover:bg-gray-50
                       transition"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- TABLE --}}

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
            Daftar Produk
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Produk yang tersedia di inventory.
        </p>

    </div>


    @if($products->count())

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
                            Produk
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Kategori
                        </th>

                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Harga Jual
                        </th>

                        <th
                            class="px-6 py-4
                                   text-center
                                   font-semibold
                                   text-gray-600"
                        >
                            Stok
                        </th>

                        <th
                            class="px-6 py-4
                                   text-center
                                   font-semibold
                                   text-gray-600"
                        >
                            Status
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


                <tbody class="divide-y divide-gray-100">

                    @foreach($products as $product)

                        <tr
                            class="hover:bg-gray-50 transition"
                        >

                            {{-- PRODUK --}}

                            <td class="px-6 py-4">

                                <div>

                                    <p
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $product->name }}
                                    </p>

                                    <p
                                        class="text-xs
                                               text-gray-500
                                               mt-1"
                                    >
                                        {{ $product->code }}
                                    </p>

                                </div>

                            </td>


                            {{-- KATEGORI --}}

                            <td
                                class="px-6 py-4
                                       text-gray-600"
                            >
                                {{ $product->category->name ?? '-' }}
                            </td>


                            {{-- HARGA --}}

                            <td
                                class="px-6 py-4
                                       text-right
                                       font-medium
                                       text-gray-900"
                            >

                                Rp
                                {{ number_format(
                                    $product->selling_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- STOK --}}

                            <td
                                class="px-6 py-4
                                       text-center"
                            >

                                <span
                                    class="font-semibold
                                           text-gray-900"
                                >
                                    {{ $product->stock }}
                                </span>

                                <span
                                    class="text-xs
                                           text-gray-500"
                                >
                                    {{ $product->unit }}
                                </span>

                            </td>


                            {{-- STATUS --}}

                            <td
                                class="px-6 py-4
                                       text-center"
                            >

                                @if(
                                    $product->stock
                                    <=
                                    $product->minimum_stock
                                )

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-100
                                               text-gray-700"
                                    >
                                        Stok Menipis
                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-50
                                               text-gray-600"
                                    >
                                        Aman
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}

                            <td
                                class="px-6 py-4
                                       text-right"
                            >

                                <a
                                    href="{{ route(
                                        'admin.products.show',
                                        $product
                                    ) }}"
                                    class="inline-flex
                                           items-center
                                           px-3 py-1.5
                                           rounded-lg
                                           text-sm
                                           font-medium
                                           text-gray-700
                                           hover:bg-gray-100
                                           hover:text-black
                                           transition"
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

            {{ $products->links() }}

        </div>

    @else

        {{-- DATA KOSONG --}}

        <div
            class="py-16
                   text-center"
        >

            <div class="text-4xl mb-3">
                📦
            </div>

            <p
                class="font-medium
                       text-gray-700"
            >
                Belum ada produk
            </p>

            <p
                class="text-sm
                       text-gray-500
                       mt-1"
            >
                Produk yang ditambahkan
                akan muncul di sini.
            </p>

            <a
                href="{{ route('admin.products.create') }}"
                class="inline-flex
                       mt-5
                       px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm
                       font-medium
                       hover:bg-gray-800"
            >
                Tambah Produk
            </a>

        </div>

    @endif

</div>

@endsection