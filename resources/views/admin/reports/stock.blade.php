@extends('layouts.admin')

@section('title', 'Laporan Stok')

@section('page-title', 'Laporan Stok')

@section('content')

{{-- ===================================================== --}}
{{-- HEADER --}}
{{-- ===================================================== --}}

<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Laporan Stok
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Informasi kondisi stok dan riwayat pergerakan barang.
        </p>

    </div>

</div>


{{-- ===================================================== --}}
{{-- SUMMARY --}}
{{-- ===================================================== --}}

<div class="grid grid-cols-1
            sm:grid-cols-2
            lg:grid-cols-4
            gap-4 mb-6">


    {{-- TOTAL PRODUK --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Produk
                </p>

                <p class="mt-2
                          text-2xl
                          font-bold
                          text-gray-900">

                    {{ number_format($totalProducts) }}

                </p>

            </div>

            <div class="w-10 h-10
                        rounded-lg
                        bg-gray-100
                        flex items-center
                        justify-center">

                📦

            </div>

        </div>

    </div>


    {{-- TOTAL STOK --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Stok
                </p>

                <p class="mt-2
                          text-2xl
                          font-bold
                          text-gray-900">

                    {{ number_format($totalStock) }}

                </p>

            </div>

            <div class="w-10 h-10
                        rounded-lg
                        bg-gray-100
                        flex items-center
                        justify-center">

                📊

            </div>

        </div>

    </div>


    {{-- STOK MENIPIS --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Stok Menipis
                </p>

                <p class="mt-2
                          text-2xl
                          font-bold
                          text-gray-900">

                    {{ number_format($lowStockProducts) }}

                </p>

            </div>

            <div class="w-10 h-10
                        rounded-lg
                        bg-gray-100
                        flex items-center
                        justify-center">

                ⚠️

            </div>

        </div>

    </div>


    {{-- NILAI PERSEDIAAN --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Nilai Persediaan
                </p>

                <p class="mt-2
                          text-xl
                          font-bold
                          text-gray-900">

                    Rp {{ number_format(
                        $totalInventoryValue,
                        0,
                        ',',
                        '.'
                    ) }}

                </p>

            </div>

            <div class="w-10 h-10
                        rounded-lg
                        bg-gray-100
                        flex items-center
                        justify-center">

                💰

            </div>

        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- STATUS SUMMARY --}}
{{-- ===================================================== --}}

<div class="grid grid-cols-1
            sm:grid-cols-3
            gap-4 mb-6">


    {{-- AMAN --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <p class="text-sm text-gray-500">
            Stok Aman
        </p>

        <p class="mt-2
                  text-2xl
                  font-bold
                  text-gray-900">

            {{ number_format($safeStockProducts) }}

        </p>

        <p class="mt-1 text-xs text-gray-500">
            Produk di atas batas minimum.
        </p>

    </div>


    {{-- MENIPIS --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <p class="text-sm text-gray-500">
            Stok Menipis
        </p>

        <p class="mt-2
                  text-2xl
                  font-bold
                  text-gray-900">

            {{ number_format($lowStockProducts) }}

        </p>

        <p class="mt-1 text-xs text-gray-500">
            Produk berada pada batas minimum.
        </p>

    </div>


    {{-- HABIS --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <p class="text-sm text-gray-500">
            Stok Habis
        </p>

        <p class="mt-2
                  text-2xl
                  font-bold
                  text-gray-900">

            {{ number_format($outOfStockProducts) }}

        </p>

        <p class="mt-1 text-xs text-gray-500">
            Produk dengan stok nol.
        </p>

    </div>

</div>


{{-- ===================================================== --}}
{{-- FILTER --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            p-5 mb-6">

    <div class="mb-4">

        <h2 class="font-semibold text-gray-900">
            Filter Laporan
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Gunakan filter untuk melihat data stok tertentu.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('admin.reports.stock') }}"
        class="grid grid-cols-1
               md:grid-cols-2
               lg:grid-cols-5
               gap-4"
    >


        {{-- KATEGORI --}}

        <div>

            <label
                class="block text-sm
                       font-medium
                       text-gray-700
                       mb-2"
            >
                Kategori
            </label>

            <select
                name="category_id"
                class="w-full
                       rounded-lg
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
                            $categoryId == $category->id
                        )
                    >
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- TANGGAL MULAI --}}

        <div>

            <label
                class="block text-sm
                       font-medium
                       text-gray-700
                       mb-2"
            >
                Dari Tanggal
            </label>

            <input
                type="date"
                name="start_date"
                value="{{ $startDate }}"
                class="w-full
                       rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

        </div>


        {{-- TANGGAL AKHIR --}}

        <div>

            <label
                class="block text-sm
                       font-medium
                       text-gray-700
                       mb-2"
            >
                Sampai Tanggal
            </label>

            <input
                type="date"
                name="end_date"
                value="{{ $endDate }}"
                class="w-full
                       rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

        </div>


        {{-- JENIS --}}

        <div>

            <label
                class="block text-sm
                       font-medium
                       text-gray-700
                       mb-2"
            >
                Jenis
            </label>

            <select
                name="type"
                class="w-full
                       rounded-lg
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
                    value="IN"
                    @selected($type === 'IN')
                >
                    Barang Masuk
                </option>

                <option
                    value="OUT"
                    @selected($type === 'OUT')
                >
                    Barang Keluar
                </option>

            </select>

        </div>


        {{-- BUTTON --}}

        <div class="flex items-end gap-2">

            <button
                type="submit"
                class="flex-1
                       px-4 py-2.5
                       bg-gray-900
                       text-white
                       rounded-lg
                       text-sm
                       font-medium
                       hover:bg-gray-800"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.reports.stock') }}"
                class="px-4 py-2.5
                       border border-gray-300
                       rounded-lg
                       text-sm
                       font-medium
                       text-gray-700
                       hover:bg-gray-50"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- ===================================================== --}}
{{-- PERGERAKAN SUMMARY --}}
{{-- ===================================================== --}}

<div class="grid grid-cols-1
            sm:grid-cols-2
            gap-4 mb-6">


    {{-- MASUK --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Barang Masuk
                </p>

                <p class="mt-2
                          text-2xl
                          font-bold
                          text-gray-900">

                    +{{ number_format($totalStockIn) }}

                </p>

            </div>

            <span
                class="inline-flex
                       px-3 py-1
                       rounded-full
                       text-xs
                       font-medium
                       bg-gray-100
                       text-gray-700"
            >
                IN
            </span>

        </div>

    </div>


    {{-- KELUAR --}}

    <div class="bg-white
                border border-gray-200
                rounded-xl
                p-5">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Barang Keluar
                </p>

                <p class="mt-2
                          text-2xl
                          font-bold
                          text-gray-900">

                    -{{ number_format($totalStockOut) }}

                </p>

            </div>

            <span
                class="inline-flex
                       px-3 py-1
                       rounded-full
                       text-xs
                       font-medium
                       bg-gray-100
                       text-gray-700"
            >
                OUT
            </span>

        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- DAFTAR STOK SAAT INI --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden
            mb-6">

    <div class="px-6 py-5
                border-b border-gray-200">

        <h2 class="font-semibold text-gray-900">
            Kondisi Stok Saat Ini
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Daftar stok barang yang tersedia saat ini.
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
                            Kode
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Barang
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
                            Stok
                        </th>

                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Minimum
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
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
                            Nilai
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-gray-100"
                >

                    @foreach($products as $product)

                        <tr class="hover:bg-gray-50">

                            {{-- KODE --}}

                            <td class="px-6 py-4">

                                <span
                                    class="font-medium
                                           text-gray-900"
                                >
                                    {{ $product->code }}
                                </span>

                            </td>


                            {{-- BARANG --}}

                            <td class="px-6 py-4">

                                <div
                                    class="font-medium
                                           text-gray-900"
                                >
                                    {{ $product->name }}
                                </div>

                            </td>


                            {{-- KATEGORI --}}

                            <td class="px-6 py-4">

                                <span
                                    class="text-gray-600"
                                >
                                    {{ $product->category?->name ?? '-' }}
                                </span>

                            </td>


                            {{-- STOK --}}

                            <td
                                class="px-6 py-4
                                       text-right"
                            >

                                <span
                                    class="font-semibold
                                           text-gray-900"
                                >
                                    {{ number_format($product->stock) }}
                                </span>

                                <span
                                    class="text-xs
                                           text-gray-400"
                                >
                                    {{ $product->unit }}
                                </span>

                            </td>


                            {{-- MINIMUM --}}

                            <td
                                class="px-6 py-4
                                       text-right
                                       text-gray-600"
                            >
                                {{ number_format(
                                    $product->minimum_stock
                                ) }}
                            </td>


                            {{-- STATUS --}}

                            <td class="px-6 py-4">

                                @if($product->stock <= 0)

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-900
                                               text-white"
                                    >
                                        Habis
                                    </span>

                                @elseif(
                                    $product->stock <=
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
                                        Menipis
                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-100
                                               text-gray-700"
                                    >
                                        Aman
                                    </span>

                                @endif

                            </td>


                            {{-- NILAI --}}

                            <td
                                class="px-6 py-4
                                       text-right
                                       font-medium
                                       text-gray-900"
                            >

                                Rp {{ number_format(
                                    $product->stock *
                                    $product->purchase_price,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="py-16 text-center">

            <div class="text-4xl mb-3">
                📦
            </div>

            <p class="font-medium text-gray-700">
                Tidak ada data produk
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Tidak ditemukan produk sesuai filter.
            </p>

        </div>

    @endif

</div>


{{-- ===================================================== --}}
{{-- RIWAYAT PERGERAKAN STOK --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden">

    <div class="px-6 py-5
                border-b border-gray-200">

        <h2 class="font-semibold text-gray-900">
            Riwayat Pergerakan Stok
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Riwayat barang masuk dan barang keluar.
        </p>

    </div>


    @if($movements->count())

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
                            Waktu
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Barang
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Pengguna
                        </th>

                        <th
                            class="px-6 py-4
                                   text-center
                                   font-semibold
                                   text-gray-600"
                        >
                            Jenis
                        </th>

                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Jumlah
                        </th>

                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-600"
                        >
                            Stok
                        </th>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-gray-600"
                        >
                            Keterangan
                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-gray-100"
                >

                    @foreach($movements as $movement)

                        <tr class="hover:bg-gray-50">

                            {{-- WAKTU --}}

                            <td class="px-6 py-4">

                                <div
                                    class="text-gray-700"
                                >
                                    {{ $movement->created_at
                                        ->format('d/m/Y') }}
                                </div>

                                <div
                                    class="mt-1
                                           text-xs
                                           text-gray-400"
                                >
                                    {{ $movement->created_at
                                        ->format('H:i:s') }}
                                </div>

                            </td>


                            {{-- BARANG --}}

                            <td class="px-6 py-4">

                                @if($movement->product)

                                    <div
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $movement->product->name }}
                                    </div>

                                    <div
                                        class="mt-1
                                               text-xs
                                               text-gray-400"
                                    >
                                        {{ $movement->product->code }}
                                    </div>

                                @else

                                    <span
                                        class="text-gray-400"
                                    >
                                        Produk tidak tersedia
                                    </span>

                                @endif

                            </td>


                            {{-- USER --}}

                            <td class="px-6 py-4">

                                @if($movement->user)

                                    <div
                                        class="font-medium
                                               text-gray-900"
                                    >
                                        {{ $movement->user->name }}
                                    </div>

                                    <div
                                        class="mt-1
                                               text-xs
                                               text-gray-400"
                                    >
                                        {{ ucfirst(
                                            $movement->user->role
                                        ) }}
                                    </div>

                                @else

                                    <span
                                        class="text-gray-400"
                                    >
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- TYPE --}}

                            <td
                                class="px-6 py-4
                                       text-center"
                            >

                                @if($movement->type === 'IN')

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-100
                                               text-gray-700"
                                    >
                                        IN
                                    </span>

                                @else

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-gray-900
                                               text-white"
                                    >
                                        OUT
                                    </span>

                                @endif

                            </td>


                            {{-- QUANTITY --}}

                            <td
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-gray-900"
                            >

                                {{ number_format(
                                    $movement->quantity
                                ) }}

                            </td>


                            {{-- STOCK --}}

                            <td
                                class="px-6 py-4
                                       text-right"
                            >

                                <div
                                    class="text-gray-500"
                                >
                                    {{ number_format(
                                        $movement->stock_before
                                    ) }}
                                </div>

                                <div
                                    class="mt-1
                                           font-semibold
                                           text-gray-900"
                                >
                                    →
                                    {{ number_format(
                                        $movement->stock_after
                                    ) }}
                                </div>

                            </td>


                            {{-- DESCRIPTION --}}

                            <td class="px-6 py-4">

                                <span
                                    class="text-gray-600"
                                >
                                    {{ $movement->description ?? '-' }}
                                </span>

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

            {{ $movements->links() }}

        </div>

    @else

        <div class="py-16 text-center">

            <div class="text-4xl mb-3">
                📋
            </div>

            <p class="font-medium text-gray-700">
                Belum ada pergerakan stok
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Riwayat barang masuk dan keluar akan muncul di sini.
            </p>

        </div>

    @endif

</div>

@endsection