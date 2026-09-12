@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('page-title', 'Laporan Penjualan')

@section('content')

{{-- ===================================================== --}}
{{-- HEADER --}}
{{-- ===================================================== --}}

<div class="flex flex-col sm:flex-row
            sm:items-center sm:justify-between
            gap-4 mb-6">

    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Laporan Penjualan
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Informasi penjualan servis, sparepart, dan project yang telah selesai.
        </p>

    </div>

</div>


{{-- ===================================================== --}}
{{-- SUMMARY UTAMA --}}
{{-- ===================================================== --}}

<div class="grid grid-cols-1
            sm:grid-cols-2
            lg:grid-cols-4
            gap-4 mb-6">


    {{-- TOTAL PENJUALAN --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Penjualan
                </p>

                <p class="mt-2 text-xl font-bold text-gray-900">

                    Rp {{ number_format(
                        $totalRevenue,
                        0,
                        ',',
                        '.'
                    ) }}

                </p>

            </div>

            <div class="w-10 h-10 rounded-lg
                        bg-gray-100
                        flex items-center justify-center">
                💰
            </div>

        </div>

    </div>


    {{-- TRANSAKSI SERVIS --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Transaksi Servis
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">

                    {{ number_format(
                        $totalServiceTransactions
                    ) }}

                </p>

            </div>

            <div class="w-10 h-10 rounded-lg
                        bg-gray-100
                        flex items-center justify-center">
                🧾
            </div>

        </div>

    </div>


    {{-- PENDAPATAN JASA --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Pendapatan Jasa
                </p>

                <p class="mt-2 text-xl font-bold text-gray-900">

                    Rp {{ number_format(
                        $totalServiceRevenue,
                        0,
                        ',',
                        '.'
                    ) }}

                </p>

            </div>

            <div class="w-10 h-10 rounded-lg
                        bg-gray-100
                        flex items-center justify-center">
                🔧
            </div>

        </div>

    </div>


    {{-- PROJECT SELESAI --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Project Selesai
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">

                    {{ number_format(
                        $totalProjects
                    ) }}

                </p>

            </div>

            <div class="w-10 h-10 rounded-lg
                        bg-gray-100
                        flex items-center justify-center">
                🛠️
            </div>

        </div>

    </div>

</div>



{{-- ===================================================== --}}
{{-- SUMMARY PENDAPATAN --}}
{{-- ===================================================== --}}

<div class="grid grid-cols-1
            sm:grid-cols-2
            lg:grid-cols-3
            gap-4 mb-6">


    {{-- MEKANIK --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <p class="text-sm text-gray-500">
            Bagian Mekanik
        </p>

        <p class="mt-2 text-xl font-bold text-gray-900">

            Rp {{ number_format(
                $totalServiceMechanic,
                0,
                ',',
                '.'
            ) }}

        </p>

        <p class="mt-1 text-xs text-gray-500">
            60% dari pendapatan jasa.
        </p>

    </div>


    {{-- OWNER JASA --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <p class="text-sm text-gray-500">
            Bagian Owner Jasa
        </p>

        <p class="mt-2 text-xl font-bold text-gray-900">

            Rp {{ number_format(
                $totalServiceOwner,
                0,
                ',',
                '.'
            ) }}

        </p>

        <p class="mt-1 text-xs text-gray-500">
            40% dari pendapatan jasa.
        </p>

    </div>


    {{-- PROFIT SPAREPART --}}

    <div class="bg-white border border-gray-200 rounded-xl p-5">

        <p class="text-sm text-gray-500">
            Profit Sparepart
        </p>

        <p class="mt-2 text-xl font-bold text-gray-900">

            Rp {{ number_format(
                $totalProductProfit,
                0,
                ',',
                '.'
            ) }}

        </p>

        <p class="mt-1 text-xs text-gray-500">
            Harga jual dikurangi modal.
        </p>

    </div>

</div>



{{-- ===================================================== --}}
{{-- RINGKASAN OWNER --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            p-5
            mb-6">

    <div class="flex flex-col sm:flex-row
                sm:items-center
                sm:justify-between
                gap-3">

        <div>

            <h2 class="font-semibold text-gray-900">
                Pendapatan Owner
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Total pendapatan owner dari jasa dan profit sparepart.
            </p>

        </div>

        <div class="text-left sm:text-right">

            <p class="text-2xl font-bold text-gray-900">

                Rp {{ number_format(
                    $totalOwnerIncome,
                    0,
                    ',',
                    '.'
                ) }}

            </p>

        </div>

    </div>


    <div class="mt-5
                grid grid-cols-1
                md:grid-cols-2
                gap-4">


        {{-- OWNER JASA --}}

        <div class="border border-gray-200
                    rounded-lg
                    p-4">

            <p class="text-xs text-gray-500">
                Jasa 40%
            </p>

            <p class="mt-1 font-semibold text-gray-900">

                Rp {{ number_format(
                    $totalServiceOwner,
                    0,
                    ',',
                    '.'
                ) }}

            </p>

        </div>


        {{-- PROFIT SPAREPART --}}

        <div class="border border-gray-200
                    rounded-lg
                    p-4">

            <p class="text-xs text-gray-500">
                Profit Sparepart
            </p>

            <p class="mt-1 font-semibold text-gray-900">

                Rp {{ number_format(
                    $totalProductProfit,
                    0,
                    ',',
                    '.'
                ) }}

            </p>

        </div>

    </div>

</div>



{{-- ===================================================== --}}
{{-- FILTER --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            p-5
            mb-6">

    <div class="mb-4">

        <h2 class="font-semibold text-gray-900">
            Filter Laporan
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Gunakan filter tanggal dan jenis penjualan.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('admin.reports.sales') }}"
        class="grid grid-cols-1 md:grid-cols-4 gap-4"
    >


        {{-- DARI TANGGAL --}}

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


        {{-- SAMPAI TANGGAL --}}

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
                Jenis Penjualan
            </label>

            <select
                name="type"
                class="w-full
                       rounded-lg
                       border border-gray-300
                       px-4 py-2.5
                       text-sm
                       bg-white
                       outline-none
                       focus:border-gray-500
                       focus:ring-1
                       focus:ring-gray-500"
            >

                <option
                    value=""
                    {{ $type === '' ? 'selected' : '' }}
                >
                    Semua
                </option>

                <option
                    value="service"
                    {{ $type === 'service' ? 'selected' : '' }}
                >
                    Servis
                </option>

                <option
                    value="product"
                    {{ $type === 'product' ? 'selected' : '' }}
                >
                    Sparepart
                </option>

                <option
                    value="project"
                    {{ $type === 'project' ? 'selected' : '' }}
                >
                    Project
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
                href="{{ route('admin.reports.sales') }}"
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
{{-- TRANSAKSI SERVIS --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden
            mb-6">

    <div class="px-6 py-5
                border-b border-gray-200">

        <div class="flex flex-col
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                    gap-2">

            <div>

                <h2 class="font-semibold text-gray-900">
                    Transaksi Servis
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pendapatan jasa dan pembagian hasil servis.
                </p>

            </div>

            <span
                class="inline-flex
                       items-center
                       px-3 py-1
                       rounded-full
                       bg-gray-100
                       text-gray-600
                       text-xs
                       font-medium"
            >
                {{ $totalServiceTransactions }} transaksi
            </span>

        </div>

    </div>


    @if($serviceTransactions->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Tanggal
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Kode
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Customer
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Jasa
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Mekanik 60%
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Owner 40%
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach($serviceTransactions as $transaction)

                        <tr class="hover:bg-gray-50">


                            {{-- TANGGAL --}}

                            <td class="px-6 py-4">

                                <div class="text-gray-700">
                                    {{ $transaction->created_at->format('d/m/Y') }}
                                </div>

                                <div class="mt-1 text-xs text-gray-400">
                                    {{ $transaction->created_at->format('H:i') }}
                                </div>

                            </td>


                            {{-- KODE --}}

                            <td class="px-6 py-4">

                                <span class="font-medium text-gray-900">
                                    {{ $transaction->transaction_code }}
                                </span>

                            </td>


                            {{-- CUSTOMER --}}

                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $transaction->customer?->name ?? '-' }}
                                </div>

                                @if($transaction->vehicle)

                                    <div class="mt-1 text-xs text-gray-400">
                                        {{ $transaction->vehicle->plate_number }}
                                    </div>

                                @endif

                            </td>


                            {{-- JASA --}}

                            <td class="px-6 py-4 text-right">

                                <span class="font-medium text-gray-900">

                                    Rp {{ number_format(
                                        $transaction->service_revenue,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </td>


                            {{-- MEKANIK --}}

                            <td class="px-6 py-4 text-right">

                                Rp {{ number_format(
                                    $transaction->mechanic_share,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>


                            {{-- OWNER --}}

                            <td class="px-6 py-4 text-right">

                                Rp {{ number_format(
                                    $transaction->owner_share,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>


                <tfoot class="bg-gray-50 border-t border-gray-200">

                    <tr>

                        <td
                            colspan="3"
                            class="px-6 py-4
                                   font-semibold
                                   text-gray-700"
                        >
                            Total
                        </td>


                        <td class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-900">

                            Rp {{ number_format(
                                $totalServiceRevenue,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-900">

                            Rp {{ number_format(
                                $totalServiceMechanic,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-900">

                            Rp {{ number_format(
                                $totalServiceOwner,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    @else

        <div class="py-16 text-center">

            <div class="text-4xl mb-3">
                🔧
            </div>

            <p class="font-medium text-gray-700">
                Belum ada transaksi servis
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Tidak ditemukan transaksi servis pada periode tersebut.
            </p>

        </div>

    @endif

</div>



{{-- ===================================================== --}}
{{-- PENJUALAN SPAREPART --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden
            mb-6">

    <div class="px-6 py-5
                border-b border-gray-200">

        <div class="flex flex-col
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                    gap-2">

            <div>

                <h2 class="font-semibold text-gray-900">
                    Penjualan Sparepart
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Detail harga beli, harga jual, modal, dan profit sparepart.
                </p>

            </div>

            <span
                class="inline-flex
                       items-center
                       px-3 py-1
                       rounded-full
                       bg-gray-100
                       text-gray-600
                       text-xs
                       font-medium"
            >
                {{ $totalProductTransactions }} transaksi
            </span>

        </div>

    </div>


    @if($productTransactions->count())

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Tanggal
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Kode
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Customer
                        </th>

                        <th class="px-6 py-4 text-left
                                   font-semibold text-gray-600">
                            Sparepart
                        </th>

                        <th class="px-6 py-4 text-center
                                   font-semibold text-gray-600">
                            Qty
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Harga Beli
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Harga Jual
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Modal
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Penjualan
                        </th>

                        <th class="px-6 py-4 text-right
                                   font-semibold text-gray-600">
                            Profit
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach($productTransactions as $transaction)

                        @foreach(
                            $transaction->items->whereNotNull('product_id')
                            as $item
                        )

                            <tr class="hover:bg-gray-50">


                                {{-- TANGGAL --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-gray-700">
                                        {{ $transaction->created_at->format('d/m/Y') }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-400">
                                        {{ $transaction->created_at->format('H:i') }}
                                    </div>

                                </td>


                                {{-- KODE --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <span class="font-medium text-gray-900">
                                        {{ $transaction->transaction_code }}
                                    </span>

                                </td>


                                {{-- CUSTOMER --}}

                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $transaction->customer?->name ?? '-' }}
                                    </div>

                                    @if($transaction->vehicle)

                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ $transaction->vehicle->plate_number }}
                                        </div>

                                    @endif

                                </td>


                                {{-- SPAREPART --}}

                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $item->product?->name ?? '-' }}
                                    </div>

                                    @if($item->product?->code)

                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ $item->product->code }}
                                        </div>

                                    @endif

                                </td>


                                {{-- QTY --}}

                                <td class="px-6 py-4 text-center">

                                    {{ $item->quantity }}

                                </td>


                                {{-- HARGA BELI --}}

                                <td class="px-6 py-4 text-right whitespace-nowrap">

                                    Rp {{ number_format(
                                        $item->purchase_price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- HARGA JUAL --}}

                                <td class="px-6 py-4 text-right whitespace-nowrap">

                                    Rp {{ number_format(
                                        $item->price,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- MODAL --}}

                                <td class="px-6 py-4 text-right whitespace-nowrap">

                                    Rp {{ number_format(
                                        $item->purchase_price * $item->quantity,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- PENJUALAN --}}

                                <td class="px-6 py-4 text-right whitespace-nowrap">

                                    Rp {{ number_format(
                                        $item->subtotal,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>


                                {{-- PROFIT --}}

                                <td class="px-6 py-4
                                           text-right
                                           whitespace-nowrap
                                           font-semibold
                                           text-gray-900">

                                    Rp {{ number_format(
                                        (
                                            $item->price
                                            -
                                            $item->purchase_price
                                        )
                                        * $item->quantity,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    @endforeach

                </tbody>


                <tfoot class="bg-gray-50 border-t border-gray-200">

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-4
                                   font-semibold
                                   text-gray-700"
                        >
                            Total Sparepart
                        </td>


                        <td class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-900">

                            Rp {{ number_format(
                                $totalProductCost,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-900">

                            Rp {{ number_format(
                                $totalProductRevenue,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        <td class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-gray-900">

                            Rp {{ number_format(
                                $totalProductProfit,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    @else

        <div class="py-16 text-center">

            <div class="text-4xl mb-3">
                📦
            </div>

            <p class="font-medium text-gray-700">
                Belum ada penjualan sparepart
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Tidak ditemukan penjualan sparepart pada periode tersebut.
            </p>

        </div>

    @endif

</div>



{{-- ===================================================== --}}
{{-- PROJECT --}}
{{-- ===================================================== --}}

<div class="bg-white
            border border-gray-200
            rounded-xl
            overflow-hidden">

    {{-- ================================================= --}}
    {{-- HEADER PROJECT --}}
    {{-- ================================================= --}}

    <div class="px-6 py-5
                border-b border-gray-200">

        <div class="flex flex-col
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                    gap-2">

            <div>

                <h2 class="font-semibold text-gray-900">
                    Project Selesai
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan project dan detail pembagian hasil jasa.
                </p>

            </div>

            <span
                class="inline-flex
                       items-center
                       px-3 py-1
                       rounded-full
                       bg-gray-100
                       text-gray-600
                       text-xs
                       font-medium"
            >
                {{ $totalProjects }} project
            </span>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- DATA PROJECT --}}
    {{-- ================================================= --}}

    @if($projects->count())

        <div class="divide-y divide-gray-200">

            @foreach($projects as $project)

                @php

                    /* BARANG / MATERIAL */
                    $projectProducts = $project->items->filter(function ($item) {
                        return $item->product_id && $item->product;
                    });

                    /* JASA */
                    $projectServices = $project->items->filter(function ($item) {
                        return $item->service_id && $item->service;
                    });

                    /* TOTAL MATERIAL */
                    $projectMaterialTotal = $projectProducts->sum('subtotal');

                    /* TOTAL JASA */
                    $projectServiceTotal = $projectServices->sum('subtotal');

                    /* PEMBAGIAN JASA */
                    $projectMechanicShare = $projectServiceTotal * 0.60;
                    $projectOwnerShare = $projectServiceTotal * 0.40;

                    /* BIAYA TAMBAHAN */
                    $projectAdditionalCost = $project->costs->sum('amount');

                    /* TOTAL PROJECT */
                    $projectTotal =
                        $projectMaterialTotal
                        + $projectServiceTotal
                        + $projectAdditionalCost;

                @endphp


                {{-- ================================================= --}}
                {{-- SATU PROJECT --}}
                {{-- ================================================= --}}

                <div
                    x-data="{ open: false }"
                    class="relative"
                >

                    {{-- RINGKASAN PROJECT --}}

                    <div class="px-6 py-5">

                        <div class="flex flex-col
                                    lg:flex-row
                                    lg:items-center
                                    lg:justify-between
                                    gap-4">

                            {{-- IDENTITAS PROJECT --}}

                            <div>

                                <div class="flex items-center gap-2 flex-wrap">

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $project->name }}
                                    </h3>

                                    <span
                                        class="px-2.5 py-1
                                               rounded-full
                                               bg-gray-100
                                               text-gray-600
                                               text-xs
                                               font-medium"
                                    >
                                        {{ $project->code }}
                                    </span>

                                </div>

                                <div class="mt-2 text-sm text-gray-500">

                                    {{ $project->customer_name ?: 'Customer tidak tersedia' }}

                                    <span class="mx-1">
                                        •
                                    </span>

                                    {{ $project->created_at->format('d/m/Y H:i') }}

                                </div>

                            </div>


                            {{-- TOTAL + DETAIL --}}

                            <div class="flex flex-col sm:flex-row
                                        sm:items-center
                                        gap-3">

                                <div class="lg:text-right">

                                    <p class="text-xs text-gray-500">
                                        Total Project
                                    </p>

                                    <p class="mt-1 text-xl
                                              font-bold
                                              text-gray-900">

                                        Rp {{ number_format(
                                            $projectTotal,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </p>

                                </div>


                                <button
                                    type="button"
                                    @click="open = true"
                                    class="inline-flex
                                           items-center
                                           justify-center
                                           gap-2
                                           px-4 py-2.5
                                           bg-gray-900
                                           text-white
                                           rounded-lg
                                           text-sm
                                           font-medium
                                           hover:bg-gray-800
                                           transition"
                                >
                                    
                                    Detail
                                </button>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MODAL DETAIL PROJECT --}}
                    {{-- ================================================= --}}

                    <div
                        x-show="open"
                        style="display: none;"
                        @keydown.escape.window="open = false"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    >

                        {{-- BACKDROP --}}

                        <div
                            class="absolute inset-0 bg-black/40"
                            @click="open = false"
                        ></div>


                        {{-- MODAL --}}

                        <div
                            class="relative
                                   w-full
                                   max-w-5xl
                                   max-h-[90vh]
                                   overflow-y-auto
                                   bg-white
                                   rounded-2xl
                                   shadow-2xl"
                            @click.stop
                        >

                            {{-- MODAL HEADER --}}

                            <div
                                class="sticky top-0 z-10
                                       flex items-center
                                       justify-between
                                       gap-4
                                       px-6 py-5
                                       bg-white
                                       border-b border-gray-200"
                            >

                                <div>

                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Detail Project
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $project->name }} • {{ $project->code }}
                                    </p>

                                </div>

                                <button
                                    type="button"
                                    @click="open = false"
                                    class="w-9 h-9
                                           inline-flex
                                           items-center
                                           justify-center
                                           rounded-lg
                                           border border-gray-200
                                           text-gray-500
                                           hover:bg-gray-50
                                           hover:text-gray-700"
                                >
                                    ✕
                                </button>

                            </div>


                            <div class="px-6 py-6">

                                {{-- INFO PROJECT --}}

                                <div class="grid grid-cols-1
                                            md:grid-cols-3
                                            gap-4 mb-6">

                                    <div class="border border-gray-200 rounded-xl p-4">
                                        <p class="text-xs text-gray-500">
                                            Customer
                                        </p>
                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $project->customer_name ?: '-' }}
                                        </p>
                                    </div>

                                    <div class="border border-gray-200 rounded-xl p-4">
                                        <p class="text-xs text-gray-500">
                                            Tanggal
                                        </p>
                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $project->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>

                                    <div class="border border-gray-200 rounded-xl p-4">
                                        <p class="text-xs text-gray-500">
                                            Total Project
                                        </p>
                                        <p class="mt-1 font-semibold text-gray-900">
                                            Rp {{ number_format(
                                                $projectTotal,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </p>
                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- BARANG / MATERIAL --}}
                                {{-- ================================================= --}}

                                <div>

                                    <div class="flex items-center justify-between gap-3 mb-3">

                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                Barang / Material
                                            </h4>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Rincian barang yang digunakan dalam project.
                                            </p>
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            Rp {{ number_format(
                                                $projectMaterialTotal,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </span>

                                    </div>

                                    @if($projectProducts->count())

                                        <div class="overflow-x-auto border border-gray-200 rounded-xl">

                                            <table class="w-full text-sm">

                                                <thead class="bg-gray-50 border-b border-gray-200">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                                                            Barang
                                                        </th>
                                                        <th class="px-4 py-3 text-center font-semibold text-gray-600">
                                                            Qty
                                                        </th>
                                                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                            Harga
                                                        </th>
                                                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                            Subtotal
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-gray-100">

                                                    @foreach($projectProducts as $item)

                                                        <tr class="hover:bg-gray-50">

                                                            <td class="px-4 py-3">
                                                                <div class="font-medium text-gray-900">
                                                                    {{ $item->product->name }}
                                                                </div>

                                                                @if($item->product->code)
                                                                    <div class="mt-1 text-xs text-gray-400">
                                                                        {{ $item->product->code }}
                                                                    </div>
                                                                @endif
                                                            </td>

                                                            <td class="px-4 py-3 text-center">
                                                                {{ $item->quantity }}
                                                            </td>

                                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                                Rp {{ number_format(
                                                                    $item->price,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </td>

                                                            <td class="px-4 py-3 text-right whitespace-nowrap font-medium">
                                                                Rp {{ number_format(
                                                                    $item->subtotal,
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

                                        <div class="border border-gray-200 rounded-xl px-4 py-6 text-center text-sm text-gray-500">
                                            Tidak ada barang/material.
                                        </div>

                                    @endif

                                </div>


                                {{-- ================================================= --}}
                                {{-- JASA --}}
                                {{-- ================================================= --}}

                                <div class="mt-8">

                                    <div class="flex items-center justify-between gap-3 mb-3">

                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                Jasa
                                            </h4>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Pendapatan jasa project dengan pembagian 60% mekanik dan 40% owner.
                                            </p>
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            Rp {{ number_format(
                                                $projectServiceTotal,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </span>

                                    </div>

                                    @if($projectServices->count())

                                        <div class="overflow-x-auto border border-gray-200 rounded-xl">

                                            <table class="w-full text-sm">

                                                <thead class="bg-gray-50 border-b border-gray-200">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                                                            Jasa
                                                        </th>
                                                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                            Harga
                                                        </th>
                                                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                            Mekanik 60%
                                                        </th>
                                                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                            Owner 40%
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-gray-100">

                                                    @foreach($projectServices as $item)

                                                        @php
                                                            $serviceAmount = (float) $item->subtotal;
                                                            $mechanicShare = $serviceAmount * 0.60;
                                                            $ownerShare = $serviceAmount * 0.40;
                                                        @endphp

                                                        <tr class="hover:bg-gray-50">

                                                            <td class="px-4 py-3">
                                                                <span class="font-medium text-gray-900">
                                                                    {{ $item->service->name }}
                                                                </span>
                                                            </td>

                                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                                Rp {{ number_format(
                                                                    $serviceAmount,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </td>

                                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                                Rp {{ number_format(
                                                                    $mechanicShare,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </td>

                                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                                Rp {{ number_format(
                                                                    $ownerShare,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </td>

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                                    <tr>
                                                        <td class="px-4 py-3 font-semibold text-gray-700">
                                                            Total Jasa
                                                        </td>

                                                        <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                                            Rp {{ number_format(
                                                                $projectServiceTotal,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}
                                                        </td>

                                                        <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                                            Rp {{ number_format(
                                                                $projectMechanicShare,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}
                                                        </td>

                                                        <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                                            Rp {{ number_format(
                                                                $projectOwnerShare,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}
                                                        </td>
                                                    </tr>
                                                </tfoot>

                                            </table>

                                        </div>

                                    @else

                                        <div class="border border-gray-200 rounded-xl px-4 py-6 text-center text-sm text-gray-500">
                                            Tidak ada jasa.
                                        </div>

                                    @endif

                                </div>


                                {{-- ================================================= --}}
                                {{-- BIAYA TAMBAHAN --}}
                                {{-- ================================================= --}}

                                <div class="mt-8">

                                    <div class="flex items-center justify-between gap-3 mb-3">

                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                Biaya Tambahan
                                            </h4>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Biaya tambahan yang dicatat dalam project.
                                            </p>
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            Rp {{ number_format(
                                                $projectAdditionalCost,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </span>

                                    </div>

                                    @if($project->costs->count())

                                        <div class="overflow-x-auto border border-gray-200 rounded-xl">

                                            <table class="w-full text-sm">

                                                <thead class="bg-gray-50 border-b border-gray-200">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left font-semibold text-gray-600">
                                                            Nama Biaya
                                                        </th>
                                                        <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                            Jumlah
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-gray-100">

                                                    @foreach($project->costs as $cost)

                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-3">
                                                                <span class="font-medium text-gray-900">
                                                                    {{ $cost->name }}
                                                                </span>
                                                            </td>

                                                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                                                Rp {{ number_format(
                                                                    $cost->amount,
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

                                        <div class="border border-gray-200 rounded-xl px-4 py-6 text-center text-sm text-gray-500">
                                            Tidak ada biaya tambahan.
                                        </div>

                                    @endif

                                </div>


                                {{-- ================================================= --}}
                                {{-- RINGKASAN PROJECT --}}
                                {{-- ================================================= --}}

                                <div class="mt-8 border-t border-gray-200 pt-6">

                                    <div class="ml-auto w-full md:w-1/2 lg:w-2/5">

                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-sm text-gray-500">
                                                Barang / Material
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format(
                                                    $projectMaterialTotal,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-sm text-gray-500">
                                                Jasa
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format(
                                                    $projectServiceTotal,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-sm text-gray-500">
                                                Biaya Tambahan
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format(
                                                    $projectAdditionalCost,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                                            <span class="text-sm text-gray-500">
                                                Bagian Mekanik dari Jasa (60%)
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format(
                                                    $projectMechanicShare,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between py-2">
                                            <span class="text-sm text-gray-500">
                                                Bagian Owner dari Jasa (40%)
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format(
                                                    $projectOwnerShare,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between mt-3 pt-4 border-t-2 border-gray-900">
                                            <span class="font-semibold text-gray-900">
                                                TOTAL PROJECT
                                            </span>
                                            <span class="text-lg font-bold text-gray-900">
                                                Rp {{ number_format(
                                                    $projectTotal,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- MODAL FOOTER --}}

                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">

                                <button
                                    type="button"
                                    @click="open = false"
                                    class="px-4 py-2.5
                                           border border-gray-300
                                           rounded-lg
                                           text-sm
                                           font-medium
                                           text-gray-700
                                           hover:bg-white"
                                >
                                    Tutup
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="py-16 text-center">

            <div class="text-4xl mb-3">
                🛠️
            </div>

            <p class="font-medium text-gray-700">
                Belum ada project selesai
            </p>

            <p class="text-sm text-gray-500 mt-1">
                Tidak ditemukan project selesai pada periode tersebut.
            </p>

        </div>

    @endif

</div>

@endsection